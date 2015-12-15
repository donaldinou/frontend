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
     * @Route("/service")
     */
    class ServiceController extends Controller {

        /**
         *
         */
        private $services;

        /**
         *
         */
        protected function initEstimateService() {
            if (!isset($this->services['estimate'])) {
                $service = new Service();
                $service->setName('estimate');
                $service->setImage('bundles/vitelogefrontend/images/service/estimate.jpg');
                $service->setIcon('icon-icon-calculator');
                $service->setTitle('service.estimate');
                $service->setUrl($this->generateUrl('viteloge_estimation_default_index'));
                $service->setDescription('service.estimate.description');
                $this->services['estimate'] = $service;
            }
            return $this;
        }

        /**
         *
         */
        protected function initPublishService() {
            if (!isset($this->services['publish'])) {
                $service = new Service();
                $service->setName('publish');
                $service->setImage('bundles/vitelogefrontend/images/service/publish.jpg');
                $service->setIcon('icon-icon-publish');
                $service->setTitle('service.publish');
                $service->setUrl($this->generateUrl('viteloge_frontend_static_register'));
                $service->setDescription('service.publish.description');
                $this->services['publish'] = $service;
            }
            return $this;
        }

        /**
         *
         */
        protected function initMailService() {
            if (!isset($this->services['mail'])) {
                $service = new Service();
                $service->setName('mail');
                $service->setImage('bundles/vitelogefrontend/images/service/mail.jpg');
                $service->setIcon('icon-icon-alert');
                $service->setTitle('service.alert');
                $service->setUrl($this->generateUrl('viteloge_frontend_static_subscribe'));
                $service->setDescription('service.alert.description');
                $this->services['mail'] = $service;
            }
            return $this;
        }

        /**
         *
         */
        protected function initServices() {
            $this->initEstimateService()
                ->initPublishService()
                ->initMailService();
            return $this;
        }

        /**
         * Display the list of ViteLoge services
         * Ajax and static so we could have a shared public cache
         *
         * @Route(
         *     "/list/",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "5"
         *     }
         * )
         * @Cache(smaxage="604800", maxage="604800", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Service:list.html.twig")
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
