<?php

namespace Viteloge\FrontendBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;
    use Viteloge\CoreBundle\Entity\User as CoreUser;

    /**
     * @ORM\Entity
     */
    class Recommand {

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
         * @Assert\NotBlank()
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
         * @Assert\Count(
         *      min = "1",
         *      max = "10",
         *      minMessage = "You must specify at least one email",
         *      maxMessage = "You cannot specify more than {{ limit }} emails"
         * )
         * @Assert\All(
         *     @Assert\Email()
         * )
         */
        protected $emails;

        /**
         *
         */
        public function __construct() {
            $this->emails = new \Doctrine\Common\Collections\ArrayCollection();
            $this->addEmail('');
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
            }
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
         * Add email
         *
         */
        public function addEmail($email) {
            $this->emails[] = $email;

            return $this;
        }

        /**
         * Remove email
         */
        public function removeEmail($email) {
            $this->emails->removeElement($email);
        }

        /**
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getEmails() {
            return $this->emails;
        }
    }

}
