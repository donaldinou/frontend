<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Viteloge\CoreBundle\Entity\Service;

    class ServiceController extends Controller {

        private $services;

        protected function initEstimateService() {
            if (!isset($this->services['estimate'])) {
                $service = new Service();
                $service->setName('estimate');
                $service->setImage('bundles/frontendbundle/images/home/estimate.jpg');
                $service->setIcon('');
                $service->setTitle('Estimate my property');
                $service->setDescription('Aenean sollicitudin, lorem quis bibendum auctor');
                $this->services['estimate'] = $service;
            }
            return $this;
        }

        protected function initPublishService() {
            if (!isset($this->services['publish'])) {
                $service = new Service();
                $service->setName('publish');
                $service->setImage('bundles/frontendbundle/images/home/publish.jpg');
                $service->setIcon('');
                $service->setTitle('Publish my ads');
                $service->setDescription('Aenean sollicitudin, lorem quis bibendum auctor');
                $this->services['publish'] = $service;
            }
            return $this;
        }

        protected function initMailService() {
            if (!isset($this->services['mail'])) {
                $service = new Service();
                $service->setName('mail');
                $service->setImage('bundles/frontendbundle/images/home/mail.jpg');
                $service->setIcon('');
                $service->setTitle('Create my mail alert');
                $service->setDescription('Aenean sollicitudin, lorem quis bibendum auctor');
                $this->services['mail'] = $service;
            }
            return $this;
        }

        protected function initServices() {
            $this->initEstimateService()
                ->initPublishService()
                ->initMailService();
            return $this;
        }

        /**
         * @Cache(expires="tomorrow", public=true)
         */
        public function listAction(Request $request) {
            $this->initServices();
            return $this->render(
                'VitelogeFrontendBundle:Service:list.html.twig',
                array(
                    'services' => $this->services
                )
            );
        }
    }

}
