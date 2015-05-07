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

    /**
     *
     */
    class ServiceController extends Controller {

        private $services;

        protected function initEstimateService() {
            if (!isset($this->services['estimate'])) {
                $service = new Service();
                $service->setName('estimate');
                $service->setImage('bundles/frontendbundle/images/service/estimate.jpg');
                $service->setIcon('icon-icon-calculator');
                $service->setTitle('Estimate my property');
                $service->setUrl($this->generateUrl('viteloge_estimation_default_index'));
                $service->setDescription('Aenean sollicitudin, lorem quis bibendum auctor');
                $this->services['estimate'] = $service;
            }
            return $this;
        }

        protected function initPublishService() {
            if (!isset($this->services['publish'])) {
                $service = new Service();
                $service->setName('publish');
                $service->setImage('bundles/frontendbundle/images/service/publish.jpg');
                $service->setIcon('icon-icon-publish');
                $service->setTitle('Publish my ads');
                $service->setUrl($this->generateUrl('viteloge_frontend_static_register'));
                $service->setDescription('Aenean sollicitudin, lorem quis bibendum auctor');
                $this->services['publish'] = $service;
            }
            return $this;
        }

        protected function initMailService() {
            if (!isset($this->services['mail'])) {
                $service = new Service();
                $service->setName('mail');
                $service->setImage('bundles/frontendbundle/images/service/mail.jpg');
                $service->setIcon('icon-icon-alert');
                $service->setTitle('Create my mail alert');
                $service->setUrl($this->generateUrl('viteloge_frontend_static_subscribe'));
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
