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
         * Creates a form to delete a UserSearch entity by id.
         *
         * @param UserSearch the object
         * @return \Symfony\Component\Form\Form The form
         */
        private function createDeleteForm(UserSearch $userSearch) {
            $id = $userSearch->getId();
            $method = 'DELETE';
            $action = 'viteloge_frontend_usersearch_delete';
            return $this->createFormBuilder()
                ->setAction($this->generateUrl($action, array('id' => $id)))
                ->setMethod($method)
                ->add('submit', 'submit', array('label' => 'usersearch.delete'))
                ->getForm()
            ;
        }

        /**
         * @Route(
         *     "/delete/{id}",
         *     requirements={
         *         "id"="\d+"
         *     }
         * )
         * @Method("DELETE")
         * @Security("has_role('ROLE_USER')")
         * @ParamConverter("userSearch", class="VitelogeCoreBundle:UserSearch", options={"id" = "id"})
         * Template("VitelogeFrontendBundle:UserSearch:delete.html.twig")
         */
        public function deleteAction(Request $request, $userSearch) {
            $translated = $this->get('translator');

            $form = $this->createDeleteForm($userSearch);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $userSearch->setDeletedAt(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($userSearch);
                $em->flush();
                $this->addFlash(
                    'notice',
                    $translated->trans('usersearch.flash.deleted')
                );
            }

            return $this->redirectToRoute('viteloge_frontend_websearch_list');
        }

    }

}
