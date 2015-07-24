<?php

namespace Viteloge\FrontendBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;
    use Viteloge\CoreBundle\Entity\User as CoreUser;
    use Viteloge\CoreBundle\Entity\Ad;

    /**
     * @ORM\Entity
     */
    class Message {

        /**
         * @var integer
         *
         * @ORM\Id
         * @ORM\Column(name="id", type="integer")
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         *
         */
        protected $user;

        /**
         *
         */
        protected $ad;

        /**
         * @Assert\Length(
         *      min = "2",
         *      max = "64"
         * )
         */
        protected $firstname;

        /**
         * @Assert\NotBlank()
         * @Assert\Length(
         *      min = "2",
         *      max = "64"
         * )
         */
        protected $lastname;

        /**
         * @Assert\NotBlank()
         * @Assert\Email(
         *      checkHost = true,
         *      checkMX = true
         * )
         */
        protected $email;

        /**
         * @Assert\NotBlank()
         * @Assert\Length(
         *      min = "5",
         *      max = "250"
         * )
         */
        protected $message;

        /**
         * @Assert\NotBlank()
         * @Assert\Regex(
         *     pattern="/\d/",
         *     match=true,
         *     message="viteloge.assert.phone"
         * )
         */
        protected $phone;

        /**
         *
         */
        public function __construct(Ad $ad) {
            $this->setAd($ad);
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
        public function setUser(CoreUser $user = null) {
            $this->user = $user;
            if ($user instanceof CoreUser) {
                $this->email = $this->user->getEmail();
                $this->lastname = $this->user->getLastname();
                $this->firstname = $this->user->getFirstname();
                $this->phone = $this->user->getPhone();
            }
            return $this;
        }

        /**
         *
         */
        public function getAd() {
            return $this->ad;
        }

        /**
         *
         */
        public function setAd(Ad $ad) {
            $this->ad = $ad;
            return $this;
        }

        /**
         *
         */
        public function getFirstname() {
            return $this->firstname;
        }

        /**
         *
         */
        public function setFirstname($firstname) {
            $this->firstname = $firstname;
            return $this;
        }

        /**
         *
         */
        public function getLastname() {
            return $this->lastname;
        }

        /**
         *
         */
        public function setLastname($lastname) {
            $this->lastname = $lastname;
            return $this;
        }

        /**
         *
         */
        public function getFullname() {
            return trim($this->getFirstname().' '.$this->getLastname());
        }

        /**
         *
         */
        public function getEmail() {
            return $this->email;
        }

        /**
         *
         */
        public function setEmail($email) {
            $this->email = $email;
            return $this;
        }

        /**
         *
         */
        public function getMessage() {
            return $this->message;
        }

        /**
         *
         */
        public function setMessage($message) {
            $this->message = $message;
            return $this;
        }

        /**
         *
         */
        public function getPhone() {
            return $this->phone;
        }

        /**
         *
         */
        public function setPhone($phone) {
            $this->phone = $phone;
            return $this;
        }
    }

}
