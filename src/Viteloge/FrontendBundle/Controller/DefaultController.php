<?php

namespace Viteloge\FrontendBundle\Controller {

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    class DefaultController extends Controller {

        /**
         *
         */
        public function indexAction() {
            return $this->render(
                'VitelogeFrontendBundle:Default:index.html.twig',
                array()
            );
        }

    }

}
