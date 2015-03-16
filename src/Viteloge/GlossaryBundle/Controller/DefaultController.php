<?php

namespace Viteloge\GlossaryBundle\Controller {

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    class DefaultController extends Controller {

        /**
         *
         */
        public function indexAction($name) {
            return $this->render(
                'VitelogeGlossaryBundle:Default:index.html.twig',
                array()
            );
        }

        /**
         *
         */
        public function lastAction() {
            $glossary = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:Search')
                ->findAll();
            return $this->render(
                'VitelogeGlossaryBundle:Default:last.html.twig'
            );
        }
    }

}
