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
         *     "/mostSearched/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "5"
         *     }
         * )
         * @Route(
         *     "/mostSearched/",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "5"
         *     }
         * )
         * @Cache(expires="tomorrow", public=true)
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
         * @Route(
         *     "/state/{name}/{id}",
         *     requirements={
         *         "id"="\d+"
         *     }
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
            $options = array(
                'whereState' => array( $inseeState->getId() )
            );
            return $this->redirectToRoute('viteloge_frontend_ad_search', $options, 301);
        }

        /**
         * @Route(
         *     "/department/{name}/{id}",
         *     requirements={
         *         "id"="\d+"
         *     }
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
            $options = array(
                'whereDepartment' => array( $inseeDepartment->getId() )
            );
            return $this->redirectToRoute('viteloge_frontend_ad_search', $options, 301);
        }

        /**
         * @Route(
         *     "/city/{name}/{id}",
         *     requirements={
         *         "id"="\d+"
         *     }
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
         * @Cache(expires="tomorrow", public=true)
         * @Template("VitelogeFrontendBundle:Glossary:showCity.html.twig")
         */
        public function showCityAction(Request $request, InseeCity $inseeCity) {
            $translated = $this->get('translator');

            // Breadcrumbs
            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem(
                'Home', $this->get('router')->generate('viteloge_frontend_homepage')
            );
            $breadcrumbs->addItem(
                $inseeCity->getInseeState()->getFullname(),
                $this->get('router')->generate('viteloge_frontend_glossary_showstate',
                    array(
                        'name' => $inseeCity->getInseeState()->getName(),
                        'id' => $inseeCity->getInseeState()->getId()
                    )
                )
            );
            $breadcrumbs->addItem(
                $inseeCity->getInseeDepartment()->getFullname(),
                $this->get('router')->generate('viteloge_frontend_glossary_showdepartment',
                    array(
                        'name' => $inseeCity->getInseeDepartment()->getName(),
                        'id' => $inseeCity->getInseeDepartment()->getId()
                    )
                )
            );
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
                    'name' => $inseeCity->getName(),
                    'id' => $inseeCity->getId()
                ),
                true
            );
            $cityTitle = $inseeCity->getFullname().' ('.$inseeCity->getInseeDepartment()->getId().')';
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($cityTitle.' - '.$translated->trans('viteloge.frontend.glossary.showcity.title'))
                ->addMeta('name', 'description', $cityTitle.' - '.$translated->trans('viteloge.frontend.glossary.showcity.description'))
                ->addMeta('property', 'og:title', $cityTitle.' - '.$translated->trans('viteloge.frontend.glossary.showcity.title'))
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:description', $cityTitle.' - '.$translated->trans('viteloge.frontend.glossary.showcity.description'))
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->setLinkCanonical($canonicalLink)
            ;
            // --

            return array(
                'city' => $inseeCity,
                'mapOptions' => $mapOptions,
                'form' => $this->initForm()->form->createView()
            );
        }

        /**
         * @Route(
         *     "/area/{name}/{id}",
         *     requirements={
         *         "id"="\d+"
         *     }
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
         * @Route(
         *     "/departments/{id}/",
         *     requirements={
         *         "id"="\d+"
         *     }
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
         * @Route(
         *     "/cities/{id}/",
         *     requirements={
         *         "id"="\d+"
         *     }
         * )
         * @Method({"GET"})
         * @ParamConverter("inseeDepartment", class="AcreatInseeBundle:InseeDepartment", options={"id" = "id"})
         * @Cache(expires="tomorrow", public=true)
         * @Template("VitelogeFrontendBundle:Glossary:citites.html.twig")
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
         * @Route(
         *      "/areas/{id}/",
         *      requirements={
         *          "id"="\d+"
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
