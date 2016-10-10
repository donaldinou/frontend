<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Serializer\Serializer;
    use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
    use Symfony\Component\Serializer\Encoder\JsonEncoder;
    use Viteloge\CoreBundle\Component\Enum\TransactionEnum;
    use Viteloge\CoreBundle\SearchEntity\Ad as AdSearch;

    /**
     * @Route("/")
     */
    class DefaultController extends Controller {

        /**
         * Homepage
         * No cache
         *
         * @Route(
         *     "/",
         *     defaults={},
         *     name="viteloge_frontend_homepage",
         *     options = {
         *          "i18n" = true,
         *          "vl_sitemap" = {
         *              "title" = "viteloge.frontend.default.index.title",
         *              "description" = "viteloge.frontend.default.index.description",
         *              "changefreq" = "hourly",
         *              "priority" = "1.0"
         *          }
         *     }
         * )
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Default:index.html.twig")
         */
        public function indexAction( Request $request ) {
            $translated = $this->get('translator');

            // SEO
            $canonicalLink = $this->get('router')->generate($request->get('_route'), array(), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.frontend.default.index.title'))
                ->addMeta('name', 'description', $translated->trans('viteloge.frontend.default.index.description'))
                ->addMeta('property', 'og:title', $translated->trans('viteloge.frontend.default.index.title'))
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $translated->trans('viteloge.frontend.default.index.description'))
            ;
            // --

            // Breadcrumb
            // --

            // This count is pretty faster than an elastic search count
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:Ad');
            $count = $repository->countByFiltered();

            // Form
            $entity = new AdSearch();
            $form = $this->createForm('viteloge_core_adsearch', $entity);
            // --
            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
            return array(
                'count' => $count,
                'form' => $form->createView(),
                'csrf_token' => $csrfToken,
            );
        }

         public function headerFormAction( Request $request ) {
            $session = $request->getSession();
            $requestSearch = $session->get('request');
            // Form
            $adSearch = new AdSearch();
          if(!is_null($requestSearch)){

           $adSearch->handleRequest($requestSearch);
          }
           $form = $this->createForm('viteloge_core_adsearch', $adSearch);
           $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
           return $this->render('VitelogeUserBundle:base:headerSearch.html.twig',array(
                'form' => $form->createView(),
                'csrf_token' => $csrfToken,
            ));
         }

    }

}
