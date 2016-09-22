<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\Security\Core\Exception\AccessDeniedException;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use FOS\UserBundle\FOSUserEvents;
    use FOS\UserBundle\Event\FormEvent;
    use FOS\UserBundle\Event\GetResponseUserEvent;
    use FOS\UserBundle\Event\FilterUserResponseEvent;
    use Viteloge\CoreBundle\Entity\User;
    use Viteloge\CoreBundle\SearchEntity\Ad as AdSearch;

    /**
     * @Route("/user")
     */
    class UserController extends Controller {

        /**
         * @Route("/")
         * @Method({"GET"})
         * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
         * @Template("VitelogeFrontendBundle:User:index.html.twig")
         */
        public function indexAction( Request $request ) {
            $translated = $this->get('translator');

            // Breadcrumbs
            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem($translated->trans('breadcrumb.home', array(), 'breadcrumbs'), $this->get('router')->generate('viteloge_frontend_homepage'));
            $breadcrumbs->addItem($translated->trans('breadcrumb.user', array(), 'breadcrumbs'));
            // --

            // SEO
            $canonicalLink = $this->get('router')->generate(
                $request->get('_route'),
                $request->get('_route_params'),
                true
            );
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.frontend.user.index.title'))
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'description', $translated->trans('viteloge.frontend.user.index.description'))
                ->addMeta('property', 'og:title', $seoPage->getTitle())
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $translated->trans('viteloge.frontend.user.index.description'))
            ;
            // --
            $adSearch = new AdSearch();
            $adSearch->handleRequest($request);
            $form = $this->createForm('viteloge_core_adsearch', $adSearch);

            return array(
                'form' => $form->createView(),
            );
        }

        /**
         * @Route("/registerModal")
         * @Method({"GET"})
         * @see RegisterController::registerAction
         * @Template("VitelogeFrontendBundle:User:registermodal.html.twig")
         */
        public function registerModalAction(Request $request) {
            $formFactory = $this->get('fos_user.registration.form.factory');
            $userManager = $this->get('fos_user.user_manager');
            $dispatcher = $this->get('event_dispatcher');

            $user = $userManager->createUser();
            $user->setEnabled(true);

            $event = new GetResponseUserEvent($user, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }

            $form = $formFactory->createForm();
            $form->setData($user);
            $form->handleRequest($request);

            return array(
                'form' => $form->createView(),
            );
        }

        /**
         * This is a legacy disabling fork
         * @Route(
         *     "/disableMail/{token}/{info}",
         *     name="viteloge_frontend_user_disablemail"
         * )
         * @Method({"GET", "POST"})
         */
        public function disableMailAction(Request $request, $token, $info) {
            $translated = $this->get('translator');
            $data = json_decode( base64_decode( strtr( $info, '-_', '+/' ) ), true );

            if (empty($data) || empty($data['id'])) {
                throw $this->createNotFoundException();
            }

            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->findUserBy(
                array( 'id' => $data['id'] )
            );

            if (!$user instanceof User) {
                throw $this->createNotFoundException();
            }

            $newTokenManager = $this->get('viteloge_frontend.mail_token_manager');
            $oldTokenManager = $this->get('viteloge_frontend.old_token_manager');
            $newTokenManager->setUser($user)->hash();
            $oldTokenManager->setUser($user)->hash();

            if (!$newTokenManager->isTokenValid($token) && !$oldTokenManager->isTokenValid($token)) {
                throw $this->createNotFoundException();
            }

            $user->setInternalMailDisabled(true);
            $userManager->updateUser($user);

            $this->addFlash(
                'success',
                $translated->trans('user.flash.internalmaildisabled')
            );

            return $this->redirectToRoute('viteloge_frontend_homepage');
        }

        /**
         * This is a legacy disabling fork
         * @Route(
         *     "/disablePartnerContact/{token}/{info}",
         *     name="viteloge_frontend_user_disablepartnercontact"
         * )
         * @Method({"GET", "POST"})
         */
        public function disablePartnerContactAction(Request $request, $token, $info) {
            $translated = $this->get('translator');
            $data = json_decode( base64_decode( strtr( $info, '-_', '+/' ) ), true );

            if (empty($data) || empty($data['id'])) {
                throw new AccessDeniedException();
            }

            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->findUserBy(
                array( 'id' => $data['id'] )
            );

            if (!$user instanceof User) {
                throw new AccessDeniedException();
            }

            $newTokenManager = $this->get('viteloge_frontend.mail_token_manager');
            $oldTokenManager = $this->get('viteloge_frontend.old_token_manager');
            $newTokenManager->setUser($user)->hash();
            $oldTokenManager->setUser($user)->hash();

            if (!$newTokenManager->isTokenValid($token) && !$oldTokenManager->isTokenValid($token)) {
                throw $this->createNotFoundException();
            }

            $user->setPartnerContactEnabled(false);
            $userManager->updateUser($user);

            $this->addFlash(
                'success',
                $translated->trans('user.flash.partnercontactdisabled')
            );

            return $this->redirectToRoute('viteloge_frontend_homepage');
        }

    }

}
