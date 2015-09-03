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
     * @Route(
     *     "/{slug}/{id}",
     *     requirements={
     *         "id"="^[0-9][0-9abAB][0-9]+"
     *     }
     * )
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
            ->setTitle('viteloge.estimation.statistic.index.title')
            ->addMeta('name', 'robots', 'index, follow')
            ->addMeta('name', 'description', 'viteloge.estimation.statistic.index.description')
            //->addMeta('property', 'og:title', "viteloge.frontend.ad.search.title")
            ->addMeta('property', 'og:type', 'website')
            ->addMeta('property', 'og:url',  $canonicalLink)
            //->addMeta('property', 'og:description', 'viteloge.frontend.ad.search.description')
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
     * @Route(
     *     "price/{slug}/{id}",
     *     requirements={
     *         "id"="\d+"
     *     },
     *     name="viteloge_estimation_statistic_price"
     * )
     * Cache(expires="tomorrow", public=true)
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
     * @Route(
     *     "history/{slug}/{id}",
     *     requirements={
     *         "id"="\d+"
     *     },
     *     name="viteloge_estimation_statistic_history"
     * )
     * Cache(expires="tomorrow", public=true)
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
     * @Route(
     *     "around/{slug}/{id}",
     *     requirements={
     *         "id"="\d+"
     *     },
     *     name="viteloge_estimation_statistic_around"
     * )
     * Cache(expires="tomorrow", public=true)
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
