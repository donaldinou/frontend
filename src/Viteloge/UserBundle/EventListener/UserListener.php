<?php

namespace Viteloge\UserBundle\EventListener {

    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use FOS\UserBundle\FOSUserEvents;
    use FOS\UserBundle\Event\FormEvent;
    use FOS\UserBundle\Event\GetResponseUserEvent;
    use FOS\UserBundle\Event\UserEvent;
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
                //FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrating'
            );
        }

        public function onProfileEditing(GetResponseUserEvent $event) {
            $this->breadcrumbsService->addItem('Home', $this->router->generate('viteloge_frontend_homepage'));
            $this->breadcrumbsService->addItem('User', $this->router->generate('viteloge_frontend_user_index'));
            $this->breadcrumbsService->addItem('Profile');
        }

        public function onRegistrating(UserEvent $event) {
            $this->breadcrumbsService->addItem('Home', $this->router->generate('viteloge_frontend_homepage'));
            $this->breadcrumbsService->addItem('User', $this->router->generate('viteloge_frontend_user_index'));
            $this->breadcrumbsService->addItem('Registration');
        }
    }

}
