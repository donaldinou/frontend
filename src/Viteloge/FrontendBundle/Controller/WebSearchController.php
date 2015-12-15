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
    use Pagerfanta\Pagerfanta;
    use Pagerfanta\Adapter\ArrayAdapter;
    use Pagerfanta\Adapter\DoctrineORMAdapter;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Viteloge\CoreBundle\Entity\WebSearch;
    use Viteloge\CoreBundle\Entity\UserSearch;
    use Viteloge\CoreBundle\SearchEntity\Ad as AdSearch;

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
        protected function initBreadcrumbs($isRoot=false, $rootTitle='breadcrumb.alert', $rootRoute='viteloge_frontend_websearch_list') {
            $translated = $this->get('translator');
            $this->breadcrumbs = $this->get('white_october_breadcrumbs');
            $this->breadcrumbs->addItem(
                $translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->get('router')->generate('viteloge_frontend_homepage')
            );
            $this->breadcrumbs->addItem(
                $translated->trans('breadcrumb.user', array(), 'breadcrumbs'),
                $this->get('router')->generate('viteloge_frontend_user_index')
            );
            if ($isRoot) {
                $this->breadcrumbs->addItem(
                    $translated->trans($rootTitle, array(), 'breadcrumbs')
                );
            } else {
                $this->breadcrumbs->addItem(
                    $translated->trans($rootTitle, array(), 'breadcrumbs'),
                    $this->get('router')->generate($rootRoute)
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
            $translated = $this->get('translator');

            // Breadcrumbs
            $this->initBreadcrumbs(true, 'breadcrumb.alert.list');
            // --

            // SEO
            $canonicalLink = $this->get('router')->generate($request->get('_route'), array(), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.frontend.websearch.list.title'))
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'description', $translated->trans('viteloge.frontend.websearch.list.description'))
                ->addMeta('property', 'og:title', $translated->trans('viteloge.frontend.websearch.list.title'))
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $translated->trans('viteloge.frontend.websearch.list.description'))
            ;
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
         * @Security("has_role('ROLE_OPERATOR')")
         * @Template("VitelogeFrontendBundle:WebSearch:history.html.twig")
         */
        public function historyAction(Request $request) {
            $translated = $this->get('translator');

            // Breadcrumbs
            $this->initBreadcrumbs(true, 'breadcrumb.alert.history');
            // --

            // SEO
            $canonicalLink = $this->get('router')->generate($request->get('_route'), array(), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.frontend.websearch.history.title'))
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'description', $translated->trans('viteloge.frontend.websearch.history.description'))
                ->addMeta('property', 'og:title', $translated->trans('viteloge.frontend.websearch.history.title'))
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $translated->trans('viteloge.frontend.websearch.history.description'))
            ;
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
         * Display the last websearch (Used in homepage)
         * Ajax call so we could have shared public cache
         *
         * @Route(
         *     "/latest/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "5"
         *     },
         *     name="viteloge_frontend_websearch_latest_limited"
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
         * @Cache(smaxage="300", maxage="300", public=true)
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
         * Search from websearch
         * Cache is set with the last update date
         *
         * @Route(
         *     "/show/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_frontend_websearch_show"
         * )
         * @Cache(lastModified="webSearch.getUpdatedAt()", ETag="'WebSearch' ~ webSearch.getId() ~ webSearch.getUpdatedAt()")
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
            $translated = $this->get('translator');

            // Breadcrumbs
            $this->initBreadcrumbs();
            $this->breadcrumbs->addItem($translated->trans('breadcrumb.alert.action.add', array(), 'breadcrumbs'));
            // --

            // SEO
            $canonicalLink = $this->get('router')->generate($request->get('_route'), array(), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.frontend.websearch.new.title'))
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'description', $translated->trans('viteloge.frontend.websearch.new.description'))
                ->addMeta('property', 'og:title', $translated->trans('viteloge.frontend.websearch.new.title'))
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $translated->trans('viteloge.frontend.websearch.new.description'))
            ;
            // --

            $webSearch = new WebSearch();
            $webSearch->setUserSearch(new UserSearch());

            // try to get session
            if ($request->query->has('session')) {
                $session = $request->getSession();
                if ($session->has('adSearch')) {
                    $adSearch = $session->get('adSearch');
                    if ($adSearch instanceof AdSearch) {
                        $inseeCity = null;
                        $cityId = (is_array($adSearch->getWhere())) ? current($adSearch->getWhere()) : null;
                        if (!empty($cityId)) {
                            $cityRepository = $this->getDoctrine()->getRepository('AcreatInseeBundle:InseeCity');
                            $inseeCity = $cityRepository->find((int)$cityId);
                        }
                        $now = new \DateTime('now');
                        $webSearch->setTitle($translated->trans('websearch.title.date', array('%date%' => $now->format('d/m/Y')), null));
                        $webSearch->getUserSearch()->setTransaction($adSearch->getTransaction());
                        $webSearch->getUserSearch()->setType($adSearch->getWhat());
                        $webSearch->getUserSearch()->setInseeCity($inseeCity);
                        $webSearch->getUserSearch()->setRadius($adSearch->getRadius());
                        $webSearch->getUserSearch()->setRooms($adSearch->getRooms());
                        $webSearch->getUserSearch()->setBudgetMin($adSearch->getMinPrice());
                        $webSearch->getUserSearch()->setBudgetMax($adSearch->getMaxPrice());
                        $webSearch->getUserSearch()->setKeywords($adSearch->getKeywords());

                        $this->addFlash(
                            'warning',
                            $translated->trans('websearch.flash.creating')
                        );
                    }
                }
            }

            // by default check mail enabled
            $webSearch->getUserSearch()->setMailEnabled(true);

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
            $translated = $this->get('translator');

            // Breadcrumbs
            $this->initBreadcrumbs();
            $this->breadcrumbs->addItem($translated->trans('breadcrumb.alert.action.add', array(), 'breadcrumbs'));
            // --

            // SEO
            $canonicalLink = $this->get('router')->generate('viteloge_frontend_websearch_new', array(), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.frontend.websearch.new.title'))
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'description', $translated->trans('viteloge.frontend.websearch.new.description'))
                ->addMeta('property', 'og:title', $translated->trans('viteloge.frontend.websearch.new.title'))
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $translated->trans('viteloge.frontend.websearch.new.description'))
            ;
            // --

            $webSearch = new WebSearch();
            $form = $this->createCreateForm($webSearch);
            $form->handleRequest($request);

            $session = $request->getSession();

            if( $form->isValid() ) {
                if ($session->has('adSearch')) {
                    $session->remove('adSearch');
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($webSearch);
                $em->flush();
                $this->addFlash(
                    'notice',
                    $translated->trans('websearch.flash.created', array('%title%' => $webSearch->getTitle()))
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
            $translated = $this->get('translator');

            if ( $this->getUser() != $webSearch->getUser() ) {
                throw $this->createAccessDeniedException();
            }

            // Breadcrumbs
            $this->initBreadcrumbs();
            $this->breadcrumbs->addItem(
                $translated->trans('breadcrumb.alert.action.edit', array(), 'breadcrumbs').' '.ucfirst($webSearch->getTitle())
            );
            // --

            // SEO
            $canonicalLink = $this->get('router')->generate($request->get('_route'), array('id' => $webSearch->getId()), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.frontend.websearch.edit.title'))
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'description', $translated->trans('viteloge.frontend.websearch.edit.description'))
                ->addMeta('property', 'og:title', $translated->trans('viteloge.frontend.websearch.edit.title'))
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $translated->trans('viteloge.frontend.websearch.edit.description'))
            ;
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
         * @Security("has_role('ROLE_OPERATOR')")
         * @ParamConverter("webSearch", class="VitelogeCoreBundle:WebSearch", options={
         *    "repository_method" = "findHistory",
         *    "mapping": {"id": "id"},
         *    "map_method_signature" = true
         * })
         * @Template("VitelogeFrontendBundle:WebSearch:history_edit.html.twig")
         */
        public function historyEditAction(Request $request, WebSearch $webSearch) {
            $translated = $this->get('translator');

            if ( $this->getUser() != $webSearch->getUser() ) {
                throw $this->createAccessDeniedException();
            }

            // Breadcrumbs
            $this->initBreadcrumbs(false, 'breadcrumb.alert.history', 'viteloge_frontend_websearch_history');
            $this->breadcrumbs->addItem(
                $translated->trans('breadcrumb.alert.action.edit', array(), 'breadcrumbs').' '.ucfirst($webSearch->getTitle())
            );
            // --

            // SEO
            $canonicalLink = $this->get('router')->generate($request->get('_route'), array('id' => $webSearch->getId()), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.frontend.websearch.historyedit.title'))
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'description', $translated->trans('viteloge.frontend.websearch.historyedit.description'))
                ->addMeta('property', 'og:title', $translated->trans('viteloge.frontend.websearch.historyedit.title'))
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $translated->trans('viteloge.frontend.websearch.historyedit.description'))
            ;
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
            $translated = $this->get('translator');

            // Breadcrumbs
            $this->initBreadcrumbs();
            $this->breadcrumbs->addItem(
                $translated->trans('breadcrumb.alert.action.edit', array(), 'breadcrumbs').' '.ucfirst($webSearch->getTitle())
            );
            // --

            // SEO
            $canonicalLink = $this->get('router')->generate('viteloge_frontend_websearch_edit', array('id' => $webSearch->getId()), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.frontend.websearch.edit.title'))
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'description', $translated->trans('viteloge.frontend.websearch.edit.description'))
                ->addMeta('property', 'og:title', $translated->trans('viteloge.frontend.websearch.edit.title'))
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $translated->trans('viteloge.frontend.websearch.edit.description'))
            ;
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
                    $translated->trans('websearch.flash.updated', array('%title%' => $webSearch->getTitle()))
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
        private function createDeleteForm(WebSearch $webSearch, $forceDeleteForm=false) {
            $id = $webSearch->getId();
            $isMailEnabled = $webSearch->getUserSearch()->isMailEnabled();
            $method = ($isMailEnabled && !$forceDeleteForm) ? 'GET' : 'DELETE';
            $action = ($isMailEnabled && !$forceDeleteForm) ? 'viteloge_frontend_websearch_remove' : 'viteloge_frontend_websearch_delete';
            return $this->createFormBuilder()
                ->setAction($this->generateUrl($action, array('id' => $id)))
                ->setMethod($method)
                ->add('submit', 'submit', array('label' => 'websearch.action.delete'))
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
            $translated = $this->get('translator');

            // Breadcrumbs
            $this->initBreadcrumbs();
            $this->breadcrumbs->addItem(
                $translated->trans('breadcrumb.alert.action.remove', array(), 'breadcrumbs').' '.ucfirst($webSearch->getTitle())
            );
            // --

            // SEO
            $canonicalLink = $this->get('router')->generate($request->get('_route'), array('id' => $webSearch->getId()), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.frontend.websearch.remove.title'))
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'description', $translated->trans('viteloge.frontend.websearch.remove.description'))
                ->addMeta('property', 'og:title', $translated->trans('viteloge.frontend.websearch.remove.title'))
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $translated->trans('viteloge.frontend.websearch.remove.description'))
            ;
            // --

            $form = $this->createDeleteForm($webSearch, true);
            $userSearch = $webSearch->getUserSearch();
            $userSearchForm = $this->createFormBuilder()
                ->setAction($this->generateUrl('viteloge_frontend_usersearch_delete', array('id' => $userSearch->getId())))
                ->setMethod('DELETE')
                ->add('submit', 'submit', array('label' => 'usersearch.action.delete'))
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
         * @Template("VitelogeFrontendBundle:WebSearch:delete.html.twig")
         */
        public function deleteAction(Request $request, WebSearch $webSearch) {
            $translated = $this->get('translator');
            $webSearch->getUserSearch()->setDeletedAt(new \DateTime('now')); // desactivate userSearch in order to remove websearch

            $form = $this->createDeleteForm($webSearch);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($webSearch);
                $em->flush();
                $this->addFlash(
                    'notice',
                    $translated->trans('websearch.flash.removed', array('%title%' => $webSearch->getTitle()))
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
                ->add('submit', 'submit', array('label' => 'websearch.action.activate'))
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
         * @ParamConverter("webSearch", class="VitelogeCoreBundle:WebSearch", options={
         *    "repository_method" = "findHistory",
         *    "mapping": {"id": "id"},
         *    "map_method_signature" = true
         * })
         * Template("VitelogeFrontendBundle:WebSearch:activate.html.twig")
         */
        public function activateAction(Request $request, WebSearch $webSearch) {
            $translated = $this->get('translator');

            $form = $this->createActivateForm($webSearch);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $webSearch->setDeletedAt(null);
                $webSearch->getUserSearch()->setDeletedAt(null);
                $em = $this->getDoctrine()->getManager();
                $em->persist($webSearch);
                $em->flush();
                $this->addFlash(
                    'notice',
                    $translated->trans('websearch.flash.activated', array('%title%' => $webSearch->getTitle()))
                );
            }

            return $this->redirectToRoute('viteloge_frontend_websearch_list');
        }

    }

}
