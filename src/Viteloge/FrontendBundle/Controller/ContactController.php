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
    use Viteloge\FrontendBundle\Entity\Contact;
    use Viteloge\FrontendBundle\Form\Type\ContactType;
    use Viteloge\CoreBundle\Entity\User;


    /**
     * Contact controller.
     *
     * @Route("/contact")
     */
    class ContactController extends Controller {

        /**
         * Creates a form to create a Message entity.
         *
         * @param Contact $contact The entity
         * @return \Symfony\Component\Form\Form The form
         */
        private function createCreateForm(Contact $contact) {
            return $this->createForm(
                'viteloge_frontend_contact',
                $contact,
                array(
                    'action' => $this->generateUrl('viteloge_frontend_contact_create'),
                    'method' => 'GET'
                )
            );
        }

        /**
         * Displays a form to create a new Contact entity.
         * Private cache for the user
         *
         * @Route(
         *      "/new",
         *      name="viteloge_frontend_contact_new"
         * )
         * @Cache(expires="tomorrow", public=false)
         * @Method("GET")
         * @Template("VitelogeFrontendBundle:Contact:new.html.twig")
         */
        public function newAction(Request $request) {
            $contact = new Contact();
            $contact->setUser($this->getUser());
            $form = $this->createCreateForm($contact);

            return array(
                'contact' => $contact,
                'form' => $form->createView(),
            );
        }

        /**
         * Creates a new Contact entity.
         * No cache for a post
         *
         * @Route(
         *      "/",
         *      name="viteloge_frontend_contact_create"
         * )
         * @Method("GET")
         * @Template("VitelogeFrontendBundle:Contact:new.html.twig")
         */
        public function createAction(Request $request) {
            $em = $this->getDoctrine()->getManager();
            $trans = $this->get('translator');
            $contact = new Contact();
            $contact->setUser($this->getUser());
            $form = $this->createCreateForm($contact);
            $form->handleRequest($request);

            if ($form->isValid()) {

                // TODO : use a doctrine listener instead
                //si c'est vide on verifie quand même si le compte existe, sinon on le crée
          /*      if(empty($contact->getUser())){
                    //si on crée le compte on envoi un mail avec le mdp
                    $newuser = $this->get('viteloge_frontend_generate.user')->generate($contact);
                    $contact->setUser($newuser);
                    $inscription = $this->inscriptionMessage($newuser);
                }
*/


                    $forbiddenUA = array(
                        'yakaz_bot' => 'YakazBot/1.0',
                        'mitula_bot' => 'java/1.6.0_26'
                    );
                    $forbiddenIP = array(

                    );
                    $ua = $request->headers->get('User-Agent');
                    $ip = $request->getClientIp();

                    // log redirect
                    if (!in_array($ua, $forbiddenUA) && !in_array($ip, $forbiddenIP)) {
                        $now = new \DateTime('now');
                        $contact->setIp($ip);
                        $contact->setUa($ua);
                        $newuser = $em->getRepository('VitelogeCoreBundle:User')->FindOneBy(array('email'=>$contact->getEmail()));
                        $contact->setUser($newuser);
                        //TODO trouver pourquoi
                        //il y a un bug avec le id celui du user et ajouter à la place d'un id auto si on stoche le user surement une mauvaise relation dans l'entité
                        //desactivé dans l'entity
                        $em->persist($contact);
                        $em->flush();
                    $result = $this->sendMessage($contact);
                    return $this->redirect($this->generateUrl('viteloge_frontend_contact_success', array()));
                }
                $form->addError(new FormError($trans->trans('contact.send.error')));
            }

            return array(
                'contact' => $contact,
                'form' => $form->createView(),
            );
        }

        /**
         *
         */
        protected function sendMessage(Contact $contact) {
            $trans = $this->get('translator');
            $from = array(
                $contact->getEmail() => $contact->getFullname()
            );
            $mail = \Swift_Message::newInstance()
                ->setSubject($trans->trans('Demande de contact via le site Viteloge.com'))
                ->setFrom($from)
                ->setTo('contact@viteloge.com')
                ->setBody(
                    $this->renderView(
                        'VitelogeFrontendBundle:Contact:email/contact.html.twig',
                        array(
                            'contact' => $contact
                        )
                    ),
                    'text/html'
                )
            ;
            return $this->get('mailer')->send($mail);
        }

        /**
         * Success contact
         * Private cache because of user informations
         *
         * @Route(
         *      "/success",
         *      name="viteloge_frontend_contact_success"
         * )
         * @Cache(expires="tomorrow", public=false)
         * @Method("GET")
         * @Template("VitelogeFrontendBundle:Contact:success.html.twig")
         */
        public function successAction() {
            $contact = null;
            return array(
                'contact' => $contact
            );
        }

        /**
         * Fail contact
         * Private cache because of user information
         *
         * @Route(
         *      "/fail",
         *      name="viteloge_frontend_contact_fail"
         * )
         * @Cache(expires="tomorrow", public=false)
         * @Method("GET")
         * @Template("VitelogeFrontendBundle:Contact:fail.html.twig")
         */
        public function failAction() {
            $contact = null;
            return array(
                'contact' => $contact
            );
        }

    }


}
