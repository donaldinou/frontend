<?php

// TODO: This is not optimized. A fos_user service already exist. You should use it instead
namespace Viteloge\FrontendBundle\Services {

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

            if(empty($user)) {
                $userManager = $this->container->get('fos_user.user_manager');
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
                $this->em->persist($user);
                $this->em->flush();
            } else {
                $user = '';
            }

            return $user;

        }

    }

}
