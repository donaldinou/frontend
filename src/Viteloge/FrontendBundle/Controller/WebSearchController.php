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
    use Viteloge\CoreBundle\Entity\WebSearch;

    /**
     * @Route("/user/websearch")
     */
    class WebSearchController extends Controller {

        /**
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:WebSearch:latest.html.twig")
         */
        public function latestAction(Request $request, $transaction=null, $limit=5) {
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:WebSearch');
            $webSearches = $repository->findBy(
                array(),
                array( 'updatedAt' => 'DESC' ),
                $limit
            );
            return array(
                'webSearches' => $webSearches
            );
        }

        /**
         * @Route("/list/")
         * @Method({"GET"})
         * @Security("has_role('ROLE_USER')")
         * @Template("VitelogeFrontendBundle:WebSearch:list.html.twig")
         */
        public function listAction(Request $request) {
            $webSearches = $this->getUser()->getWebSearches();
            return array(
                'webSearches' => $webSearches
            );
        }

        /**
         * @Route("/add/")
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:WebSearch:create.html.twig")
         */
        public function createAction(Request $request) {
            $webSearch = new WebSearch();
            $form = $this->createForm('viteloge_frontend_websearch', $webSearch);

            $form->handleRequest($request);
            if( $form->isValid() ) {
                $this->addFlash(
                    'notice',
                    'Your changes were saved!'
                );
                return $this->redirectToRoute('viteloge_frontend_user', $args);
            }

            return array(
                'count' => $count,
                'form' => $form->createView()
            );
        }

        /**
         * @Route(
         *     "/edit/{id}",
         *     requirements={
         *         "id"="\d+"
         *     }
         * )
         * @ParamConverter("webSearch", class="VitelogeCoreBundle:WebSearch", options={"id" = "id"})
         * @Template("VitelogeFrontendBundle:WebSearch:edit.html.twig")
         */
        public function editAction(Request $request, $webSearch) {

        }

        /**
         * @Route(
         *     "/delete/{id}",
         *     requirements={
         *         "id"="\d+"
         *     }
         * )
         * @ParamConverter("webSearch", class="VitelogeCoreBundle:WebSearch", options={"id" = "id"})
         * @Template("VitelogeFrontendBundle:WebSearch:delete.html.twig")
         */
        public function deleteAction(Request $request, $webSearch) {
            return array(
                'count' => $count,
                'form' => $form->createView()
            );
        }

    }

}
