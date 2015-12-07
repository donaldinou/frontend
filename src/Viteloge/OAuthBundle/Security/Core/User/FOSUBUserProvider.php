<?php

namespace Viteloge\OAuthBundle\Security\Core\User {

    use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;
    use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
    use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseProvider;
    use Symfony\Component\Security\Core\User\UserInterface;

    class FOSUBUserProvider extends BaseProvider {

        /**
         * {@inheritdoc}
         */
        public function loadUserByOAuthUserResponse(UserResponseInterface $response) {
            $username = $response->getUsername();
            if ($username === null) {
                throw new AccountNotLinkedException(sprintf("User '%s' not found.", $username));
            }
            $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
            if ($user === null) {
                $service = $response->getResourceOwner()->getName();
                $email = $response->getEmail();
                if ($email === null) {
                    throw new AccountNotLinkedException(sprintf("User '%s' not found.", $username));
                }
                $user = $this->userManager->findUserByUsernameOrEmail($email);
                if (!$user instanceof UserInterface) {
                    $user = $this->userManager->createUser();
                    $user->setUsername($email);
                    $user->setPlainPassword($username);
                    $user->setEmail($email);
                    $user->setEnabled(true);
                }
                $user->{'set'.ucfirst($service).'Id'}($username);
                $this->userManager->updateUser($user, true);
            }
            return $user;
        }

    }

}
