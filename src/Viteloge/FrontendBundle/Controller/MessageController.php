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
    use Viteloge\CoreBundle\Entity\Ad;
    use Viteloge\FrontendBundle\Entity\Message;
    use Viteloge\FrontendBundle\Form\Type\MessageType;

    /**
     * Message controller.
     *
     * @Route("/message")
     */
    class MessageController extends Controller {

        /**
         * Creates a form to create a Message entity.
         *
         * @param Message $message The entity
         * @return \Symfony\Component\Form\Form The form
         */
        private function createCreateForm(Message $message) {
            return $this->createForm(
                'viteloge_frontend_message',
                $message,
                array(
                    'action' => $this->generateUrl('viteloge_frontend_message_create', array('ad' => $message->getAd()->getId() )),
                    'method' => 'POST'
                )
            );
        }

        /**
         * Displays a form to create a new Message entity.
         *
         * @Route(
         *      "/new/{ad}",
         *      requirements={
         *          "ad"="\d+"
         *      },
         *      name="viteloge_frontend_message_new"
         * )
         * @Method("GET")
         * @ParamConverter("ad", class="VitelogeCoreBundle:Ad", options={"ad" = "ad"})
         * @Template("VitelogeFrontendBundle:Message:new.html.twig")
         */
        public function newAction(Request $request, Ad $ad) {
            $message = new Message($ad);
            $message->setUser($this->getUser());
            $form   = $this->createCreateForm($message);

            return array(
                'message' => $message,
                'form' => $form->createView(),
            );
        }

        /**
         * Creates a new Message entity.
         *
         * @Route(
         *      "/{ad}",
         *      requirements={
         *          "ad"="\d+"
         *      },
         *      name="viteloge_frontend_message_create"
         * )
         * @Method("POST")
         * @ParamConverter("ad", class="VitelogeCoreBundle:Ad", options={"ad" = "ad"})
         * @Template("VitelogeFrontendBundle:Message:new.html.twig")
         */
        public function createAction(Request $request, Ad $ad) {
            $trans = $this->get('translator');
            $message = new Message($ad);
            $message->setUser($this->getUser());
            $form = $this->createCreateForm($message);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $result = $this->sendMessage($message);
                if ($result) {
                    return $this->redirect($this->generateUrl('viteloge_frontend_message_success', array()));
                }
                $form->addError(new FormError($trans->trans('message.send.error')));
            }

            return array(
                'message' => $message,
                'form' => $form->createView(),
            );
        }

        /**
         *
         */
        protected function sendMessage(Message $message) {
            $trans = $this->get('translator');
            $from = array(
                $message->getEmail() => $message->getFullname()
            );
            $mail = \Swift_Message::newInstance()
                ->setSubject($trans->trans('Demande de contact via le site Viteloge.com'))
                ->setFrom($from)
                ->setTo('contact@viteloge.com')
                ->setBody(
                    $this->renderView(
                        'VitelogeFrontendBundle:Message:email/message.html.twig',
                        array(
                            'message' => $message
                        )
                    ),
                    'text/html'
                )
            ;
            return $this->get('mailer')->send($mail);
        }

        /**
         * Success message
         *
         * @Route(
         *      "/success",
         *      name="viteloge_frontend_message_success"
         * )
         * @Method("GET")
         * @Template("VitelogeFrontendBundle:Message:success.html.twig")
         */
        public function successAction() {
            $message = null;
            return array(
                'message' => $message
            );
        }

        /**
         * Fail message
         *
         * @Route(
         *      "/fail",
         *      name="viteloge_frontend_message_fail"
         * )
         * @Method("GET")
         * @Template("VitelogeFrontendBundle:Message:fail.html.twig")
         */
        public function failAction() {
            $message = null;
            return array(
                'message' => $message
            );
        }

    }


}
