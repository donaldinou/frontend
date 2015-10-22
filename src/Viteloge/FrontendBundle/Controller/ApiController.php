<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Form\FormError;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Viteloge\FrontendBundle\Entity\Api;
    use Viteloge\FrontendBundle\Form\Type\ApiType;
    use Viteloge\CoreBundle\SearchEntity\Ad as AdSearch;

    /**
     * Api controller.
     *
     * @Route("/api")
     */
    class ApiController extends Controller {

        /**
         * Creates a form to create a Api entity.
         *
         * @param Api $api The entity
         * @return \Symfony\Component\Form\Form The form
         */
        private function createCreateForm(Api $api) {
            return $this->createForm(
                'viteloge_frontend_api',
                $api,
                array(
                    'action' => $this->generateUrl('viteloge_frontend_api_create'),
                    'method' => 'POST'
                )
            );
        }

        /**
         * Displays a form to create a new Api entity.
         *
         * @Route(
         *      "/new",
         *      name="viteloge_frontend_api_new"
         * )
         * @Method("GET")
         * @Template("VitelogeFrontendBundle:Api:new.html.twig")
         */
        public function newAction(Request $request) {
            $api = new Api();
            $form = $this->createCreateForm($api);

            return array(
                'api' => $api,
                'form' => $form->createView(),
            );
        }

        /**
         * Creates a new Api entity.
         *
         * @Route(
         *      "/",
         *      name="viteloge_frontend_api_create"
         * )
         * @Method("POST")
         * @Template("VitelogeFrontendBundle:Api:new.html.twig")
         */
        public function createAction(Request $request) {
            $trans = $this->get('translator');
            $api = new Api();
            $form = $this->createCreateForm($api);
            $form->handleRequest($request);

            if ($form->isValid()) {
                // just display the form
            }

            return array(
                'api' => $api,
                'form' => $form->createView(),
            );
        }

        /**
         * Show included form research
         *
         * @Route(
         *      "/show/{id}",
         *      requirements={
         *         "id"="(?:2[a|b|A|B])?0{0,2}\d+"
         *      },
         *      name="viteloge_frontend_api_show"
         * )
         * @Method("GET")
         * @ParamConverter("inseeCity", class="AcreatInseeBundle:InseeCity", options={"id" = "id"})
         * @Template("VitelogeFrontendBundle:Api:show.html.twig")
         */
        public function showAction(Request $request, InseeCity $inseeCity) {
            $adSearch = new AdSearch();
            $adSearch->setWhere(array($inseeCity));
            $form = $this->createForm('viteloge_core_adsearch', $adSearch);
            return array(
                'inseeCity' => $inseeCity,
                'form' => $form->createView()
            );
        }

        /**
         * Show included form research like the legacy api
         *
         * @Route(
         *      "/legacy/{id}",
         *      requirements={
         *         "id"="(?:2[a|b|A|B])?0{0,2}\d+"
         *      },
         *      name="viteloge_frontend_api_legacy"
         * )
         * @Method("GET")
         * @ParamConverter("inseeCity", class="AcreatInseeBundle:InseeCity", options={"id" = "id"})
         * @Template("VitelogeFrontendBundle:Api:legacy.html.twig")
         */
        public function legacyAction(Request $request, InseeCity $inseeCity) {
            $adSearch = new AdSearch();
            $adSearch->setWhere(array($inseeCity));
            $form = $this->createForm('viteloge_core_adsearch', $adSearch);
            return array(
                'inseeCity' => $inseeCity,
                'form' => $form->createView()
            );
        }

    }


}
