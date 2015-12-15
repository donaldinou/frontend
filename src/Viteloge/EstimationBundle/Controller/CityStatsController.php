<?php

namespace Viteloge\EstimationBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Acreat\InseeBundle\Entity\InseeCity;
use Viteloge\CoreBundle\Entity\Estimate;

/**
 * @Route("prix-immobilier/")
 */
class CityStatsController extends Controller
{
    /**
     * Display price page for a city
     * Private cache
     *
     * @Route(
     *     "/{name}/{id}",
     *     requirements={
     *         "id"="(?:2[a|b|A|B])?0{0,2}\d+"
     *     },
     *     name="viteloge_estimation_statistic_city"
     * )
     * @Cache(expires="tomorrow", public=false)
     * @Method({"GET"})
     * @ParamConverter("inseeCity", class="AcreatInseeBundle:InseeCity", options={"id" = "id"})
     * @Template()
     */
    public function cityAction( Request $request, InseeCity $inseeCity ) {
        $translated = $this->get('translator');

        // SEO
        $canonicalLink = $this->get('router')->generate(
            $request->get('_route'),
            $request->get('_route_params'),
            true
        );
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($translated->trans('viteloge.estimation.statistic.index.title', array('%city%' => $inseeCity->getFullname())))
            ->addMeta('name', 'robots', 'index, follow')
            ->addMeta('name', 'description', $translated->trans('viteloge.estimation.statistic.index.description', array('%city%' => $inseeCity->getFullname())))
            ->addMeta('property', 'og:title', $translated->trans('viteloge.frontend.ad.search.title', array('%city%' => $inseeCity->getFullname())))
            ->addMeta('property', 'og:type', 'website')
            ->addMeta('property', 'og:url',  $canonicalLink)
            ->addMeta('property', 'og:description', $translated->trans('viteloge.frontend.ad.search.description', array('%city%' => $inseeCity->getFullname())))
            ->addMeta('property', 'geo.region', 'FR')
            ->addMeta('property', 'geo.placename', $inseeCity->getFullname())
            ->addMeta('property', 'geo.position', $inseeCity->getLat().';'.$inseeCity->getLng())
            ->addMeta('property', 'ICMB', $inseeCity->getLat().','.$inseeCity->getLng())
            ->setLinkCanonical($canonicalLink)
        ;
        // --

        // Breadcrumbs
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addItem(
            $translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
            $this->get('router')->generate('viteloge_frontend_homepage')
        );
        if ($inseeCity->getInseeState()){
            $breadcrumbs->addItem(
                $inseeCity->getInseeState()->getFullname(),
                $this->get('router')->generate('viteloge_frontend_glossary_showstate',
                    array(
                        'name' => $inseeCity->getInseeState()->getSlug(),
                        'id' => $inseeCity->getInseeState()->getId()
                    )
                )
            );
        }
        if ($inseeCity->getInseeDepartment()) {
            $breadcrumbs->addItem(
                $inseeCity->getInseeDepartment()->getFullname(),
                $this->get('router')->generate('viteloge_frontend_glossary_showdepartment',
                    array(
                        'name' => $inseeCity->getInseeDepartment()->getSlug(),
                        'id' => $inseeCity->getInseeDepartment()->getId()
                    )
                )
            );
        }
        $breadcrumbs->addItem(
            $inseeCity->getFullname(),
            $this->get('router')->generate('viteloge_frontend_glossary_showcity',
                array(
                    'name' => $inseeCity->getSlug(),
                    'id' => $inseeCity->getId()
                )
            )
        );
        $breadcrumbs->addItem(
            $translated->trans('breadcrumb.statistic.city', array('%city%' => $inseeCity->getName()), 'breadcrumbs')
        );
        // --

        $om = $this->getDoctrine()->getManager();
        $baro_repo = $om->getRepository( 'VitelogeCoreBundle:Barometer' );
        $dist_repo = $om->getRepository( 'VitelogeCoreBundle:Distance' );

        $barometers = $baro_repo->findSortedSalesFor( $inseeCity );

        $estimate = new Estimate();
        $estimate->setInseeCity( $inseeCity );

        $form = $this->createForm( 'intro_estimation', $estimate, array(
            'action' => $this->generateUrl( 'viteloge_estimation_default_index', array( 'intro' => 1 ) )
        ) );

        // Google map api
        $mapOptions = new \StdClass();
        $mapOptions->zoom = 12;
        $mapOptions->lat = $inseeCity->getLat();
        $mapOptions->lng = $inseeCity->getLng();
        $mapOptions->disableDefaultUI = false;
        $mapOptions->scrollwheel = false;
        // --

        return array(
            'city' => $inseeCity,
            'mapOptions' => $mapOptions,
            'form' => $form->createView(),
            'barometres' => $barometers,
            'surrounding_cities' => $dist_repo->findNeighbors( $inseeCity, 30, 8 ),
            'main_title' => $translated->trans( 'city_eval.main_title', array(
                '%name%' => $inseeCity->getName( true ),
                '%cp%' => $inseeCity->getPostalCode(),
                '%charniere%' => $translated->trans('insee.city.prefix.'.$inseeCity->getPrefixId())
            ) ),
            'LAYOUT_TITLE' => $translated->trans( 'city_eval.title', array(
                '%name%' => $inseeCity->getName( true ),
                '%cp%' => $inseeCity->getPostalCode(),
                '%charniere%' => $translated->trans('insee.city.prefix.'.$inseeCity->getPrefixId())
            ) )
        );
    }

    /**
     * Find price for a city
     * Ajax call so cache could be public
     *
     * @Route(
     *     "price/{name}/{id}",
     *     requirements={
     *         "id"="(?:2[a|b|A|B])?0{0,2}\d+"
     *     },
     *     name="viteloge_estimation_statistic_price"
     * )
     * @Cache(expires="tomorrow", public=true)
     * @Method({"GET"})
     * @ParamConverter("inseeCity", class="AcreatInseeBundle:InseeCity", options={"id" = "id"})
     * @Template()
     */
    public function priceAction(Request $request, InseeCity $inseeCity) {
        $baro_repo = $this->getDoctrine()->getRepository( 'VitelogeCoreBundle:Barometer' );
        $barometers = $baro_repo->findSortedSalesFor( $inseeCity );

        return array(
            'city' => $inseeCity,
            'barometers' => $barometers
        );
    }

    /**
     * Find price history for a city. Currently it is equal to priceAction method
     * Ajax call so cache could be public
     *
     * @Route(
     *     "history/{name}/{id}",
     *     requirements={
     *         "id"="(?:2[a|b|A|B])?0{0,2}\d+"
     *     },
     *     name="viteloge_estimation_statistic_history"
     * )
     * @Cache(expires="tomorrow", public=true)
     * @Method({"GET"})
     * @ParamConverter("inseeCity", class="AcreatInseeBundle:InseeCity", options={"id" = "id"})
     * @Template()
     */
    public function historyAction(Request $request, InseeCity $inseeCity) {
        $baro_repo = $this->getDoctrine()->getRepository( 'VitelogeCoreBundle:Barometer' );
        $barometers = $baro_repo->findSortedSalesFor( $inseeCity );

        return array(
            'city' => $inseeCity,
            'barometers' => $barometers
        );
    }

    /**
     * Legacy. See nearAction from SuggestController
     * Ajax call so cache could be public
     *
     * @Route(
     *     "around/{name}/{id}",
     *     requirements={
     *         "id"="\d+"
     *     },
     *     name="viteloge_estimation_statistic_around"
     * )
     * @Cache(expires="tomorrow", public=true)
     * @Method({"GET"})
     * @ParamConverter("inseeCity", class="AcreatInseeBundle:InseeCity", options={"id" = "id"})
     * @Template()
     */
    public function aroundAction(Request $request, InseeCity $inseeCity) {
        $dist_repo = $this->getDoctrine()->getRepository( 'VitelogeCoreBundle:Distance' );
        $cities = $dist_repo->findNeighbors( $inseeCity, 30, 8 );

        return array(
            'cities' => $cities
        );
    }

}
