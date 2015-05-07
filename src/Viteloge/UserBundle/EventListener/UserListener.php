<?php

namespace Viteloge\UserBundle\EventListener {

    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use FOS\UserBundle\FOSUserEvents;
    use FOS\UserBundle\Event\FormEvent;
    use FOS\UserBundle\Event\GetResponseUserEvent;
    use FOS\UserBundle\Event\UserEvent;
    use FOS\UserBundle\Event\FilterUserResponseEvent;
    use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

    /**
     *
     */
    class UserListener implements EventSubscriberInterface {

        private $breadcrumbsService;

        private $router;

        public function __construct( Breadcrumbs $breadcrumbsService, UrlGeneratorInterface $router ) {
            $this->breadcrumbsService = $breadcrumbsService;
            $this->router = $router;
        }

        /**
         * {@inheritDoc}
         */
        public static function getSubscribedEvents() {
            return array(
                FOSUserEvents::PROFILE_EDIT_INITIALIZE => 'onProfileEditing',
                //FOSUserEvents::PROFILE_EDIT_SUCCESS => 'onProfileEditing',
                //FOSUserEvents::PROFILE_EDIT_COMPLETED => 'onProfileEditing',
                FOSUserEvents::REGISTRATION_INITIALIZE => 'onRegistrating',
                //FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrating',
                //FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrating',
                FOSUserEvents::CHANGE_PASSWORD_INITIALIZE => 'onPasswordChanging',
                //FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onPasswordSuccess',
                FOSUserEvents::RESETTING_RESET_INITIALIZE => 'onResetting',
                FOSUserEvents::RESETTING_RESET_SUCCESS => 'onResettingSucceed',
                FOSUserEvents::RESETTING_RESET_COMPLETED => 'onResettingCompleted'
            );
        }

        /**
         *
         */
        public function onProfileEditing(GetResponseUserEvent $event) {
            $this->breadcrumbsService->addItem('Home', $this->router->generate('viteloge_frontend_homepage'));
            $this->breadcrumbsService->addItem('User', $this->router->generate('viteloge_frontend_user_index'));
            $this->breadcrumbsService->addItem('Profile', $this->router->generate('fos_user_profile_show'));
            $this->breadcrumbsService->addItem('Edit');
        }

        /**
         *
         */
        public function onRegistrating(UserEvent $event) {
            $this->breadcrumbsService->addItem('Home', $this->router->generate('viteloge_frontend_homepage'));
            $this->breadcrumbsService->addItem('User', $this->router->generate('viteloge_frontend_user_index'));
            $this->breadcrumbsService->addItem('Registration');
        }

        /**
         *
         */
        public function onPasswordChanging(GetResponseUserEvent $event) {
            $this->breadcrumbsService->addItem('Home', $this->router->generate('viteloge_frontend_homepage'));
            $this->breadcrumbsService->addItem('User', $this->router->generate('viteloge_frontend_user_index'));
            $this->breadcrumbsService->addItem('Profile', $this->router->generate('fos_user_profile_show'));
            $this->breadcrumbsService->addItem('Change Password');
        }

        /**
         *
         */
        public function onResetting(GetResponseUserEvent $event) {
            $this->breadcrumbsService->addItem('Home', $this->router->generate('viteloge_frontend_homepage'));
            $this->breadcrumbsService->addItem('User', $this->router->generate('viteloge_frontend_user_index'));
            $this->breadcrumbsService->addItem('Password');
        }

        /**
         *
         */
        public function onResettingSucceed(FormEvent $event) {
            $this->breadcrumbsService->addItem('Home', $this->router->generate('viteloge_frontend_homepage'));
            $this->breadcrumbsService->addItem('User', $this->router->generate('viteloge_frontend_user_index'));
            $this->breadcrumbsService->addItem('Password');
        }

        /**
         *
         */
        public function onResettingCompleted(FilterUserResponseEvent $event) {
            $this->breadcrumbsService->addItem('Home', $this->router->generate('viteloge_frontend_homepage'));
            $this->breadcrumbsService->addItem('User', $this->router->generate('viteloge_frontend_user_index'));
            $this->breadcrumbsService->addItem('Password');
        }
    }

}
