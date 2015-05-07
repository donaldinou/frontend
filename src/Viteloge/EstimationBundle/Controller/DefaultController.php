<?php

namespace Viteloge\EstimationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Viteloge\EstimationBundle\Entity\Estimation;

/**
 * @Route("prix-immobilier/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("votre-estimation/")
     * @Method("GET")
     * @Template()
     */
    public function indexAction( Request $request )
    {

        $e = new Estimation();

        $form = $this->createForm( 'estimation', $e );

        return array(
            'LAYOUT_TITLE' => $this->t( 'estimation.form_page.title' ),
            'form' => $form->createView()
        );
    }

    /**
     * @Route("votre-estimation/")
     * @Method("POST")
     * @Template("VitelogeEstimationBundle:Default:index.html.twig")
     */
    public function indexPostAction( Request $request )
    {
        $estimation = new Estimation();

        $post_is_intro = false;
        if ( $request->query->get( 'intro', false ) ) {
            $post_is_intro = true;

            $form_intro = $this->createForm( 'intro_estimation', $estimation );
            $form_intro->handleRequest( $request );
        }

        $form = $this->createForm( 'estimation', $estimation, array(
            "action" => $this->generateUrl( 'viteloge_estimation_default_indexpost')
        ) );

        if ( ! $post_is_intro ) {
            $form->handleRequest( $request );

            if ( $form->isValid() ) {
                $handler = $this->get('viteloge_estimation.estimation.handler');
                $handler->save( $estimation );

                return $this->redirect(
                    $this->generateUrl(
                        'viteloge_estimation_default_resultat',
                        array( 'id' => $estimation->getId() )
                    )
                );
            }
        }



        return array(
            'LAYOUT_TITLE' => $this->t( 'estimation.form_page.title' ),
            'form' => $form->createView()
        );
    }

    /**
     * @Route("votre-estimation/resultat/{id}")
     * @Method("GET")
     * @Template()
     */
    public function resultatAction( Estimation $estimation ) {

        $computer = $this->get( 'viteloge_estimation.estimation.computer' );

        $form = null;
        if ( ! $estimation->getDemandeAgence() ) {
            $form = $this->createForm( 'contact_estimation', $estimation );
        }

        $result = $computer->estimate( $estimation );
        $debug = false;
        if ( $result ) {
            $debug = $result["debug"];
            unset( $result["debug"] );
        }

        return array(
            'computed_estimation' => $result,
            'debug_result' => $debug,
            'form' => $form ? $form->createView() : null,
            'LAYOUT_TITLE' => $this->t( 'estimation.result.title' )
        );
    }

    /**
     * @Route("votre-estimation/resultat/{id}")
     * @Method("POST")
     * @Template("VitelogeEstimationBundle:Default:resultat.html.twig")
     */
    public function resultatContactAction( Request $request, Estimation $estimation ) {

        $form = $this->createForm( 'contact_estimation', $estimation );

        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $handler = $this->get('viteloge_estimation.estimation.handler');
            $handler->save( $estimation );
            return $this->redirect(
                $this->generateUrl(
                    'viteloge_estimation_default_contact'
                )
            );
        }

        $computer = $this->get( 'viteloge_estimation.estimation.computer' );
        $result = $computer->estimate( $estimation );
        $debug = false;
        if ( $result ) {
            $debug = $result["debug"];
            unset( $result["debug"] );
        }

        return array(
            'computed_estimation' => $result,
            'debug_result' => $debug,
            'form' => $form->createView(),
            'LAYOUT_TITLE' => $this->t( 'estimation.result.title' )
        );
    }

    /**
     * @Route("votre-estimation/contact")
     * @Template()
     */
    public function contactAction() {
        return array(
            "LAYOUT_TITLE" => $this->t( 'estimation.contact.title' )
        );
    }

    private function t( $ref ) {
        return $this->get( 'translator' )->trans( $ref );
    }


}
