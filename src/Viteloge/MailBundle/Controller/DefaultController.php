<?php

namespace Viteloge\MailBundle\Controller {

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    class DefaultController extends Controller {

        public function indexAction($name) {
            return $this->render('VitelogeMailBundle:Default:index.html.twig', array());
        }

    }

}
