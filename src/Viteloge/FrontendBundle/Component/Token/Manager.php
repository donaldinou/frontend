<?php

namespace Viteloge\FrontendBundle\Component\Token {

    use Viteloge\CoreBundle\Entity\User;

    /**
     * Use for legacy disable mail function
     */
    class Manager {

        /**
         *
         */
        protected $secret;

        /**
         *
         */
        protected $user;

        /**
         *
         */
        protected $operation;

        /**
         *
         */
        protected $hash;

        /**
         *
         */
        public function __construct($secret, User $user=null, $operation='disable') {
            if (!is_string($secret)) {
                throw new \InvalidArgumentException();
            }
            if (!is_string($operation)) {
                throw new \InvalidArgumentException();
            }
            $this->secret = $secret;
            $this->user = $user;
            $this->operation = $operation;
        }

        /**
         * public because of legacy
         */
        public function hash() {
            if (!$this->getUser() instanceof User) {
                throw new \LogicException();
            }
            $this->hash = hash('sha512', implode(':', array( $this->getUser()->getId(), $this->getOperation(), $this->secret )));
            return $this;
        }

        /**
         * public because of legacy
         */
        public function hashByMail() {
            if (!$this->getUser() instanceof User) {
                throw new \LogicException();
            }
            $this->hash = hash('sha512', implode(':', array( $this->getUser()->getEmail(), $this->getOperation(), $this->secret )));
            return $this;
        }

        /**
         * public because of legacy
         */
        public function hashBy($key) {
            if (!$this->getUser() instanceof User) {
                throw new \LogicException();
            }
            $this->hash = hash('sha512', implode(':', array( $key, $this->getOperation(), $this->secret )));
            return $this;
        }

        /**
         * @return boolean
         */
        public function isTokenValid($token) {
            $result = false;
            $hashLength = strlen($this->getHash());
            $tokenLength = strlen($token);
            if ($tokenLength<$hashLength && substr( $this->getHash(), 0, $tokenLength ) == $token) {
                $result = true;
            }
            return $result;
        }

        /**
         *
         */
        public function setUser(User $user) {
            $this->user = $user;
            return $this;
        }

        /**
         *
         */
        public function getUser() {
            return $this->user;
        }

        /**
         *
         */
        public function setOperation($operation) {
            if (!is_string($operation)) {
                throw new \InvalidArgumentException();
            }
            $this->operation = $operation;
            return $this;
        }

        /**
         *
         */
        public function getOperation() {
            return $this->operation;
        }

        /**
         *
         */
        public function getHash() {
            return $this->hash;
        }

    }

}
