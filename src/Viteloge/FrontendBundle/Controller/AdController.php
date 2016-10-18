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
    use Symfony\Component\HttpFoundation\Cookie;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Serializer\Serializer;
    use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
    use Symfony\Component\Serializer\Encoder\JsonEncoder;
    use Pagerfanta\Pagerfanta;
    use Pagerfanta\Adapter\ArrayAdapter;
    use Pagerfanta\Adapter\DoctrineORMAdapter;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Acreat\InseeBundle\Entity\InseeDepartment;
    use Acreat\InseeBundle\Entity\InseeState;
    use Viteloge\CoreBundle\Entity\Ad;
    use Viteloge\CoreBundle\Entity\QueryStats;
    use Viteloge\CoreBundle\Entity\Statistics;
    use Viteloge\CoreBundle\Entity\WebSearch;
    use Viteloge\CoreBundle\Entity\UserSearch;
    use Viteloge\CoreBundle\Component\DBAL\EnumTransactionType;
    use Viteloge\CoreBundle\Component\Enum\DistanceEnum;
    use Viteloge\CoreBundle\SearchEntity\Ad as AdSearch;

    /**
     * Note: This should be the search ad controller
     * @Route("/ad")
     */
    class AdController extends Controller {

        /**
         * Return the number of ads in database.
         * Usefull for pro and part website
         *
         * @Route(
         *      "/count",
         *      defaults={
         *          "_format"="txt"
         *      },
         *      name="viteloge_frontend_ad_count_format",
         *      options = {
         *          "i18n" = true
         *      }
         * )
         * @Route(
         *      "/count.{_format}",
         *      requirements={
         *          "_format"="txt"
         *      },
         *      defaults={
         *          "_format"="txt"
         *      },
         *      name="viteloge_frontend_ad_count_format",
         *      options = {
         *          "i18n" = true
         *      }
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template()
         */
        public function countAction(Request $request, $_format) {
            // This count is pretty faster than an elastic search count
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:Ad');
            $count = $repository->countByFiltered();
            return array(
                'count' => $count
            );
        }

        /**
         * Display research result. No cache for this page
         *
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
         *     options = {
         *          "i18n" = true,
         *          "vl_sitemap" = {
         *              "title" = "viteloge.frontend.ad.search.title",
         *              "description" = "viteloge.frontend.ad.search.description",
         *              "priority" = "0.7"
         *          }
         *     },
         *     name="viteloge_frontend_ad_search_default"
         * )
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:search_response.html.twig")
         */
        public function searchAction(Request $request, $page, $limit) {
            $translated = $this->get('translator');
           $currentUrl = $request->getUri();
            // Form
            $adSearch = new AdSearch();
            $adSearch->handleRequest($request);
            $form = $this->createForm('viteloge_core_adsearch', $adSearch);
            // --

            // Save session
            $session = $request->getSession();
            $session->set('adSearch', $adSearch);
            $session->set('currentUrl', $currentUrl);
            $session->remove('request');
            $session->set('request', $request);

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

            // Improve search for specifics city
            if ($inseeCity instanceof InseeCity) {
                $radius = $adSearch->getRadius();
                $adSearch->setLocation($inseeCity->getLocation());
                if ($inseeCity->getGeolevel() == 'ARM' && empty($radius)) {
                    $adSearch->setRadius(DistanceEnum::FIVE);
                }
            }
            // --

            // Breadcrumbs
            $transaction = $adSearch->getTransaction();
            $description = 'Toutes les annonces immobilières de ';
            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem(
                $translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->get('router')->generate('viteloge_frontend_homepage')
            );
            if ($inseeState instanceof InseeState) {
                $breadcrumbTitle  = (!empty($transaction)) ? $translated->trans('ad.transaction.'.strtoupper($transaction)).' ' : '';
                $breadcrumbTitle .= $inseeState->getFullname();
                $breadcrumbs->addItem(
                    $breadcrumbTitle,
                    $this->get('router')->generate('viteloge_frontend_ad_search',
                        array(
                            'transaction' => $transaction,
                            'whereState' => array($inseeState->getId())
                        )
                    )
                );
            }
            if ($inseeDepartment instanceof InseeDepartment) {
                $breadcrumbTitle  = (!empty($transaction)) ? $translated->trans('ad.transaction.'.strtoupper($transaction)).' ' : '';
                $breadcrumbTitle .= $inseeDepartment->getFullname();
                $breadcrumbs->addItem(
                    $breadcrumbTitle,
                    $this->get('router')->generate('viteloge_frontend_ad_search',
                        array(
                            'transaction' => $transaction,
                            'whereDepartment' => array($inseeDepartment->getId())
                        )
                    )
                );
            }
            if ($inseeCity instanceof InseeCity) {
                $breadcrumbTitle  = (!empty($transaction)) ? $translated->trans('ad.transaction.'.strtoupper($transaction)).' ' : '';
                $breadcrumbTitle .= $inseeCity->getFullname().' ('.$inseeCity->getInseeDepartment()->getId().')';
                $breadcrumbs->addItem(
                    $breadcrumbTitle,
                    $this->get('router')->generate('viteloge_frontend_ad_search',
                        array(
                            'transaction' => $transaction,
                            'where' => array($inseeCity->getId())
                        )
                    )
                );
            }

            // No QueryStats SEO Optimization
            $qsId = $request->get('qs');
            if (empty($qsId)) {
                $what = $adSearch->getWhat();
                $breadcrumbTitleSuffix = '';
                $breadcrumbTitleSuffix .= (!empty($what)) ? implode(', ', $what).' ' : ' ';
                $suffix = '';
                $suffix .= (!empty($what)) ? implode(' et ', $what).' ' : ' ';
                $title = '';
                $titre ='';
                if($transaction == 'V'){
                   $title .= ' ventes ';
                   $titre .= ' a vendre ';
               }elseif($transaction == 'L'){
                   $title .= ' locations ';
                   $titre .= ' a louer ';
               }elseif($transaction == 'N'){
                   $title .= ' programmes neufs ';
                   $titre .= ' neufs ';
               }
                $description .= $title.$suffix;
                $description .= ($inseeCity instanceof InseeCity) ? $inseeCity->getFullname() : '';
                $description .= '. Retrouvez';
                if($suffix == 'Maison'){
                    $description .= ' toutes nos '.$suffix;
                }else{
                     $description .= ' tous nos '.$suffix;
                }
                $description .= $titre.' a ';
                $description .= ($inseeCity instanceof InseeCity) ? $inseeCity->getFullname() : '';
                $breadcrumbTitleSuffix .= ($inseeCity instanceof InseeCity) ? $inseeCity->getFullname() : '';
                $breadcrumbTitle  = (!empty($transaction)) ? $translated->trans('ad.transaction.'.strtoupper($transaction)).' ' : $translated->trans('ad.research').': ';
                $breadcrumbTitle .= (!empty(trim($breadcrumbTitleSuffix))) ? $breadcrumbTitleSuffix : $translated->trans('viteloge.result');

                $breadcrumbs->addItem($breadcrumbTitle);
            }
            // --


            // QueryStats SEO optimiation
            if (!empty($qsId)) {
                $qsRepository = $this->getDoctrine()->getRepository('VitelogeCoreBundle:QueryStats');
                $qs = $qsRepository->find((int)$qsId);
                $breadcrumbTitle = $qs->getKeywords();
                $description .= $breadcrumbTitle;
                $breadcrumbs->addItem($breadcrumbTitle);
            }
            // --
       /*     $description = 'Toutes les annonces immobilières de ';
            $description .=(!empty($transaction)) ? strtolower($translated->trans('ad.transaction.'.strtoupper($transaction))).' ' : strtolower($translated->trans('ad.research')).': ';
            if(isset($breadcrumbTitleSuffix)){
               $description .= $breadcrumbTitleSuffix;
            }
            $description .= '.Retrouvez ';
            var_dump($what);
            die();


            $description .= (!empty($transaction)) ? strtolower($translated->trans('ad.transaction.'.strtoupper($transaction))).' ' : strtolower($translated->trans('ad.research')).': ';
            if(isset($breadcrumbTitleSuffix)){
               $description .= $breadcrumbTitleSuffix;
            }
           var_dump($description);*/

            // elastica
            $elasticaManager = $this->container->get('fos_elastica.manager');
            $repository = $elasticaManager->getRepository('VitelogeCoreBundle:Ad');
            $repository->setEntityManager($this->getDoctrine()->getManager());
            $pagination = $repository->searchPaginated($form->getData());
            // --

            // pager
            $pagination->setMaxPerPage($limit);
            $pagination->setCurrentPage($page);
            //$pagination->setEndPage();
            // --

            // SEO
            $canonicalLink = $this->get('router')->generate(
                $request->get('_route'),
                $request->get('_route_params'),
                true
            );
            $seoPage = $this->container->get('sonata.seo.page');

            $seoPage
                ->setTitle($breadcrumbTitle.' - '.$translated->trans('viteloge.frontend.ad.search.title'))
                ->addMeta('name', 'robots', 'noindex, follow')
                ->addMeta('name', 'description', $description)
                ->addMeta('property', 'og:title', $seoPage->getTitle())
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $breadcrumbTitle.' - '.$translated->trans('viteloge.frontend.ad.search.description'))
                ->setLinkCanonical($canonicalLink)
            ;
            // --
            $session->set('resultAd',$pagination->getCurrentPageResults());
            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();

            return array(
                'form' => $form->createView(),
                'ads' => $pagination->getCurrentPageResults(),
                'pagination' => $pagination,
                'csrf_token' => $csrfToken,
            );
        }

        /**
         * Legacy, search others ads from a particular ad
         * There are no header information so we could set a good cache
         *
         * @Route(
         *      "/search/from/ad/{id}",
         *      requirements={
         *          "id"="\d+",
         *      },
         *      name="viteloge_frontend_ad_count",
         *      options = {
         *          "i18n" = true
         *      }
         * )
         * @Cache(lastModified="ad.getUpdatedAt()", ETag="'Ad' ~ ad.getId() ~ ad.getUpdatedAt().getTimestamp()")
         * @Method({"GET"})
         * @ParamConverter("ad", class="VitelogeCoreBundle:Ad", options={"id" = "id"})
         * @Template()
         */
        public function searchFromAdAction(Request $request, Ad $ad) {
            $adSearch = new AdSearch();
            $adSearch->setTransaction($ad->getTransaction());
            $adSearch->setWhat($ad->getType());
            $adSearch->setRooms($ad->getRooms());
            if ($ad->getInseeCity() instanceof InseeCity) {
                $adSearch->setWhere($ad->getInseeCity()->getId());
                $adSearch->setLocation($ad->getInseeCity()->getLocation());
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
         * Search form.
         * No cache
         *
         * @Route(
         *     "/search/from/form/",
         *     name="viteloge_frontend_ad_searchfromform"
         * )
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

            $isTransactionLabelHidden = (bool)$request->query->has('hideTransaction');
            $options = array(
                'adSearch' => $adSearch,
                'form' => $form->createView()
            );
            if ($request->query->has('hideTransaction')) {
                $options['isTransactionLabelHidden'] = true;
            }
            return $options;
        }

        /**
         * Search from a WebSearch
         * Cache is set from the created date.
         *
         * @Route(
         *     "/search/from/websearch/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_ad_searchfromwebsearch"
         * )
         * @Cache(lastModified="webSearch.getUpdatedAt()", ETag="'WebSearch' ~ webSearch.getId() ~ webSearch.getUpdatedAt().getTimestamp()")
         * @ParamConverter("webSearch", class="VitelogeCoreBundle:WebSearch", options={"id" = "id"})
         * @Method({"GET"})
         */
        public function searchFromWebSearchAction(Request $request, WebSearch $webSearch) {
            $userSearch = $webSearch->getUserSearch();
            return $this->searchFromUserSearchAction($request, $userSearch);
        }

        /**
         * Search from a UserSearch
         * Cache is set from the created date.
         *
         * @Route(
         *     "/search/from/usersearch/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_ad_searchfromusersearch"
         * )
         * @Cache(lastModified="userSearch.getCreatedAt()", ETag="'UserSearch' ~ userSearch.getId() ~ userSearch.getCreatedAt().getTimestamp()")
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
                $adSearch->setLocation($userSearch->getInseeCity()->getLocation());
            }
            $adSearch->setSort('createdAt');

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
         * Search from a query stats.
         * Cache is set from set last timestamp
         *
         * @Route(
         *     "/search/from/querystats/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_ad_searchfromquerystats"
         * )
         * @Cache(lastModified="queryStats.getUpdateAt()", ETag="'QueryStats' ~ queryStats.getId() ~ queryStats.getTimestamp()")
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
            $adSearch->setWhere($queryStats->getInseeCity()->getId());
            $adSearch->setWhat(ucfirst($queryStats->getType()));
            $adSearch->setRooms($queryStats->getRooms());
            $adSearch->setLocation($queryStats->getInseeCity()->getLocation());

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
         * Show a carousel ads.
         * Ajax call, so we can set a public cache
         *
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
         * @Route(
         *     "/carousel/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "9"
         *     },
         *     name="viteloge_frontend_ad_carousel_limited"
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:carousel.html.twig")
         */
        public function carouselAction(Request $request, $limit) {
            $adSearch = new AdSearch();
            $adSearch->handleRequest($request);

            $transaction = $adSearch->getTransaction();
            $elasticaManager = $this->container->get('fos_elastica.manager');
            $repository = $elasticaManager->getRepository('VitelogeCoreBundle:Ad');
            $transaction = (!empty($transaction)) ? $transaction : 'default';
            $ads = $repository->search($adSearch, $limit);

            return array(
                'transaction' => $transaction,
                'ads' => $ads
            );
        }

        /**
         * Ad favorie.
         *
         *
         * @Route(
         *     "/favourite/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_ad_favourite"
         * )
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:cookie.html.twig")
         */
        public function favorieAction(Request $request, Ad $ad) {
            if($request->isXmlHttpRequest()){
                $cookies = $request->cookies;
            if ($cookies->has('viteloge_favorie')){
                    $cookie_favorie = $cookies->get('viteloge_favorie').'#$#'.$ad->getId();
            }else{
                $cookie_favorie = $ad->getId();
            }
            $response = new Response();
            $response->headers->setCookie(new Cookie('viteloge_favorie', $cookie_favorie));
                return $this->render('VitelogeFrontendBundle:Ad:cookie.html.twig',array(), $response);

            }else{
             throw new \Exception("Erreur");
            }

        }

        /**
         * Show latest ads for a request
         * Ajax call so we can have a public cache
         *
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
         * Show latest ads (use in home)
         * Ajax call so we can have a public cache
         *
         * @Route(
         *     "/home/latest/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "5"
         *     },
         *     name="viteloge_frontend_ad_latest_limited"
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:latest_home.html.twig")
         */
        public function latesthomeAction(Request $request, $limit) {

            $adSearch = new AdSearch();

            $elasticaManager = $this->container->get('fos_elastica.manager');
            $repository = $elasticaManager->getRepository('VitelogeCoreBundle:Ad');
            $ads = $repository->search($adSearch, 24);
            // Save session
            $session = $request->getSession();
            $session->set('resultAd', $ads);
            $session->remove('request');
            return array(
                'ads' => $ads
            );
        }

        /**
         * Show latest ads in list page
         *
         * @Route(
         *     "/last/{page}/{limit}",
         *     requirements={
                   "page"="\d+",
         *         "limit"="\d+"
         *     },
         *     defaults={
                   "page" = "1",
         *         "limit" = "24"
         *     },
         *     name="viteloge_frontend_ad_latest_list"
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:search_response.html.twig")
         */
        public function latestListAction(Request $request,$page,$limit) {
            $translated = $this->get('translator');
            $adSearch = new AdSearch();
            $adSearch->handleRequest($request);
            $form = $this->createForm('viteloge_core_adsearch', $adSearch);
            // Breadcrumbs
            $transaction = $adSearch->getTransaction();
            $description = 'Les dernières annonces immobilières de viteloge';
            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem(
                $translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->get('router')->generate('viteloge_frontend_homepage')
            );
            $elasticaManager = $this->container->get('fos_elastica.manager');
            $repository = $elasticaManager->getRepository('VitelogeCoreBundle:Ad');
            $ads = $repository->search($adSearch,$limit);
            $pagination = $repository->searchPaginated($form->getData());


            // pager
            $pagination->setMaxPerPage($limit);
            $pagination->setCurrentPage($page);
            $seoPage = $this->container->get('sonata.seo.page');
            // SEO
            $canonicalLink = $this->get('router')->generate(
                $request->get('_route'),
                $request->get('_route_params'),
                true
            );
            $breadcrumbTitle  = 'Derniers biens';
            $breadcrumbs = $this->get('white_october_breadcrumbs');

            $breadcrumbs->addItem(
                    $breadcrumbTitle,
                    $this->get('router')->generate('viteloge_frontend_ad_latest_list'));

            $seoPage
                ->setTitle($breadcrumbTitle.' - '.$translated->trans('viteloge.frontend.ad.search.title'))
                ->addMeta('name', 'robots', 'noindex, follow')
                ->addMeta('name', 'description', $description)
                ->addMeta('property', 'og:title', $seoPage->getTitle())
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $breadcrumbTitle.' - '.$translated->trans('viteloge.frontend.ad.search.description'))
                ->setLinkCanonical($canonicalLink)
            ;
            // --

            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
            // Save session
            $session = $request->getSession();
            $session->set('resultAd',$pagination->getCurrentPageResults());
            $session->set('resultAd', $ads);
            $session->remove('request');

            return array(
                'form' => $form->createView(),
                'ads' => $pagination->getCurrentPageResults(),
                'pagination' => $pagination,
                'csrf_token' => $csrfToken,

            );
        }

        /**
         * News suggestion
         * Ajax call so we can have public cache
         *
         * @Route(
         *     "/suggest/new/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "3"
         *     },
         *     name="viteloge_frontend_ad_suggestnew_limited"
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
            $em = $this->getDoctrine()->getManager();
            $queryBuilder = $em->createQueryBuilder()
                ->select('ad')
                ->from('VitelogeCoreBundle:Ad', 'ad')
                ->where('ad.agencyId = :agencyId')
                ->setParameter('agencyId', Ad::AGENCY_ID_NEW)
                ->orderBy('ad.createdAt', 'DESC')
            ;

            $adapter = new DoctrineORMAdapter($queryBuilder, true, false);
            $pagination = new Pagerfanta($adapter);
            $pagination->setCurrentPage(1);
            $pagination->setMaxPerPage($limit);

            return array(
                'count' => $pagination->getNbResults(),
                'ads' => $pagination->getCurrentPageResults()
            );
        }

        /**
         * Redirect to the hosted page.
         * There are no header information so we could set a good cache
         *
         * @Route(
         *      "/redirect/{id}",
         *      requirements={
         *          "id"="\d+"
         *      }
         * )
         * @Cache(lastModified="ad.getUpdatedAt()", ETag="'Ad' ~ ad.getId() ~ ad.getUpdatedAt().getTimestamp()")
         * @Method({"GET"})
         * @ParamConverter("ad", class="VitelogeCoreBundle:Ad", options={"id" = "id"})
         * @Template("VitelogeFrontendBundle:Ad:redirect.html.twig")
         */
        public function redirectAction(Request $request, Ad $ad) {
            $translated = $this->get('translator');
            // SEO
            $canonicalLink = $this->get('router')->generate(
                $request->get('_route'),
                $request->get('_route_params'),
                true
            );
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.frontend.ad.redirect.title'))
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'description', $translated->trans('viteloge.frontend.ad.redirect.description'))
                ->addMeta('property', 'og:title', $seoPage->getTitle())
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $translated->trans('viteloge.frontend.ad.redirect.description'))
                ->setLinkCanonical($canonicalLink)
            ;
            // -- les stats sont déja ajouté

        /*    $forbiddenUA = array(
                'yakaz_bot' => 'YakazBot/1.0',
                'mitula_bot' => 'java/1.6.0_26'
            );
            $forbiddenIP = array(

            );
            $ua = $request->headers->get('User-Agent');
            $ip = $request->getClientIp();

            // log redirect
            if (!in_array($ua, $forbiddenUA) && !in_array($ip, $forbiddenIP)) {
                $now = new \DateTime('now');
                $statistics = new Statistics();
                $statistics->setIp($ip);
                $statistics->setUa($ua);
                $statistics->initFromAd($ad);

                $em = $this->getDoctrine()->getManager();
                $em->persist($statistics);
                $em->flush();
            }
*/
            return array(
                'ad' => $ad
            );


        }


         /**
         * Show latest ads with type and transaction(use in home)
         * Ajax call so we can have a public cache
         *
         * @Route(
         *     "/type/latest/{transaction}/{type}/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "5"
         *     },
         *     name="viteloge_frontend_ad_latest_transaction_type_limited"
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:fragment/ad_list.html.twig")
         */
        public function mostSearchedAction(Request $request,$transaction,$type, $limit=5) {
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:Ad');
            $ads = $repository->findByTransactionandType($transaction,$type, $limit);
            return $this->render(
                'VitelogeFrontendBundle:Ad:fragment/ad_list.html.twig',
                array(
                    'ads' => $ads,
                    'transaction' => $transaction,
                    'type' => $type,
                )
            );
        }

        /**
         * view the favourite list.
         *
         *
         * @Route(
         *     "/remove/favourite/{id}",
         *     name="viteloge_frontend_favourite_remove",
         *     requirements={
         *         "limit"="\d+"
         *     },
         * )
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:favourite.html.twig")
         */
        public function removeFavouriteAction(Request $request,$id ) {
           $translated = $this->get('translator');
           $currentUrl = $request->getUri();
           $session = $request->getSession();
           $requestSearch = $session->get('request');
            // Form
            $adSearch = new AdSearch();
            $adSearch->handleRequest($requestSearch);
            $form = $this->createForm('viteloge_core_adsearch', $adSearch);

            // Breadcrumbs
            $transaction = $adSearch->getTransaction();
            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem(
                $translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->get('router')->generate('viteloge_frontend_homepage')
            );
            $breadcrumbs->addItem(
                $translated->trans('breadcrumb.user', array(), 'breadcrumbs'),
                $this->get('router')->generate('viteloge_frontend_user_index')
            );
            $breadcrumbs->addItem(
            $TitleName =$translated->trans('breadcrumb.favourite', array(), 'breadcrumbs')
        );
               $cookies = $request->cookies;
            if ($cookies->has('viteloge_favorie')){
                $info_cookies_favorie = explode('#$#', $cookies->get('viteloge_favorie')) ;
                // on supprime l'id du cookies
                unset($info_cookies_favorie[array_search($id, $info_cookies_favorie)]);

                $repository = $this->getDoctrine()->getRepository('VitelogeCoreBundle:Ad');
             $ads = $repository->findById($info_cookies_favorie);
             // on reconstruit le cookie
             $cookies = $request->cookies;
                   $cookie_favorie = '';
                foreach ($info_cookies_favorie as $key => $value) {
                    if($key == 0){
                        $cookie_favorie = $value;
                    }else{
                      $cookie_favorie .= '#$#'.$value;
                    }

                }


            $response = new Response();
            $response->headers->setCookie(new Cookie('viteloge_favorie', $cookie_favorie));
             // SEO
            $canonicalLink = $this->get('router')->generate(
                $request->get('_route'),
                $request->get('_route_params'),
                true
            );
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($TitleName)
                ->addMeta('name', 'robots', 'noindex, follow')
                ->addMeta('property', 'og:title', $seoPage->getTitle())
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->setLinkCanonical($canonicalLink)
            ;


            return $this->render('VitelogeFrontendBundle:Ad:favourite.html.twig',array(
                'form' => $form->createView(),
                'ads' => $ads
            ), $response);

            }else{
               return $this->redirectToRoute(
                    'fos_user_profile_show');


            }


            }

        /**
         * view the favourite list.
         *
         *
         * @Route(
         *     "/list/favourite",
         *     name="viteloge_frontend_favourite_list"
         * )
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:favourite.html.twig")
         */
        public function listFavouriteAction(Request $request) {
           $translated = $this->get('translator');
           $currentUrl = $request->getUri();
           $session = $request->getSession();
           $requestSearch = $session->get('request');
            // Form
            $adSearch = new AdSearch();
            $adSearch->handleRequest($requestSearch);
            $form = $this->createForm('viteloge_core_adsearch', $adSearch);

            // Breadcrumbs
            $transaction = $adSearch->getTransaction();
            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem(
                $translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->get('router')->generate('viteloge_frontend_homepage')
            );
            $breadcrumbs->addItem(
                $translated->trans('breadcrumb.user', array(), 'breadcrumbs'),
                $this->get('router')->generate('viteloge_frontend_user_index')
            );
            $breadcrumbs->addItem(
            $TitleName =$translated->trans('breadcrumb.favourite', array(), 'breadcrumbs')
        );
               $cookies = $request->cookies;
            if ($cookies->has('viteloge_favorie')){
                $info_cookies_favorie = explode('#$#', $cookies->get('viteloge_favorie')) ;
                $repository = $this->getDoctrine()->getRepository('VitelogeCoreBundle:Ad');
             $ads = $repository->findById($info_cookies_favorie);
             $session->set('resultAd', $ads);
             // SEO
            $canonicalLink = $this->get('router')->generate(
                $request->get('_route'),
                $request->get('_route_params'),
                true
            );
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($TitleName)
                ->addMeta('name', 'robots', 'noindex, follow')
                ->addMeta('property', 'og:title', $seoPage->getTitle())
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->setLinkCanonical($canonicalLink)
            ;
            // --
            //$session->set('resultAd',$pagination->getCurrentPageResults());


            return array(
                'form' => $form->createView(),
                'ads' => $ads
            );

            }else{
               return $this->redirectToRoute(
                    'fos_user_profile_show');


            }


            }

    }


}
