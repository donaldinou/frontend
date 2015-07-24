<?php

namespace Viteloge\FrontendBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;
    use Viteloge\CoreBundle\Entity\User as CoreUser;

    /**
     * @ORM\Entity
     */
    class Contact {

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
         * @Assert\Length(
         *      min = "2",
         *      max = "64"
         * )
         */
        protected $company;

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
         * @Assert\Regex(
         *     pattern="/\d/",
         *     match=true,
         *     message="viteloge.assert.phone"
         * )
         */
        protected $phone;

        /**
         * @Assert\NotBlank()
         * @Assert\Choice(
         *      callback = {"Viteloge\FrontendBundle\Component\Enum\SubjectEnum", "getValues"},
         *      multiple = false,
         * )
         */
        protected $subject;

        /**
         * @Assert\Length(
         *      min = "5",
         *      max = "250"
         * )
         */
        protected $address;

        /**
         * @Assert\Regex(
         *     pattern="/\d/",
         *     match=true,
         *     message="viteloge.assert.phone"
         * )
         */
        protected $postalCode;

        /**
         * @Assert\NotBlank()
         * @Assert\Length(
         *      min = "2",
         *      max = "64"
         * )
         */
        protected $city;

        /**
         *
         */
        public function __construct() {

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
        public function getCompany() {
            return $this->company;
        }

        /**
         *
         */
        public function setCompany($company) {
            $this->company = $company;
            return $this;
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

        /**
         *
         */
        public function getSubject() {
            return $this->subject;
        }

        /**
         *
         */
        public function setSubject($subject) {
            $this->subject = $subject;
            return $this;
        }

        /**
         *
         */
        public function getAddress() {
            return $this->message;
        }

        /**
         *
         */
        public function setAddress($address) {
            $this->address = $address;
            return $this;
        }

        /**
         *
         */
        public function getPostalCode() {
            return $this->postalCode;
        }

        /**
         *
         */
        public function setPostalCode($postalCode) {
            $this->postalCode = $postalCode;
            return $this;
        }

        /**
         *
         */
        public function getCity() {
            return $this->city;
        }

        /**
         *
         */
        public function setCity($city) {
            $this->city = $city;
            return $this;
        }
    }

}
