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
         * @Route(
         *     "/",
         *     requirements={
         *         "transaction"="V|L|N"
         *     },
         *     defaults={
         *         "transaction" = "L",
         *     },
         *     name="viteloge_frontend_homepage",
         *     options = {
         *         "i18n" = true
         *     }
         * )
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Default:index.html.twig")
         */
        public function indexAction( Request $request, $transaction ) {
            // SEO
            $canonicalLink = $this->get('router')->generate($request->get('_route'), array(), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle('viteloge.frontend.default.index.title')
                ->addMeta('name', 'description', 'viteloge.frontend.default.index.description')
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
                ->getRepository('VitelogeCoreBundle:Ad');
            $count = $repository->countByFiltered();

            // Form
            $entity = new AdSearch();
            $entity->setTransaction($transaction);
            $form = $this->createForm('viteloge_core_adsearch', $entity);
            // --

            return array(
                'transaction' => $transaction,
                'count' => $count,
                'form' => $form->createView()
            );
        }

    }

}
