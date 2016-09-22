<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Acreat\InseeBundle\Entity\InseeState;
    use Acreat\InseeBundle\Entity\InseeDepartment;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Acreat\InseeBundle\Entity\InseeArea;
    use Viteloge\CoreBundle\SearchEntity\Ad as AdSearch;
    use Viteloge\FrontendBundle\Entity\CityData;

    /**
     * @Route("/glossary")
     */
    class GlossaryController extends Controller {

        /**
         *
         */
        protected $form;

        /**
         *
         */
        protected function initForm() {
            $adSearch = new AdSearch();
            $this->form = $this->createForm('viteloge_core_adsearch', $adSearch);
            return $this;
        }

        /**
         * @Route(
         *      "/",
         *      defaults={},
         *      name="viteloge_frontend_glossary",
         *      options = {
         *          "i18n" = true,
         *          "vl_nositemap" = {
         *              "title" = "viteloge.frontend.glossary.index.title",
         *              "description" = "viteloge.frontend.glossary.index.description",
         *              "changefreq" = "hourly",
         *              "priority" = "1.0"
         *          }
         *      }
         * )
         * @Method({"GET"})
         * @Template()
         */
        public function indexAction(Request $request) {
            return array(

            );
        }

        /**
         * Legacy function used because there is no slug saved in table
         * There is no cache
         *
         * @Route(
         *      "/{slug}",
         *      defaults={},
         *      name="viteloge_frontend_glossary_legacy"
         * )
         * @Method({"GET"})
         * @Template()
         */
        public function legacyAction(Request $request, $slug) {
            $queries = array_merge(
                $request->query->all(),
                $request->request->all()
            );

            if ($slug == 'france') {
                return $this->redirectToRoute(
                    'viteloge_frontend_ad_search',
                    $queries,
                    301
                );
            }

            $repository = $this->getDoctrine()
                ->getRepository('AcreatInseeBundle:InseeState');
            $inseeEntity = $repository->findOneBySoundex($slug);
            if ($inseeEntity instanceof InseeState) {
                $options = array_merge(
                    array(
                        'name' => $inseeEntity->getSlug(),
                        'id' => $inseeEntity->getId()
                    ),
                    $queries
                );
                return $this->redirectToRoute(
                    'viteloge_frontend_glossary_showstate',
                    $options,
                    301
                );
            }

            $repository = $this->getDoctrine()
                ->getRepository('AcreatInseeBundle:InseeDepartment');
            $inseeEntity = $repository->findOneBySoundex($slug);
            if ($inseeEntity instanceof InseeDepartment) {
                $options = array_merge(
                    array(
                        'name' => $inseeEntity->getSlug(),
                        'id' => $inseeEntity->getId()
                    ),
                    $queries
                );
                return $this->redirectToRoute(
                    'viteloge_frontend_glossary_showdepartment',
                    $options,
                    301
                );
            }

            throw $this->createNotFoundException();
        }

        /**
         * Display the most searched cities
         * Ajax call so we could have a shared public cache
         *
         * @Route(
         *     "/mostSearched/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit"="5"
         *     },
         *     name="viteloge_frontend_glossary_mostsearched_limited"
         * )
         * @Route(
         *     "/mostSearched/",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "5"
         *     },
         *     name="viteloge_frontend_glossary_mostsearched"
         * )
         * @Cache(smaxage="604800", maxage="604800", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Glossary:mostSearched.html.twig")
         */
        public function mostSearchedAction(Request $request, $limit=5, array $criteria=array()) {
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:UserSearch');
            $glossary = $repository->findAllInseeCityOrderedByCount();
            return $this->render(
                'VitelogeFrontendBundle:Glossary:mostSearched.html.twig',
                array(
                    'glossary' => $glossary
                )
            );
        }

        /**
         * Research from state
         * Expire tomorrow
         *
         * @Route(
         *     "/state/{name}/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_glossary_showstate"
         * )
         * @Method({"GET", "POST"})
         * @ParamConverter(
         *     "inseeState",
         *     class="AcreatInseeBundle:InseeState",
         *     options={
         *         "id" = "id",
         *         "name" = "name",
         *         "exclude": {
         *             "name"
         *         }
         *     }
         * )
         * @Cache(expires="tomorrow", public=true)
         */
        public function showStateAction(Request $request, InseeState $inseeState) {
            /*$repository = $this->getDoctrine()
                ->getRepository('AcreatInseeBundle:InseeCity');
            $cities = $repository->findByInseeState($inseeState);
            $ids = array_map(function($city) {
                return $city->getId();
            }, $cities);*/

            $queries = array_merge(
                $request->query->all(),
                $request->request->all()
            );
            $options = array_merge(
                array(
                    'whereState' => array( $inseeState->getId() )
                ),
                $queries
            );

            return $this->redirectToRoute('viteloge_frontend_ad_search', $options, 301);
        }

        /**
         * Search from department
         * Expire tomorrow
         *
         * @Route(
         *     "/department/{name}/{id}",
         *     requirements={
         *         "id"="(?:2[a|b|A|B])|\d+"
         *     },
         *     name="viteloge_frontend_glossary_showdepartment"
         * )
         * @Method({"GET", "POST"})
         * @ParamConverter(
         *     "inseeDepartment",
         *     class="AcreatInseeBundle:InseeDepartment",
         *     options={
         *         "id" = "id",
         *         "name" = "name",
         *         "exclude": {
         *             "name"
         *         }
         *     }
         * )
         * @Cache(expires="tomorrow", public=true)
         */
        public function showDepartmentAction(Request $request, InseeDepartment $inseeDepartment) {
            /*$repository = $this->getDoctrine()
                ->getRepository('AcreatInseeBundle:InseeCity');
            $cities = $repository->findByInseeDepartment($inseeDepartment);
            $ids = array_map(function($city) {
                return $city->getId();
            }, $cities);*/

            $queries = array_merge(
                $request->query->all(),
                $request->request->all()
            );
            $options = array_merge(
                array(
                    'whereDepartment' => array( $inseeDepartment->getId() )
                ),
                $queries
            );

            return $this->redirectToRoute('viteloge_frontend_ad_search', $options, 301);
        }

        /**
         * Display city page information
         * Private cache
         *
         * @Route(
         *     "/city/{name}/{id}",
         *     requirements={
         *         "id"="(?:2[a|b|A|B])?0{0,2}\d+"
         *     },
         *     name="viteloge_frontend_glossary_showcity"
         * )
         * @Method({"GET", "POST"})
         * @ParamConverter(
         *     "inseeCity",
         *     class="AcreatInseeBundle:InseeCity",
         *     options={
         *         "id" = "id",
         *         "name" = "name",
         *         "exclude": {
         *             "name"
         *         }
         *     }
         * )
         * @Cache(expires="tomorrow", public=false)
         * @Template("VitelogeFrontendBundle:Glossary:showCity.html.twig")
         */
        public function showCityAction(Request $request, InseeCity $inseeCity) {
            $translated = $this->get('translator');

            // Load city data
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:CityData');
            $cityData = $repository->findOneByInseeCity($inseeCity);
            // --

            //
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:Ad');
            $count = $repository->countByFiltered(array('inseeCity' => $inseeCity));
            // --

            // Breadcrumbs
            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem(
                'Home', $this->get('router')->generate('viteloge_frontend_homepage')
            );
            if ($inseeCity->getInseeState()) {
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
            $breadcrumbs->addItem('Immobilier '.$inseeCity->getFullname());
            // --

            // Google map api
            $mapOptions = new \StdClass();
            $mapOptions->zoom = 12;
            $mapOptions->lat = $inseeCity->getLat();
            $mapOptions->lng = $inseeCity->getLng();
            $mapOptions->disableDefaultUI = true;
            $mapOptions->scrollwheel = false;
            // --

            // SEO
            $canonicalLink = $this->get('router')->generate(
                $request->get('_route'),
                array(
                    'name' => $inseeCity->getSlug(),
                    'id' => $inseeCity->getId()
                ),
                true
            );
            $cityTitle = $inseeCity->getFullname().' ('.$inseeCity->getInseeDepartment()->getId().')';
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.frontend.glossary.showcity.title.city', array('%city%' => $cityTitle)))
                ->addMeta('name', 'description', $translated->trans('viteloge.frontend.glossary.showcity.description.city', array('%city%' => $cityTitle)))
                ->addMeta('property', 'og:title', $translated->trans('viteloge.frontend.glossary.showcity.title.city', array('%city%' => $cityTitle)))
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:description', $translated->trans('viteloge.frontend.glossary.showcity.description.city', array('%city%' => $cityTitle)))
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'geo.region', 'FR')
                ->addMeta('property', 'geo.placename', $inseeCity->getFullname())
                ->addMeta('property', 'geo.position', $inseeCity->getLat().';'.$inseeCity->getLng())
                ->addMeta('property', 'ICMB', $inseeCity->getLat().','.$inseeCity->getLng())
                ->setLinkCanonical($canonicalLink)
            ;
            // --
             // Form
            $adSearch = new AdSearch();
            $adSearch->handleRequest($request);
            $headerform = $this->createForm('viteloge_core_adsearch', $adSearch);
            return array(
                'city' => $inseeCity,
                'cityData' => $cityData,
                'count' => $count,
                'mapOptions' => $mapOptions,
                'form' => $this->initForm()->form->createView(),
                'headerform' => $headerform->createView(),

            );
        }

        /**
         * Search from area
         * Expire tomorrow
         *
         * @Route(
         *     "/area/{name}/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_glossary_showarea"
         * )
         * @Method({"GET", "POST"})
         * @ParamConverter(
         *     "inseeArea",
         *     class="AcreatInseeBundle:InseeArea",
         *     options={
         *         "id" = "id",
         *         "name" = "name",
         *         "exclude": {
         *             "name"
         *         }
         *     }
         * )
         * @Cache(expires="tomorrow", public=true)
         */
        public function showAreaAction(Request $request, InseeArea $inseeArea) {
            $options = array(
                'whereArea' => array( $inseeArea->getId() )
            );
            return $this->redirectToRoute('viteloge_frontend_ad_search', $options, 301);
        }

        /**
         * Currently unused
         *
         * @Route(
         *     "/departments/{id}/",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_glossary_department"
         * )
         * @Method({"GET"})
         * @ParamConverter("inseeState", class="AcreatInseeBundle:InseeState", options={"id" = "id"})
         * @Cache(expires="tomorrow", public=true)
         * @Template("VitelogeFrontendBundle:Glossary:departments.html.twig")
         */
        public function departmentsAction(Request $request, InseeState $inseeState) {
            $repository = $this->getDoctrine()
                ->getRepository('AcreatInseeBundle:InseeDepartment');
            $departments = $repository->findByInseeState($inseeState);
            return array(
                'departments' => $departments
            );
        }

        /**
         * Currently unused
         *
         * @Route(
         *     "/cities/{id}/",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_glossary_city"
         * )
         * @Method({"GET"})
         * @ParamConverter("inseeDepartment", class="AcreatInseeBundle:InseeDepartment", options={"id" = "id"})
         * @Cache(expires="tomorrow", public=true)
         * @Template("VitelogeFrontendBundle:Glossary:cities.html.twig")
         */
        public function citiesAction(Request $request, InseeDepartment $inseeDepartment) {
            $repository = $this->getDoctrine()
                ->getRepository('AcreatInseeBundle:InseeCity');
            $cities = $repository->findByInseeDepartment($inseeDepartment);
            return array(
                'cities' => $cities
            );
        }

        /**
         * Currently unused
         *
         * @Route(
         *      "/areas/{id}/",
         *      requirements={
         *          "id"="(?:2[a|b|A|B])?0{0,2}\d+"
         *      },
         *      name="viteloge_frontend_glossary_areas"
         * )
         * @Method({"GET"})
         * @ParamConverter("inseeCity", class="AcreatInseeBundle:InseeCity", options={"id" = "id"})
         * @Cache(expires="tomorrow", public=true)
         * @Template("VitelogeFrontendBundle:Glossary:areas.html.twig")
         */
        public function areasAction(Request $request, inseeCity $inseeCity) {
            $repository = $this->getDoctrine()
                ->getRepository('AcreatInseeBundle:InseeArea');
            $areas = $repository->findByInseeCity($inseeCity);
            return array(
                'areas' => $areas
            );
        }

    }

}
