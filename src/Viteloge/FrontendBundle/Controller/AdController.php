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
    use Pagerfanta\Pagerfanta;
    use Pagerfanta\Adapter\ArrayAdapter;
    use Pagerfanta\Adapter\DoctrineORMAdapter;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Acreat\InseeBundle\Entity\InseeDepartment;
    use Acreat\InseeBundle\Entity\InseeState;
    use Viteloge\CoreBundle\Entity\Ad;
    use Viteloge\CoreBundle\Entity\QueryStats;
    use Viteloge\CoreBundle\Entity\Statistics;
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
         *      "/count.{_format}",
         *      requirements={
         *          "_format"="txt"
         *      },
         *      defaults={
         *          "_format"="txt"
         *      },
         *      name="viteloge_frontend_ad_count",
         *      options = {
         *          "i18n" = true
         *      }
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template()
         */
        public function countAction(Request $request) {
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
         * @Template("VitelogeFrontendBundle:Ad:search.html.twig")
         */
        public function searchAction(Request $request, $page, $limit) {
            $translated = $this->get('translator');

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
                ->addMeta('name', 'description', $breadcrumbTitle.' - '.$translated->trans('viteloge.frontend.ad.search.description'))
                ->addMeta('property', 'og:title', $seoPage->getTitle())
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $breadcrumbTitle.' - '.$translated->trans('viteloge.frontend.ad.search.description'))
                ->setLinkCanonical($canonicalLink)
            ;
            // --

            return array(
                'form' => $form->createView(),
                'ads' => $pagination->getCurrentPageResults(),
                'pagination' => $pagination
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
            // --

            $forbiddenUA = array(
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

            return array(
                'ad' => $ad
            );
        }

    }

}
