<?php

namespace Viteloge\FrontendBundle\Component\Token {

    use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

    class ManagerFactory {

        public static function getManager(TokenStorageInterface $tokenStorage, $secret) {
            $user = $tokenStorage->getToken()->getUser();
            return new Manager($secret, $user);
        }
    }

}
