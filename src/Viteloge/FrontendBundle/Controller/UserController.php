<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

    }

}
