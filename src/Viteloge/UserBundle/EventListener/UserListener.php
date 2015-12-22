<?php

namespace Viteloge\UserBundle\EventListener {

    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\Routing\RouterInterface as Router;
    use Symfony\Component\Translation\TranslatorInterface;
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

        private $breadcrumbs;

        private $router;

        private $translated;

        public function __construct( Breadcrumbs $breadcrumbs, Router $router, TranslatorInterface $translated) {
            $this->breadcrumbs = $breadcrumbs;
            $this->router = $router;
            $this->translated = $translated;
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
                //FOSUserEvents::RESETTING_RESET_SUCCESS => 'onResettingSucceed',
                //FOSUserEvents::RESETTING_RESET_COMPLETED => 'onResettingCompleted'
            );
        }

        /**
         *
         */
        public function onProfileEditing(GetResponseUserEvent $event) {
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->router->generate('viteloge_frontend_homepage')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.user', array(), 'breadcrumbs'),
                $this->router->generate('viteloge_frontend_user_index')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.profile', array(), 'breadcrumbs'),
                $this->router->generate('fos_user_profile_show')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.profile.edit', array(), 'breadcrumbs')
            );
        }

        /**
         *
         */
        public function onRegistrating(UserEvent $event) {
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->router->generate('viteloge_frontend_homepage')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.user', array(), 'breadcrumbs'),
                $this->router->generate('viteloge_frontend_user_index')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.registration', array(), 'breadcrumbs')
            );
        }

        /**
         *
         */
        public function onPasswordChanging(GetResponseUserEvent $event) {
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->router->generate('viteloge_frontend_homepage')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.user', array(), 'breadcrumbs'),
                $this->router->generate('viteloge_frontend_user_index')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.profile', array(), 'breadcrumbs'),
                $this->router->generate('fos_user_profile_show')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.profile.changepassword', array(), 'breadcrumbs')
            );
        }

        /**
         *
         */
        public function onPasswordSuccess(FormEvent $event) {
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->router->generate('viteloge_frontend_homepage')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.user', array(), 'breadcrumbs'),
                $this->router->generate('viteloge_frontend_user_index')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.profile', array(), 'breadcrumbs'),
                $this->router->generate('fos_user_profile_show')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.profile.changepassword', array(), 'breadcrumbs')
            );
        }

        /**
         *
         */
        public function onResetting(GetResponseUserEvent $event) {
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->router->generate('viteloge_frontend_homepage')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.user', array(), 'breadcrumbs'),
                $this->router->generate('viteloge_frontend_user_index')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.password', array(), 'breadcrumbs')
            );
        }

        /**
         *
         */
        public function onResettingSucceed(FormEvent $event) {
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->router->generate('viteloge_frontend_homepage')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.user', array(), 'breadcrumbs'),
                $this->router->generate('viteloge_frontend_user_index')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.password', array(), 'breadcrumbs')
            );
        }

        /**
         *
         */
        public function onResettingCompleted(FilterUserResponseEvent $event) {
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->router->generate('viteloge_frontend_homepage')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.user', array(), 'breadcrumbs'),
                $this->router->generate('viteloge_frontend_user_index')
            );
            $this->breadcrumbs->addItem(
                $this->translated->trans('breadcrumb.password', array(), 'breadcrumbs')
            );
        }
    }

}
