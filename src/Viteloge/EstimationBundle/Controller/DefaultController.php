<?php

namespace Viteloge\EstimationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Viteloge\CoreBundle\Entity\Estimate;

/**
 * @Route("prix-immobilier/")
 */
class DefaultController extends Controller
{
    public function startAction( Request $request ) {
        // change with index
    }

    /**
     * @Route("votre-estimation/")
     * @Method("GET")
     * @Template()
     */
    public function indexAction( Request $request ) {
        $translated = $this->get('translator');

        // Breadcrumbs
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem(
            $translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
            $this->get('router')->generate('viteloge_frontend_homepage')
        );
        $breadcrumbs->addItem(
            $translated->trans('breadcrumb.estimate', array(), 'breadcrumbs')
        );
        // --

        $estimate = new Estimate();
        $form = $this->createForm( 'estimation', $estimate );

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("votre-estimation/")
     * @Method("POST")
     * @Template("VitelogeEstimationBundle:Default:index.html.twig")
     */
    public function indexPostAction( Request $request ) {
        $translated = $this->get('translator');

        // Breadcrumbs
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem(
            $translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
            $this->get('router')->generate('viteloge_frontend_homepage')
        );
        $breadcrumbs->addItem(
            $translated->trans('breadcrumb.estimate', array(), 'breadcrumbs')
        );
        // --

        $estimate = new Estimate();
        $form = $this->createForm( 'estimation', $estimate );

        $post_is_intro = false;
        if ( $request->query->get( 'intro', false ) ) {
            $post_is_intro = true;

            $form_intro = $this->createForm( 'intro_estimation', $estimate );
            $form_intro->handleRequest( $request );
        }

        $form = $this->createForm( 'estimation', $estimate, array(
            "action" => $this->generateUrl( 'viteloge_estimation_default_indexpost')
        ) );

        if ( ! $post_is_intro ) {
            $form->handleRequest( $request );

            if ( $form->isValid() ) {
                $handler = $this->get('viteloge_estimation.estimate.handler');
                $handler->save( $estimate );

                return $this->redirect(
                    $this->generateUrl(
                        'viteloge_estimation_default_resultat',
                        array( 'id' => $estimate->getId() )
                    )
                );
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("votre-estimation/resultat/{id}")
     * @Method("GET")
     * @Template()
     */
    public function resultatAction( Estimate $estimate ) {
        $translated = $this->get('translator');

        // Breadcrumbs
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem(
            $translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
            $this->get('router')->generate('viteloge_frontend_homepage')
        );
        $breadcrumbs->addItem(
            $translated->trans('breadcrumb.estimate', array(), 'breadcrumbs'),
            $this->get('router')->generate('viteloge_estimation_default_index')
        );
        $breadcrumbs->addItem(
            $translated->trans('breadcrumb.estimate.result', array(), 'breadcrumbs')
        );
        // --

        $computer = $this->get( 'viteloge_estimation.estimate.computer' );

        $form = null;
        if ( ! $estimate->hasAgencyRequest() ) {
            $form = $this->createForm( 'contact_estimation', $estimate );
        }

        $result = $computer->estimate( $estimate );
        $debug = false;
        if ( $result ) {
            $debug = $result["debug"];
            unset( $result["debug"] );
        }

        return array(
            'computed_estimation' => $result,
            'debug_result' => $debug,
            'form' => $form ? $form->createView() : null
        );
    }

    /**
     * @Route("votre-estimation/resultat/{id}")
     * @Method("POST")
     * @Template("VitelogeEstimationBundle:Default:resultat.html.twig")
     */
    public function resultatContactAction( Request $request, Estimate $estimate ) {

        $form = $this->createForm( 'contact_estimation', $estimate );

        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $handler = $this->get('viteloge_estimation.estimate.handler');
            $handler->save( $estimate );
            return $this->redirect(
                $this->generateUrl(
                    'viteloge_estimation_default_contact'
                )
            );
        }

        $computer = $this->get( 'viteloge_estimation.estimate.computer' );
        $result = $computer->estimate( $estimate );
        $debug = false;
        if ( $result ) {
            $debug = $result["debug"];
            unset( $result["debug"] );
        }

        return array(
            'computed_estimation' => $result,
            'debug_result' => $debug,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("votre-estimation/contact")
     * @Template()
     */
    public function contactAction() {
        $translated = $this->get('translator');

        // Breadcrumbs
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem(
            $translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
            $this->get('router')->generate('viteloge_frontend_homepage')
        );
        $breadcrumbs->addItem(
            $translated->trans('breadcrumb.estimate', array(), 'breadcrumbs'),
            $this->get('router')->generate('viteloge_estimation_default_index')
        );
        $breadcrumbs->addItem(
            $translated->trans('breadcrumb.estimate.contact', array(), 'breadcrumbs')
        );
        // --

        return array(

        );
    }


}
