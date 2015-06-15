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
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use FOS\UserBundle\FOSUserEvents;
    use FOS\UserBundle\Event\FormEvent;
    use FOS\UserBundle\Event\GetResponseUserEvent;
    use FOS\UserBundle\Event\FilterUserResponseEvent;

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
            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem('Home', $this->get('router')->generate('viteloge_frontend_homepage'));
            $breadcrumbs->addItem('User');

            return array(

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

    }

}
