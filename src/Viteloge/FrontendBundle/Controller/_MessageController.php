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
    use Symfony\Component\Serializer\Serializer;
    use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
    use Symfony\Component\Serializer\Encoder\JsonEncoder;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Acreat\InseeBundle\Entity\InseeDepartment;
    use Acreat\InseeBundle\Entity\InseeState;
    use Viteloge\CoreBundle\Entity\Ad;
    use Viteloge\CoreBundle\Entity\QueryStats;
    use Viteloge\CoreBundle\Entity\UserSearch;
    use Viteloge\CoreBundle\Component\DBAL\EnumTransactionType;
    use Viteloge\CoreBundle\SearchEntity\Ad as AdSearch;

    /**
     * @Route("/message")
     */
    class MessageController extends Controller {

        /**
         *
         */
        private function createContactForm() {
            return $form;
        }

        /**
         * @Route(
         *      "/{ad}",
         *      requirements={
         *         "ad"="\d+"
         *      },
         *      name = "viteloge_frontend_ad_contact"
         * )
         * @Method({"GET"})
         * @ParamConverter("ad", class="VitelogeCoreBundle:Ad", options={"ad" = "ad"})
         */
        public function newAction(Request $request, Ad $ad) {
            return array(

            );
        }

        /**
         * @Route(
         *      "/message/{id}",
         *      requirements={
         *         "id"="\d+"
         *      },
         *      name = "viteloge_frontend_ad_contact"
         * )
         * @Method({"POST"})
         * @ParamConverter("ad", class="VitelogeCoreBundle:Ad", options={"id" = "id"})
         */
        public function messageAction(Request $request, Ad $ad) {
            $contact = new Contact();
            $contact->setUser($this->getUser());
            $form = '';
            if ($this->isXmlHttpRequest()) {
                if ($form->isValid()) {
                    $this->sendMessage();
                    $this->addFlash(
                        'notice',
                        'Your message has been send!'
                    );
                }
            }
            return array(
                'form' => $form,
                'status' => $status
            );
        }

        /**
         *
         */
        public function sendMessage($arg1, $arg2) {

        }

        /**
         * @Route(
         *      "/redirect/{id}",
         *      requirements={
         *          "id"="\d+"
         *      }
         * )
         * @Method({"GET"})
         * @ParamConverter("ad", class="VitelogeCoreBundle:Ad", options={"id" = "id"})
         * @Template("VitelogeFrontendBundle:Ad:redirect.html.twig")
         */
        public function redirectAction(Request $request, Ad $ad) {
            return array(
                'ad' => $ad
            );
        }

    }

}
