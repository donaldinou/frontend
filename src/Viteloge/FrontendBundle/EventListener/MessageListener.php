<?php

namespace Viteloge\FrontendBundle\EventListener {

    use Doctrine\ORM\Event\LifecycleEventArgs;
    use Symfony\Component\EventDispatcher\Event;
    use Viteloge\FrontendBundle\Entity\Message;
    use Viteloge\FrontendBundle\Entity\Contact;
    use Viteloge\CoreBundle\Entity\User;
    use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
    use Doctrine\ORM\EntityManager;
    use Symfony\Component\DependencyInjection\ContainerInterface;



    /**
     * Listener called for create user with contact and message
     */
    class MessageListener {

       private $tokenStorage;
       private $em;
       private $container;

        /**
         *
         */
        public function __construct(ContainerInterface $container,TokenStorageInterface $tokenStorage, $doctrine) {
            $this->tokenStorage = $tokenStorage;
            $this->em = $doctrine->getManager();
            $this->container = $container;
            $this->date = new \DateTime();
        }

         /**
         *
         */
        protected function inscriptionMessage(User $user) {
            $trans = $this->get('translator');
            $to = $user->getEmail();
            $mail = \Swift_Message::newInstance()
                ->setSubject($trans->trans('Votre compte sur viteloge.com'))
                ->setFrom('contact@viteloge.com')
                ->setTo($to)
                ->setBody(
                    $this->renderView(
                        'VitelogeFrontendBundle:Contact:Email/inscription.html.twig',
                        array(
                            'user' => $user
                        )
                    ),
                    'text/html'
                )
            ;
            return $this->get('mailer')->send($mail);
        }

        /**
         *
         */
        public function prePersist(LifecycleEventArgs $args) {
            $contact = $args->getEntity();

            if ($contact instanceof Message || $contact instanceof Contact) {
              $userManager = $this->container->get('fos_user.user_manager');
             // le findUserByEmail de fosuser ne fonctionne pas
              // $finduser = $userManager->findUserByEmail($contact->getUser());

               $user = $this->em->getRepository('VitelogeCoreBundle:User')->FindOneBy(array('email'=>$contact->getEmail()));

              if (null === $user) {
                $user = $userManager->createUser();
                $user->setUserName($contact->getEmail());
                $user->setPhone($contact->getPhone());
                $user->setEmail($contact->getEmail());
                $user->setEmailCanonical(strtolower($contact->getEmail()));

                $user->setFirstname($contact->getFirstname());

                $user->setLastname($contact->getLastname());
                $user->setPasswordRequestedAt($this->date);

                //on met à false il sera activé au clic sur lien d'un mail
                $user->setEnabled(false);
                $user->setLocked(false);


                $password = uniqid();
                $user->setPassword($this->container->get('security.encoder_factory')->getEncoder($user)->encodePassword($password,$user->getSalt()));
                $tokenGenerator = $this->container->get('fos_user.util.token_generator');
                $user->setConfirmationToken($tokenGenerator->generateToken());
                $user->addRole('ROLE_USER');
                $userManager->updateUser($user);
                $inscription = $this->inscriptionMessage($user);
              }

            }
        }

    }

}
