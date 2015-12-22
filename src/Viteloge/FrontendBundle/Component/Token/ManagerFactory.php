<?php

namespace Viteloge\FrontendBundle\Component\Token {

    use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
    use Viteloge\CoreBundle\Entity\User;

    class ManagerFactory {

        public static function getManager(TokenStorageInterface $tokenStorage, $secret) {
            $user = $tokenStorage->getToken()->getUser();
            if (!$user instanceof User) {
                $user = null;
            }
            return new Manager($secret, $user);
        }
    }

}
