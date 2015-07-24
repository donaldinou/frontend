<?php

namespace Viteloge\EstimationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Acreat\InseeBundle\Entity\InseeCity;
use Viteloge\CoreBundle\Entity\Estimate;

/**
 * @Route("prix-immobilier/")
 */
class CityStatsController extends Controller
{
    /**
     * @Route("{slug}/{id}",requirements={"id"="^[0-9][0-9abAB][0-9]+"})
     * @Template
     */
    public function cityAction( InseeCity $city ) {

        $om = $this->getDoctrine()->getManager();
        $baro_repo = $om->getRepository( 'Viteloge\EstimationBundle\Entity\Barometre' );
        $city_repo = $om->getRepository( 'AcreatInseeBundle:InseeCity' );

        $barometres = $baro_repo->findSortedSalesFor( $city );

        $estimate = new Estimate();
        $estimate->setVille( $city->getCodeInsee() );

        $form = $this->createForm( 'intro_estimation', $estimate, array(
            'action' => $this->generateUrl( 'viteloge_estimation_default_index', array( 'intro' => 1 ) )
        ) );

        return array(
            'city' => $city,
            'form' => $form->createView(),
            'barometres' => $barometres,
            'surrounding_cities' => $city_repo->findNeighbors( $city, 30, 8 ),
            'main_title' => $this->t( 'city_eval.main_title', array(
                '%name%' => $city->getName( true ),
                '%cp%' => $city->getCp(),
                '%charniere%' => $city->getCharniere()
            ) ),
            'LAYOUT_TITLE' => $this->t( 'city_eval.title', array(
                '%name%' => $city->getName( true ),
                '%cp%' => $city->getCp(),
                '%charniere%' => $city->getCharniere()
            ) )
        );
    }

    private function t( $ref, $vars = null ) {
        return $this->get( 'translator' )->trans( $ref, $vars );
    }
}
