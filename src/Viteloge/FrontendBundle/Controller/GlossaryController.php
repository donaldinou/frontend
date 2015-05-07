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
    use Viteloge\CoreBundle\Entity\UserSearch;

    /**
     * @Route("/glossary")
     */
    class GlossaryController extends Controller {

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
         *     "/{name}/{id}/{page}/{limit}",
         *     requirements={
         *         "id"="\d+",
         *         "page"="\d+",
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "page"=1,
         *         "limit"="25"
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
         * @Template("VitelogeFrontendBundle:Glossary:show.html.twig")
         */
        public function showCityAction(Request $request, InseeCity $inseeCity, $page, $limit) {
            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem(
                'Home', $this->get('router')->generate('viteloge_frontend_homepage')
            );
            $breadcrumbs->addItem(
                $inseeCity->getInseeState()->getName(),
                $this->get('router')->generate('viteloge_frontend_glossary_show',
                    array(
                        'name' => $inseeCity->getInseeState()->getName(),
                        'id' => $inseeCity->getId()
                    )
                )
            );
            $breadcrumbs->addItem(
                $inseeCity->getInseeDepartment()->getName(),
                $this->get('router')->generate('viteloge_frontend_glossary_show',
                    array(
                        'name' => $inseeCity->getInseeDepartment()->getName(),
                        'id' => $inseeCity->getId()
                    )
                )
            );
            $breadcrumbs->addItem('Immobilier '.$inseeCity->getName());

            $userSearch = new UserSearch();
            $form = $this->createForm('viteloge_frontend_usersearch', $userSearch);

            $paginator  = $this->get('knp_paginator');
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:Ad');
            $ads = $repository->findByInseeCity($inseeCity, null, $limit, $page);
            /*$pagination = $paginator->paginate(
                $query,
                $page,
                $limit
            );*/
            return array(
                'inseeCity' => $inseeCity,
                'page' => $page,
                'limit' => $limit,
                'ads' => $ads,
                'form' => $form->createView()
            );
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
         *     "/areas/{id}/",
         *     requirements={
         *         "id"="\d+"
         *     }
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
