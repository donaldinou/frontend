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
    use Viteloge\CoreBundle\Entity\UserSearch;

    /**
     * @Route("/user/search")
     */
    class UserSearchController extends Controller {

        /**
         * @Route("/list/")
         * @Method({"GET"})
         * @Security("has_role('ROLE_USER')")
         * @Template("VitelogeFrontendBundle:UserSearch:list.html.twig")
         */
        public function listAction(Request $request) {
            $userSearches = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:UserSearch')
                ->findByMail($this->getUser()->getEmail());

            return array(
                'userSearches' => $userSearches
            );
        }

        /**
         * @Route("/add/")
         * @Method({"GET"})
         * @Security("has_role('ROLE_USER')")
         * @Template("VitelogeFrontendBundle:UserSearch:create.html.twig")
         */
        public function createAction(Request $request) {
            $userSearch = new UserSearch();
            $form = $this->createForm('viteloge_frontend_usersearch', $userSearch);

            $form->handleRequest($request);
            if( $form->isValid() ) {
                $this->addFlash(
                    'notice',
                    'Your changes were saved!'
                );
                return $this->redirectToRoute('viteloge_frontend_user', $args);
            }

            return array(
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
         * @ParamConverter("userSearch", class="VitelogeCoreBundle:UserSearch", options={"id" = "id"})
         * @Template("VitelogeFrontendBundle:UserSearch:edit.html.twig")
         */
        public function editAction(Request $request, $userSearch) {
            $form = $this->createForm('viteloge_frontend_usersearch', $userSearch);

            $form->handleRequest($request);
            if( $form->isValid() ) {
                $this->addFlash(
                    'notice',
                    'Your changes were saved!'
                );
                return $this->redirectToRoute('viteloge_frontend_user', $args);
            }

            $count = 1;

            return array(
                'count' => $count,
                'form' => $form->createView()
            );
        }

        /**
         * @Route(
         *     "/delete/{id}",
         *     requirements={
         *         "id"="\d+"
         *     }
         * )
         * @ParamConverter("userSearch", class="VitelogeCoreBundle:UserSearch", options={"id" = "id"})
         * @Template("VitelogeFrontendBundle:UserSearch:delete.html.twig")
         */
        public function deleteAction(Request $request, $userSearch) {
            return array(
                'count' => $count,
                'form' => $form->createView()
            );
        }

    }

}
