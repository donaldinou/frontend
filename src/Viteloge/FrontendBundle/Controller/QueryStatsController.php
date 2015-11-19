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
    use Viteloge\CoreBundle\Entity\QueryStats;

    /**
     * @Route("/query")
     */
    class QueryStatsController extends Controller {

        /**
         * @Route(
         *      "/city/{slug}/{id}/{page}/{limit}",
         *      requirements={
         *          "id"="(?:2[a|b|A|B])?0{0,2}\d+",
         *          "page"="\d+",
         *          "limit"="\d+"
         *      },
         *      defaults={
        *           "page" = "1",
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
         *         "slug" = "slug",
         *         "exclude": {
         *             "slug"
         *         }
         *     }
         * )
         * @Cache(expires="tomorrow", public=true)
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

            $em = $this->getDoctrine()->getEntityManager();
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
         * Legacy function used because there is no slug saved in table
         * @Route(
         *      "/legacy/{urlrewrite}",
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
         *              "urlrewrite": "urlrewrite"
         *          },
         *          "map_method_signature": true
         *     }
         * )
         * @Cache(expires="tomorrow", public=true)
         */
        public function legacyAction(Request $request, QueryStats $queryStats) {
            return $this->redirectToRoute(
                'viteloge_frontend_ad_searchfromquerystats',
                array('id' => $queryStats->getId()),
                301
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
         *     }
         * )
         * @Route(
         *     "/latest/",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "9"
         *     }
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
