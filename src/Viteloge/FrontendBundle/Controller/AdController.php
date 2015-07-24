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
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Serializer\Serializer;
    use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
    use Symfony\Component\Serializer\Encoder\JsonEncoder;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Acreat\InseeBundle\Entity\InseeDepartment;
    use Acreat\InseeBundle\Entity\InseeState;
    use Viteloge\CoreBundle\Entity\Ad;
    use Viteloge\CoreBundle\Entity\QueryStats;
    use Viteloge\CoreBundle\Entity\UserSearch;
    use Viteloge\CoreBundle\Component\DBAL\EnumTransactionType;
    use Viteloge\CoreBundle\SearchEntity\Ad as AdSearch;

    /**
     * Note: This should be the search ad controller
     * @Route("/ad")
     */
    class AdController extends Controller {

        /**
         * @Route(
         *     "/search/{page}/{limit}",
         *     requirements={
         *         "page"="\d+",
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "page"=1,
         *         "limit"="25"
         *     },
         *     name="viteloge_frontend_ad_search"
         * )
         * @Route(
         *     "/search/",
         *     requirements={
         *         "page"="\d+",
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "page"=1,
         *         "limit"="25"
         *     },
         *     name="viteloge_frontend_ad_search_default"
         * )
         * Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:search.html.twig")
         */
        public function searchAction(Request $request, $page, $limit) {
            $trans = $this->get('translator');

            // SEO
            $canonicalLink = $this->get('router')->generate(
                $request->get('_route'),
                $request->get('_route_params'),
                true
            );
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle('viteloge.frontend.ad.search.title')
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'description', 'viteloge.frontend.ad.search.description')
                //->addMeta('property', 'og:title', "viteloge.frontend.ad.search.title")
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                //->addMeta('property', 'og:description', 'viteloge.frontend.ad.search.description')
                ->setLinkCanonical($canonicalLink)
            ;
            // --

            // Form
            $adSearch = new AdSearch();
            $adSearch->handleRequest($request);
            $form = $this->createForm('viteloge_core_adsearch', $adSearch);
            // --

            // Save session
            $session = $request->getSession();
            $session->set('adSearch', $adSearch);
            // --

            // First State
            $inseeState = null;
            $whereState = $adSearch->getWhereState();
            if (!empty($whereState)) {
                $stateId = current($whereState);
                $stateRepository = $this->getDoctrine()->getRepository('AcreatInseeBundle:InseeState');
                $inseeState = $stateRepository->find((int)$stateId);
            }
            // --

            // First Department
            $inseeDepartment = null;
            $whereDepartment = $adSearch->getWhereDepartment();
            if (!empty($whereDepartment)) {
                $departmentId = current($whereDepartment);
                $departmentRepository = $this->getDoctrine()->getRepository('AcreatInseeBundle:InseeDepartment');
                $inseeDepartment = $departmentRepository->find((int)$departmentId);
            }
            // --

            // First city
            $inseeCity = null;
            $where = $adSearch->getWhere();
            if (!empty($where)) {
                $cityId = current($where);
                $cityRepository = $this->getDoctrine()->getRepository('AcreatInseeBundle:InseeCity');
                $inseeCity = $cityRepository->find((int)$cityId);
            }
            // --

            // Breadcrumbs
            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem(
                'Home', $this->get('router')->generate('viteloge_frontend_homepage')
            );
            if ($inseeState instanceof InseeState) {
                $breadcrumbTitle  = (!empty($adSearch->getTransaction())) ? $trans->trans('ad.transaction.'.strtoupper($adSearch->getTransaction())).' ' : '';
                $breadcrumbTitle .= $inseeState->getFullname();
                $breadcrumbs->addItem(
                    $breadcrumbTitle,
                    $this->get('router')->generate('viteloge_frontend_ad_search',
                        array(
                            'transaction' => $adSearch->getTransaction(),
                            'whereState' => array($inseeState->getId())
                        )
                    )
                );
            }
            if ($inseeDepartment instanceof InseeDepartment) {
                $breadcrumbTitle  = (!empty($adSearch->getTransaction())) ? $trans->trans('ad.transaction.'.strtoupper($adSearch->getTransaction())).' ' : '';
                $breadcrumbTitle .= $inseeDepartment->getFullname();
                $breadcrumbs->addItem(
                    $breadcrumbTitle,
                    $this->get('router')->generate('viteloge_frontend_ad_search',
                        array(
                            'transaction' => $adSearch->getTransaction(),
                            'whereDepartment' => array($inseeDepartment->getId())
                        )
                    )
                );
            }
            if ($inseeCity instanceof InseeCity) {
                $breadcrumbTitle  = (!empty($adSearch->getTransaction())) ? $trans->trans('ad.transaction.'.strtoupper($adSearch->getTransaction())).' ' : '';
                $breadcrumbTitle .= $inseeCity->getFullname().' ('.$inseeCity->getInseeDepartment()->getId().')';
                $breadcrumbs->addItem(
                    $breadcrumbTitle,
                    $this->get('router')->generate('viteloge_frontend_ad_search',
                        array(
                            'transaction' => $adSearch->getTransaction(),
                            'where' => array($inseeCity->getId())
                        )
                    )
                );
            }
            $qs = $request->get('qs');
            if (empty($qs)) { // if there is no query stats in url
                $breadcrumbTitle  = (!empty($adSearch->getTransaction())) ? $trans->trans('ad.transaction.'.strtoupper($adSearch->getTransaction())).' ' : $trans->trans('ad.research');
                $breadcrumbTitle  .= (!empty($adSearch->getWhat())) ? implode(', ', $adSearch->getWhat()).' ' : '';
                $breadcrumbTitle  .= ($inseeCity instanceof InseeCity) ? $inseeCity->getFullname() : '';
                $breadcrumbs->addItem($breadcrumbTitle);
            }
            // --

            // QueryStats SEO optimiation
            if (!empty($request->get('qs'))) {
                $qsId = (int)$request->get('qs');
                $qsRepository = $this->getDoctrine()->getRepository('VitelogeCoreBundle:QueryStats');
                $qs = $qsRepository->find((int)$qsId);
                $breadcrumbTitle  = $qs->getKeywords();
                $breadcrumbs->addItem($breadcrumbTitle);
            }
            // --

            // elastica
            $elasticaManager = $this->container->get('fos_elastica.manager');
            $repository = $elasticaManager->getRepository('VitelogeCoreBundle:Ad');
            $repository->setEntityManager($this->getDoctrine()->getManager());
            $pagination = $repository->searchPaginated($form->getData());
            // --

            // pager
            $pagination->setMaxPerPage($limit);
            $pagination->setCurrentPage($page);
            // --

            return array(
                'form' => $form->createView(),
                'ads' => $pagination->getCurrentPageResults(),
                'pagination' => $pagination
            );
        }

        /**
         * @Route(
         *     "/search/from/form/",
         *     name="viteloge_frontend_ad_searchfromform"
         * )
         * Cache(expires="tomorrow", public=true)
         * @Method({"POST"})
         * @Template("VitelogeFrontendBundle:Ad:search_from_form.html.twig")
         */
        public function searchFromForm(Request $request) {
            $adSearch = new AdSearch();
            $form = $this->createForm('viteloge_core_adsearch', $adSearch);
            $form->handleRequest($request);

            if ($form->isValid()) {
                // transform object to array in order to through it to url
                $encoders = array(new JsonEncoder());
                $normalizers = array(new GetSetMethodNormalizer());
                $serializer = new Serializer($normalizers, $encoders);
                $options = json_decode($serializer->serialize($form->getData(), 'json'), true);

                if ($request->isXmlHttpRequest()) {
                    $response = new JsonResponse();
                    return $response->setData(array(
                        'redirect' => $this->generateUrl('viteloge_frontend_ad_search', $options)
                    ));
                }

                return $this->redirectToRoute(
                    'viteloge_frontend_ad_search',
                    $options,
                    301
                );
            }

            return array(
                'adSearch' => $adSearch,
                'form' => $form->createView()
            );
        }

        /**
         * @Route(
         *     "/search/from/usersearch/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_ad_searchfromusersearch"
         * )
         * Cache(expires="tomorrow", public=true)
         * @ParamConverter("userSearch", class="VitelogeCoreBundle:UserSearch", options={"id" = "id"})
         * @Method({"GET"})
         */
        public function searchFromUserSearchAction(Request $request, UserSearch $userSearch) {
            $adSearch = new AdSearch();
            $adSearch->setTransaction($userSearch->getTransaction());
            $adSearch->setWhat($userSearch->getType());
            $adSearch->setRooms($userSearch->getRooms());
            $adSearch->setMinPrice($userSearch->getBudgetMin());
            $adSearch->setMaxPrice($userSearch->getBudgetMax());
            $adSearch->setRadius($userSearch->getRadius());
            $adSearch->setKeywords($userSearch->getKeywords());
            if ($userSearch->getInseeCity() instanceof InseeCity) {
                $adSearch->setWhere($userSearch->getInseeCity()->getId());
            }

            // transform object to array in order to through it to url
            $encoders = array(new JsonEncoder());
            $normalizers = array(new GetSetMethodNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            $options = json_decode($serializer->serialize($adSearch, 'json'), true);

            return $this->redirectToRoute(
                'viteloge_frontend_ad_search',
                $options,
                301
            );
        }

        /**
         * @Route(
         *     "/search/from/querystats/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_ad_searchfromquerystats"
         * )
         * Cache(expires="tomorrow", public=true)
         * @ParamConverter("queryStats", class="VitelogeCoreBundle:QueryStats", options={"id" = "id"})
         * @Method({"GET"})
         */
        public function searchFromQueryStats(Request $request, QueryStats $queryStats) {
            $em = $this->getDoctrine()->getManager();
            $queryStats->setCount($queryStats->getCount()+1);
            $em->persist($queryStats);
            $em->flush();

            $adSearch = new AdSearch();
            $adSearch->setTransaction($queryStats->getTransaction());
            $adSearch->setwhere($queryStats->getInseeCity()->getId());
            $adSearch->setwhat($queryStats->getType());
            $adSearch->setrooms($queryStats->getRooms());

            // transform object to array in order to through it to url
            $encoders = array(new JsonEncoder());
            $normalizers = array(new GetSetMethodNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            $options = json_decode($serializer->serialize($adSearch, 'json'), true);
            $options['qs'] = $queryStats->getId();

            return $this->redirectToRoute(
                'viteloge_frontend_ad_search',
                $options,
                301
            );
        }

        /**
         *
         */
        public function convertRequestToWebSearch(Request $request) {
            $userSearch = new UserSearch();
            $webSearch = new WebSearch();
            return $webSearch;
        }

        /**
         * @Route(
         *     "/create/websearch",
         *     name="viteloge_frontend_ad_createwebsearch"
         * )
         */
        public function createWebSearch(Request $request) {
            $adSearch = new AdSearch();
            $form = $this->createForm('viteloge_core_adsearch', $adSearch);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $webSearch = $this->convertRequestToWebSearch($request);
            }
            var_dump($form->getErrors());
            die('ddd');
        }

        /**
         * @Route(
         *     "/carousel/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "9"
         *     },
         *     name="viteloge_frontend_ad_carousel"
         * )
         * @Route(
         *     "/carousel/",
         *     requirements={
         *         "limit" = "\d+"
         *     },
         *     defaults={
         *         "limit" = "9"
         *     },
         *     name="viteloge_frontend_ad_carousel"
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:carousel.html.twig")
         */
        public function carouselAction(Request $request, $limit) {
            $adSearch = new AdSearch();
            $adSearch->handleRequest($request);

            $elasticaManager = $this->container->get('fos_elastica.manager');
            $repository = $elasticaManager->getRepository('VitelogeCoreBundle:Ad');
            $transaction = (!empty($adSearch->getTransaction())) ? $adSearch->getTransaction() : 'default';
            $ads = $repository->search($adSearch, $limit);

            return array(
                'transaction' => $adSearch->getTransaction(),
                'ads' => $ads
            );
        }

        /**
         * @Route(
         *     "/latest/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "9"
         *     },
         *     name="viteloge_frontend_ad_latest"
         * )
         * @Route(
         *     "/latest/",
         *     defaults={
         *         "limit" = "9"
         *     },
         *     name="viteloge_frontend_ad_latest"
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:latest.html.twig")
         */
        public function latestAction(Request $request, $limit) {
            $adSearch = new AdSearch();
            $adSearch->handleRequest($request);
            $elasticaManager = $this->container->get('fos_elastica.manager');
            $repository = $elasticaManager->getRepository('VitelogeCoreBundle:Ad');
            $ads = $repository->search($adSearch, $limit);
            return array(
                'transaction' => $adSearch->getTransaction(),
                'cityName' => $request->query->get('cityName'),
                'ads' => $ads
            );
        }

        /**
         * News suggestion
         *
         * @Route(
         *     "/suggest/new/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "3"
         *     },
         *     name="viteloge_frontend_ad_suggestnew"
         * )
         * @Route(
         *     "/suggest/new/",
         *     defaults={
         *         "limit" = "3"
         *     },
         *     name="viteloge_frontend_ad_suggestnew"
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:suggestNew.html.twig")
         */
        public function suggestNewAction(Request $request, $limit) {
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:Ad');
            $ads = $repository->findByAgencyIdNew(array('createdAt' => 'DESC'), $limit);
            return array(
                'ads' => $ads
            );
        }

        /**
         * Redirect to the hosted page
         *
         * @Route(
         *      "/redirect/{id}",
         *      requirements={
         *          "id"="\d+"
         *      }
         * )
         * @Method({"GET"})
         * @ParamConverter("ad", class="VitelogeCoreBundle:Ad", options={"id" = "id"})
         * @Template("VitelogeFrontendBundle:Ad:redirect.html.twig")
         */
        public function redirectAction(Request $request, Ad $ad) {
            return array(
                'ad' => $ad
            );
        }

    }

}
