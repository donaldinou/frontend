<?php
namespace Viteloge\FrontendBundle\Services;
use Viteloge\CoreBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;


class manageUser {

   private $tokenStorage;
   private $em;
   private $container;

     public function __construct(ContainerInterface $container,TokenStorageInterface $tokenStorage, EntityManager $em){
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
        $this->container = $container;
        $this->date = new \DateTime();

     }

public function generate($contact){

//on commence par vérifier si le user est en base avec son adresse mail
  $user = $this->em->getRepository('VitelogeCoreBundle:User')->FindOneBy(array('email'=>$contact->getEmail()));

 if(empty($user)){
       $user = new User();

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
        $user->setConfirmationToken($this->container->get('security.encoder_factory')->getEncoder($user)->encodePassword($password,$user->getSalt()));
        $user->addRole('ROLE_USER');

        $this->em->persist($user);
        $this->em->flush();
        $inscription = $this->inscriptionMessage($user);
 }

return $user;

}

         /**
         *
         */
        protected function inscriptionMessage(User $user) {
            $trans = $this->container->get('translator');
            $to = $user->getEmail();
            $mail = \Swift_Message::newInstance()
                ->setSubject($trans->trans('Votre compte sur viteloge.com'))
                ->setFrom('contact@viteloge.com')
                ->setTo($to)
                ->setBody(
                    $this->renderView(
                        'VitelogeFrontendBundle:Contact:email/inscription.html.twig',
                        array(
                            'user' => $user
                        )
                    ),
                    'text/html'
                )
            ;
            return $this->container->get('mailer')->send($mail);
        }

}
