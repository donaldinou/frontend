<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\EventDispatcher\EventDispatcher;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
    use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
    use Symfony\Component\Security\Acl\Permission\MaskBuilder;
    use Viteloge\CoreBundle\Entity\WebSearch;

    /**
     * @Route("/user/websearch")
     */
    class WebSearchController extends Controller {

        /**
         * @var WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs
         */
        protected $breadcrumbs;

        /**
         * Initialize breadcrumbs
         * @param boolean $isRoot true if it was the root of the controller
         * @param string $rootTitle the title to display for root
         * @return Viteloge\FrontendBundle\Controller\WebSearchController
         */
        protected function initBreadcrumbs($isRoot=false, $rootTitle='Alert', $rootRoute='viteloge_frontend_websearch_list') {
            $this->breadcrumbs = $this->get('white_october_breadcrumbs');
            $this->breadcrumbs->addItem(
                'Home', $this->get('router')->generate('viteloge_frontend_homepage')
            );
            $this->breadcrumbs->addItem(
                'User', $this->get('router')->generate('viteloge_frontend_user_index')
            );
            if ($isRoot) {
                $this->breadcrumbs->addItem($rootTitle);
            } else {
                $this->breadcrumbs->addItem(
                    $rootTitle, $this->get('router')->generate($rootRoute)
                );
            }
            return $this;
        }

        /**
         * @Route(
         *      "/",
         *      name="viteloge_frontend_websearch_index"
         * )
         * @Method({"GET"})
         * Template("VitelogeFrontendBundle:WebSearch:index.html.twig")
         */
        public function indexAction() {
            return $this->redirectToRoute('viteloge_frontend_websearch_list');
        }

        /**
         * @Route(
         *      "/list/",
         *      name="viteloge_frontend_websearch_list"
         * )
         * @Method({"GET"})
         * @Security("has_role('ROLE_USER')")
         * @Template("VitelogeFrontendBundle:WebSearch:list.html.twig")
         */
        public function listAction(Request $request) {
            // Breadcrumbs
            $this->initBreadcrumbs(true, 'Alert list');
            // --

            $webSearches = $this->getUser()->getWebSearches();
            return array(
                'webSearches' => $webSearches
            );
        }

        /**
         * @Route(
         *      "/history/",
         *      name="viteloge_frontend_websearch_history"
         * )
         * @Method({"GET"})
         * @Security("has_role('ROLE_USER')")
         * @Template("VitelogeFrontendBundle:WebSearch:history.html.twig")
         */
        public function historyAction(Request $request) {
            // Breadcrumbs
            $this->initBreadcrumbs(true, 'History list');
            // --

            // show deleted
            $em = $this->getDoctrine()->getManager();
            $filters = $em->getFilters();
            $filters->disable('softdeleteable');
            // --

            $webSearches = $this->getUser()->getWebSearches();
            return array(
                'webSearches' => $webSearches
            );
        }

        /**
         * Used in homepage
         * @Route(
         *     "/latest/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "5"
         *     },
         *     name="viteloge_frontend_websearch_latest"
         * )
         * @Route(
         *      "/latest/",
         *      requirements={
         *         "limit"="\d+"
         *      },
         *      defaults={
         *         "limit" = "5"
         *      },
         *      name="viteloge_frontend_websearch_latest"
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:WebSearch:latest.html.twig")
         */
        public function latestAction(Request $request, $limit) {
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:WebSearch');
            $webSearches = $repository->findBy(
                array( 'deletedAt' => null ),
                array( 'updatedAt' => 'DESC' ),
                $limit
            );
            return array(
                'webSearches' => $webSearches
            );
        }

        /**
         * @Route(
         *     "/show/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_websearch_show"
         * )
         * @Method({"GET"})
         * @Security("has_role('ROLE_USER')")
         * @ParamConverter("webSearch", class="VitelogeCoreBundle:WebSearch", options={"id" = "id"})
         * Template("VitelogeFrontendBundle:WebSearch:show.html.twig")
         */
        public function showAction(Request $request, WebSearch $webSearch) {
            // Breadcrumbs
            // --

            $options = array( 'id' => $websearch->getUserSearch()->getId() );
            return $this->redirectToRoute(
                'viteloge_frontend_ad_searchfromusersearch',
                $options,
                301
            );
        }

        /**
         * Creates a form to create a WebSearch entity.
         *
         * @param WebSearch the object
         * @return \Symfony\Component\Form\Form The form
         */
        private function createCreateForm(WebSearch $webSearch) {
            return $this->createForm(
                'viteloge_frontend_websearch',
                $webSearch,
                array(
                    'action' => $this->generateUrl('viteloge_frontend_websearch_create'),
                    'method' => 'POST'
                )
            );
            return $form;
        }

        /**
         * @Route(
         *      "/add/",
         *      name="viteloge_frontend_websearch_new"
         * )
         * @Method({"GET"})
         * @Security("has_role('ROLE_USER')")
         * @Template("VitelogeFrontendBundle:WebSearch:create.html.twig")
         */
        public function newAction(Request $request) {
            // Breadcrumbs
            $this->initBreadcrumbs();
            $this->breadcrumbs->addItem('Add alert');
            // --

            $webSearch = new WebSearch();
            $form = $this->createCreateForm($webSearch);

            return array(
                'websearch' => $webSearch,
                'form' => $form->createView()
            );
        }

        /**
         * @Route(
         *      "/create/",
         *      name="viteloge_frontend_websearch_create"
         * )
         * @Method({"POST"})
         * @Security("has_role('ROLE_USER')")
         * @Template("VitelogeFrontendBundle:WebSearch:create.html.twig")
         */
        public function createAction(Request $request) {
            // Breadcrumbs
            $this->initBreadcrumbs();
            $this->breadcrumbs->addItem('Add alert');
            // --

            $webSearch = new WebSearch();
            $form = $this->createCreateForm($webSearch);
            $form->handleRequest($request);

            if( $form->isValid() ) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($webSearch);
                $em->flush();
                $this->addFlash(
                    'notice',
                    'Your changes were saved!'
                );
                return $this->redirectToRoute('viteloge_frontend_websearch_list');
            }

            return array(
                'websearch' => $webSearch,
                'form' => $form->createView()
            );
        }

        /**
         * Creates a form to edit a WebSearch entity.
         *
         * @param WebSearch the object
         * @return \Symfony\Component\Form\Form The form
         */
        private function createEditForm(WebSearch $webSearch) {
            return $this->createForm(
                'viteloge_frontend_websearch',
                $webSearch,
                array(
                    'action' => $this->generateUrl('viteloge_frontend_websearch_update', array('id' => $webSearch->getId())),
                    'method' => 'PUT'
                )
            );
        }

        /**
         * @Route(
         *     "/edit/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_websearch_edit"
         * )
         * @Method({"GET"})
         * @Security("has_role('ROLE_USER')")
         * @ParamConverter("webSearch", class="VitelogeCoreBundle:WebSearch", options={"id" = "id"})
         * @Template("VitelogeFrontendBundle:WebSearch:edit.html.twig")
         */
        public function editAction(Request $request, WebSearch $webSearch) {
            // Breadcrumbs
            $this->initBreadcrumbs();
            $this->breadcrumbs->addItem('Edit '.$webSearch->getTitle());
            // --

            $deleteForm = $this->createDeleteForm($webSearch);
            $editForm = $this->createEditForm($webSearch);
            $editForm->handleRequest($request);

            return array(
                'websearch' => $webSearch,
                'form' => $editForm->createView(),
                'form_delete' => $deleteForm->createView()
            );
        }

        /**
         * @Route(
         *     "/history/edit/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_websearch_history_edit"
         * )
         * @Method({"GET"})
         * @Security("has_role('ROLE_USER')")
         * @ParamConverter("webSearch", class="VitelogeCoreBundle:WebSearch", options={
         *    "repository_method" = "findHistory",
         *    "mapping": {"id": "id"},
         *    "map_method_signature" = true
         * })
         * @Template("VitelogeFrontendBundle:WebSearch:history_edit.html.twig")
         */
        public function historyEditAction(Request $request, WebSearch $webSearch) {
            // Breadcrumbs
            $this->initBreadcrumbs(false, 'History', 'viteloge_frontend_websearch_history');
            $this->breadcrumbs->addItem('Edit '.$webSearch->getTitle());
            // --

            $deleteForm = $this->createActivateForm($webSearch);
            $editForm = $this->createEditForm($webSearch);
            $editForm->handleRequest($request);

            return array(
                'websearch' => $webSearch,
                'form' => $editForm->createView(),
                'form_delete' => $deleteForm->createView()
            );
        }

        /**
         * @Route(
         *     "/update/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_websearch_update"
         * )
         * @Method("PUT")
         * @Security("has_role('ROLE_USER')")
         * @ParamConverter("webSearch", class="VitelogeCoreBundle:WebSearch", options={"id" = "id"})
         * @Template("VitelogeFrontendBundle:WebSearch:edit.html.twig")
         */
        public function updateAction(Request $request, WebSearch $webSearch) {
            // Breadcrumbs
            $this->initBreadcrumbs();
            $this->breadcrumbs->addItem('Edit '.ucfirst($webSearch->getTitle()));
            // --

            $deleteForm = $this->createDeleteForm($webSearch);
            $editForm = $this->createEditForm($webSearch);
            $editForm->handleRequest($request);

            if( $editForm->isValid() ) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($webSearch);
                $em->flush();
                $this->addFlash(
                    'notice',
                    'Your changes were saved!'
                );
                return $this->redirectToRoute('viteloge_frontend_websearch_list');
            }

            return array(
                'websearch' => $webSearch,
                'form' => $editForm->createView(),
                'form_delete' => $deleteForm->createView()
            );
        }

        /**
         * Creates a form to delete a WebSearch entity by id.
         *
         * @param WebSearch the object
         * @return \Symfony\Component\Form\Form The form
         */
        private function createDeleteForm(WebSearch $webSearch) {
            $id = $webSearch->getId();
            $isMailEnabled = $webSearch->getUserSearch()->isMailEnabled();
            $method = ($isMailEnabled) ? 'GET' : 'DELETE';
            $action = ($isMailEnabled) ? 'viteloge_frontend_websearch_remove' : 'viteloge_frontend_websearch_delete';
            return $this->createFormBuilder()
                ->setAction($this->generateUrl($action, array('id' => $id)))
                ->setMethod($method)
                ->add('submit', 'submit', array('label' => 'websearch.delete'))
                ->getForm()
            ;
        }

        /**
         * @Route(
         *     "/remove/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_websearch_remove"
         * )
         * @Method("GET")
         * @Security("has_role('ROLE_USER')")
         * @ParamConverter("webSearch", class="VitelogeCoreBundle:WebSearch", options={"id" = "id"})
         * @Template("VitelogeFrontendBundle:WebSearch:remove.html.twig")
         */
        public function removeAction(Request $request, WebSearch $webSearch) {
            $form = $this->createDeleteForm($webSearch);
            $userSearch = $webSearch->getUserSearch();
            $userSearchForm = $this->createFormBuilder()
                ->setAction($this->generateUrl('viteloge_frontend_usersearch_delete', array('id' => $userSearch->getId())))
                ->setMethod('DELETE')
                ->add('submit', 'submit', array('label' => 'usersearch.delete'))
                ->getForm()
            ;
            return array(
                'form_usersearch_delete' => $userSearchForm->createView(),
                'form_websearch_delete' => $form->createView()
            );
        }

        /**
         * @Route(
         *     "/delete/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_websearch_delete"
         * )
         * @Method("DELETE")
         * @Security("has_role('ROLE_USER')")
         * @ParamConverter("webSearch", class="VitelogeCoreBundle:WebSearch", options={"id" = "id"})
         * Template("VitelogeFrontendBundle:WebSearch:delete.html.twig")
         */
        public function deleteAction(Request $request, WebSearch $webSearch) {
            $form = $this->createDeleteForm($webSearch);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($webSearch);
                $em->flush();
                $this->addFlash(
                    'notice',
                    'Your changes were saved!'
                );
            }

            return $this->redirectToRoute('viteloge_frontend_websearch_list');
        }

        /**
         * Creates a form to activate a WebSearch entity by id.
         *
         * @param WebSearch the object
         * @return \Symfony\Component\Form\Form The form
         */
        private function createActivateForm(WebSearch $webSearch) {
            $id = $webSearch->getId();
            $method = 'PUT';
            $action = 'viteloge_frontend_websearch_activate';
            return $this->createFormBuilder()
                ->setAction($this->generateUrl($action, array('id' => $id)))
                ->setMethod($method)
                ->add('submit', 'submit', array('label' => 'websearch.activate'))
                ->getForm()
            ;
        }

        /**
         * @Route(
         *     "/activate/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_websearch_activate"
         * )
         * @Method("PUT")
         * @Security("has_role('ROLE_USER')")
         * @ParamConverter("webSearch", class="VitelogeCoreBundle:WebSearch", options={"id" = "id"})
         * Template("VitelogeFrontendBundle:WebSearch:activate.html.twig")
         */
        public function activateAction(Request $request, WebSearch $webSearch) {
            $form = $this->createActivateForm($webSearch);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $webSearch->setDeletedAt(null);
                $em = $this->getDoctrine()->getManager();
                $em->persist($webSearch);
                $em->flush();
                $this->addFlash(
                    'notice',
                    'Your changes were saved!'
                );
            }

            return $this->redirectToRoute('viteloge_frontend_websearch_list');
        }

    }

}
