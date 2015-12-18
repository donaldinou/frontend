<?php

// legacy controller, probably use with viteloge mail server
namespace Viteloge\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Viteloge\CoreBundle\Entity\SendgridEvent;

/**
 * @Route("/sendgrid")
 */
class SendgridController extends Controller {

    const LOCK_FILE = "sendgrid_notification.lock";

    /**
     * @Route("/notify")
     * @Method("POST")
     */
    public function notifyAction( Request $request ) {
        $om = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');
        $alerte_repo = $om->getRepository( 'VitelogeCoreBundle:UserSearch' );

        $disabled_users = 0;
        $disabled_emails = 0;

        $accepted_sg_events = array( "bounce", "dropped" );

        $post_data = $request->getContent();

        $event = new SendgridEvent();
        $event->setData( $post_data );
        $om->persist( $event );
        $om->flush();

        if ( $request->query->get( 'log_only' ) ) {
            return new Response( 'Not processing this set of data', Response::HTTP_PRECONDITION_FAILED );
        }

        if ( false === ( $lock = $this->canLock() ) ) {
            return new Response( 'Already processing something', Response::HTTP_SERVICE_UNAVAILABLE );
        }

        $data = json_decode( $post_data, true );
        if ( ( ! $data ) || ! is_array( $data ) ) {
            $this->unlock( $lock );
            return new Response( 'Invalid data provided', Response::HTTP_INTERNAL_SERVER_ERROR );
        }
        foreach ( $data as $sg_event ) {
            if ( ! in_array( $sg_event["event"], $accepted_sg_events ) ) {
                continue;
            }

            $user = $userManager->findUserByEmail( $sg_event['email'] );
            if ( $user && $user->isAccountNonLocked() ) {
                $user->setLocked( true );
                $userManager->updateUser($user);
                $disabled_users++;
            }
            $disabled_emails += $alerte_repo->disableEmails( $sg_event['email'] );
        }

        $this->unlock( $lock );
        return new JsonResponse( array(
            "users" => $disabled_users,
            "emails" => $disabled_emails
        ) );
    }

    private function canLock() {
        $lock = fopen( implode( "/", array( sys_get_temp_dir(), self::LOCK_FILE ) ), 'w+' );
        if ( flock( $lock, LOCK_EX | LOCK_NB ) ) {
            ftruncate( $lock, 0 );
            fwrite( $lock, time() );
            fflush( $lock );
            return $lock;
        }
        return false;
    }

    private function unlock( $lock ) {
        flock( $lock, LOCK_UN );
        fclose( $lock );
    }
}
