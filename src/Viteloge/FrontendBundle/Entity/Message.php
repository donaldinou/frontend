<?php

namespace Viteloge\FrontendBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;
    use Viteloge\CoreBundle\Entity\User;

    class Message {

        protected $user;

        /**
         * @Assert\Length(
         *      min = "2"
         *      max = "64"
         * )
         */
        protected $firstname;

        /**
         * @Assert\NotBlank()
         * @Assert\Length(
         *      min = "2"
         *      max = "64"
         * )
         */
        protected $lastname;

        /**
         * @Assert\NotBlank()
         * @Assert\Email(
         *      checkHost = true
         *      checkMx = true
         * )
         */
        protected $email;

        /**
         * @Assert\NotBlank()
         * @Assert\Length(
         *      min = "2"
         *      max = "64"
         * )
         */
        protected $message;

        /**
         * @Assert\Regex(
         *     pattern="/\d/",
         *     match=true,
         *     message="viteloge.assert.phone"
         * )
         */
        protected $phone;

        public function __construct() {

        }

        public function getUser() {
            return $this->user;
        }

        public function setUser(User $user) {
            $this->user = $user;
            $this->email = $this->user->getEmail();
            $this->lastname = $this->user->getLastname();
            $this->firstname = $this->user->getFirstname();
            $this->phone = $this->user->getPhone();
            return $this;
        }

        public function getFirstname() {
            return $this->firstname;
        }

        public function setFirstname($firstname) {
            $this->firstname = $firstname;
            return $this;
        }

        public function getLastname() {
            return $this->lastname;
        }

        public function setLastname($lastname) {
            $this->lastname = $lastname;
            return $this;
        }

        public function getEmail() {
            return $this->email;
        }

        public function setEmail($email) {
            $this->email = $email;
            return $this;
        }

        public function getMessage() {
            $this->message = $message;
            return $this->message;
        }

        public function setMessage($message) {
            return $this;
        }

        public function getPhone() {
            return $this->phone;
        }

        public function setPhone($phone) {
            $this->phone = $phone;
            return $this;
        }
    }

}
