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
    use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
    use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
    use Symfony\Component\Security\Acl\Permission\MaskBuilder;
    use Pagerfanta\Pagerfanta;
    use Pagerfanta\Adapter\ArrayAdapter;
    use Pagerfanta\Adapter\DoctrineORMAdapter;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Viteloge\InseeBundle\Entity\InseeDepartment;
    use Viteloge\CoreBundle\Entity\QueryStats;
    use Viteloge\CoreBundle\SearchEntity\Ad as AdSearch;

    /**
     * @Route("/query")
     */
    class QueryStatsController extends Controller {

        /**
         * Display queries stats for a city
         * Private cache
         *
         * @Route(
         *      "/city/{name}/{id}/{page}/{limit}",
         *      requirements={
         *          "id"="(?:2[a|b|A|B])?0{0,2}\d+",
         *          "page"="\d+",
         *          "limit"="\d+"
         *      },
         *      defaults={
         *          "page" = "1",
         *          "limit" = "100"
         *      },
         *      name="viteloge_frontend_querystats_city",
         *      options = {
         *         "i18n" = true
         *      }
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
         * @Template()
         */
        public function cityAction(Request $request, InseeCity $inseeCity, $page, $limit) {
            $translated = $this->get('translator');

            // SEO
            $canonicalLink = $this->get('router')->generate($request->get('_route'), $request->get('_route_params'), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle('viteloge.frontend.querystats.city.title')
                ->addMeta('name', 'description', 'viteloge.frontend.querystats.city.description')
                ->addMeta('name', 'robots', 'index, follow')
                ->addMeta('property', 'og:title', "viteloge.frontend.querystats.city.title")
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', 'viteloge.frontend.querystats.city.description')
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
                $translated->trans('breadcrumb.querystats.city', array('%city%' => $inseeCity->getName()), 'breadcrumbs')
            );
            // --

            $em = $this->getDoctrine()->getManager();
            $queryBuilder = $em->createQueryBuilder()
                ->select('qs')
                ->from('VitelogeCoreBundle:QueryStats', 'qs')
                ->where('qs.inseeCity = :inseeCity')
                ->setParameter('inseeCity', $inseeCity)
            ;

            $adapter = new DoctrineORMAdapter($queryBuilder);
            $pagination = new Pagerfanta($adapter);
            $pagination->setCurrentPage($page);
            $pagination->setMaxPerPage($limit);

            return array(
                'inseeCity' => $inseeCity,
                'queries' => $pagination->getCurrentPageResults(),
                'pagination' => $pagination
            );
        }

        /**
         * Legacy function used in order to have some old compatibilities
         * @Route(
         *      "/legacy/{slug}",
         *      defaults={},
         *      name="viteloge_frontend_querystats_legacy"
         * )
         * @Method({"GET"})
         * @ParamConverter(
         *     "queryStats",
         *     class="VitelogeCoreBundle:QueryStats",
         *     options={
         *          "repository_method" = "findOneByUrlrewrite",
         *          "mapping" = {
         *              "slug": "urlrewrite"
         *          },
         *          "map_method_signature": true
         *     }
         * )
         * @Cache(expires="tomorrow", public=true)
         *
         * @deprecated
         */
        public function legacyAction(Request $request, QueryStats $queryStats) {
            return $this->redirectToRoute(
                'viteloge_frontend_querystats_ad',
                array(
                    'slug' => $queryStats->getSlug()
                ),
                301
            );
        }

        /**
         * Ads from a querystats url
         * Cache is set from set last timestamp
         *
         * @Route(
         *      "/ad/{slug}/{page}/{limit}",
         *      defaults={},
         *      requirements={
         *         "page"="\d+",
         *         "limit"="\d+"
         *      },
         *      defaults={
         *         "page"=1,
         *         "limit"="25"
         *      },
         *      name="viteloge_frontend_querystats_ad"
         * )
         * @Method({"GET"})
         * @ParamConverter(
         *     "queryStats",
         *     class="VitelogeCoreBundle:QueryStats",
         *     options={
         *          "repository_method" = "findOneByUrlrewrite",
         *          "mapping" = {
         *              "slug": "urlrewrite"
         *          },
         *          "map_method_signature": true
         *     }
         * )
         * @Template("VitelogeFrontendBundle:QueryStats:ad.html.twig")
         * @Cache(lastModified="queryStats.getUpdateAt()", ETag="'QueryStats' ~ queryStats.getId() ~ queryStats.getTimestamp()")
         */
        public function adAction(Request $request, QueryStats $queryStats, $page, $limit) {
            $translated = $this->get('translator');

            $em = $this->getDoctrine()->getManager();
            $queryStats->setCount($queryStats->getCount()+1);
            $em->persist($queryStats);
            $em->flush();

            $inseeDepartment = $queryStats->getInseeDepartment();
            $inseeCity = $queryStats->getInseeCity();

            // BUGFIX: querystats id is not null but inseeCity does not exist
            if ($inseeDepartment instanceof InseeDepartment) {
                try {
                    $inseeDepartment->__load();
                } catch (\Doctrine\ORM\EntityNotFoundException $e) {
                    $inseeDepartment = null;
                }
            }
            if ($inseeCity instanceof InseeCity) {
                try {
                    $inseeCity->__load();
                } catch (\Doctrine\ORM\EntityNotFoundException $e) {
                    $inseeCity = null;
                }
            }
            // --

            $adSearch = new AdSearch();
            $adSearch->setTransaction($queryStats->getTransaction());
            $adSearch->setWhat(ucfirst($queryStats->getType()));
            $adSearch->setRooms($queryStats->getRooms());

            if ($inseeCity instanceof InseeCity) {
                $adSearch->setWhere($inseeCity->getId());
                $adSearch->setLocation($inseeCity->getLocation());
            }

            $form = $this->createForm('viteloge_core_adsearch', $adSearch);

            // Save session
            $session = $request->getSession();
            $session->set('adSearch', $adSearch);
            // --

            // Breadcrumbs
            $transaction = $adSearch->getTransaction();
            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem(
                $translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->get('router')->generate('viteloge_frontend_homepage')
            );
            if ($inseeDepartment instanceof InseeDepartment) {
                $breadcrumbTitle  = (!empty($transaction)) ? $translated->trans('ad.transaction.'.strtoupper($transaction[0])).' ' : '';
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
                $breadcrumbTitle  = (!empty($transaction)) ? $translated->trans('ad.transaction.'.strtoupper($transaction[0])).' ' : '';
                $breadcrumbTitle .= $inseeCity->getFullname().' ('.$inseeCity->getInseeDepartment()->getId().')';
                $breadcrumbs->addItem(
                    $breadcrumbTitle,
                    $this->get('router')->generate('viteloge_frontend_glossary_showcity',
                        array(
                            'name' => $inseeCity->getSlug(),
                            'id' => $inseeCity->getId()
                        )
                    )
                );
            }
            $breadcrumbTitle = $queryStats->getKeywords();
            $breadcrumbs->addItem($breadcrumbTitle);

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
            $cityTitle = '';
            if ($inseeCity instanceof InseeCity) {
                $cityTitle = $inseeCity->getFullname().' ('.$inseeCity->getInseeDepartment()->getId().')';
            }
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($breadcrumbTitle.' - '.$translated->trans('viteloge.frontend.querystats.ad.title', array('%city%' => $cityTitle, '%keywords%' => $queryStats->getKeywords())))
                ->addMeta('name', 'robots', 'index, follow')
                ->addMeta('name', 'description', $breadcrumbTitle.' - '.$translated->trans('viteloge.frontend.querystats.ad.description', array('%city%' => $cityTitle, '%keywords%' => $queryStats->getKeywords())))
                ->addMeta('property', 'og:title', $seoPage->getTitle())
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $breadcrumbTitle.' - '.$translated->trans('viteloge.frontend.querystats.ad.description', array('%city%' => $cityTitle, '%keywords%' => $queryStats->getKeywords())))
                ->setLinkCanonical($canonicalLink)
            ;
            // --
              $session->set('totalResult',$pagination->getNbResults());
              $session->set('resultAd',$pagination->getCurrentPageResults());
              $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
            return array(
                'form' => $form->createView(),
                'queryStats' => $queryStats,
                'ads' => $pagination->getCurrentPageResults(),
                'pagination' => $pagination,
                'csrf_token' => $csrfToken,
            );
        }

        /**
         * Display latest query stats
         * Ajax call so we can use public cache
         *
         * @Route(
         *     "/latest/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "9"
         *     },
         *     name="viteloge_frontend_querystats_latest_limited"
         * )
         * @Route(
         *     "/latest/",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "9"
         *     },
         *     name="viteloge_frontend_querystats_latest"
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:QueryStats:latest.html.twig")
         */
        public function latestAction(Request $request, $limit) {
            // SEO
            $canonicalLink = $this->get('router')->generate($request->get('_route'), array(), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle('viteloge.frontend.default.index.title')
                ->addMeta('name', 'description', 'viteloge.frontend.default.index.description')
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('property', 'og:title', "viteloge.frontend.default.index.title")
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', 'viteloge.frontend.default.index.description')
                ->setLinkCanonical($canonicalLink)
            ;
            // --

            // Breadcrumb
            // --

            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:QueryStats');
            $queries = $repository->findByFiltered(
                $request->query->all(),
                array( 'timestamp' => 'DESC' ),
                $limit
            );
            return array(
                'queries' => $queries
            );
        }

    }

}
