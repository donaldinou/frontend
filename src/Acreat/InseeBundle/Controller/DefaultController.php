<?php

namespace Acreat\InseeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AcreatInseeBundle:Default:index.html.twig', array('name' => $name));
    }
}
