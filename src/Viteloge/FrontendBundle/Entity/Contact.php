<?php

namespace Viteloge\FrontendBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;
    use Viteloge\CoreBundle\Entity\User as CoreUser;

    /**
     * Contact
     *
     * @ORM\Table(name="contact")
     * @ORM\Entity(repositoryClass="Viteloge\FrontendBundle\Repository\ContactRepository")
     */
    class Contact {

        /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="date", type="datetime", nullable=false)
         */
        protected $date;

        /**
         * @var integer
         *
         * @ORM\Column(name="annee", type="smallint", nullable=false)
         */
        protected $year;

        /**
         * @var integer
         *
         * @ORM\Column(name="mois", type="smallint", nullable=false)
         */
        protected $month;

        /**
         * @var integer
         *
         * @ORM\Column(name="jour", type="smallint", nullable=false)
         */
        protected $day;

        /**
         * @var string
         *
         * @ORM\Column(name="ip", type="string", length=15, nullable=false)
         */
        protected $ip;

        /**
         * @var string
         *
         * @ORM\Column(name="UA", type="string", length=128, nullable=false)
         */
        protected $ua;

        /**
         *
         * @ORM\ManyToOne(targetEntity="Viteloge\CoreBundle\Entity\User")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="id", referencedColumnName="id")
         * })
         */
        protected $user;

        /**
         * @Assert\Length(
         *      min = "2",
         *      max = "64"
         * )
         * @ORM\Column(name="firstname",type="string",length=255)
         */
        protected $firstname;

        /**
         * @Assert\NotBlank()
         * @Assert\Length(
         *      min = "2",
         *      max = "64"
         * )
         * @ORM\Column(name="lastname",type="string",length=255)
         */
        protected $lastname;

        /**
         * @Assert\Length(
         *      min = "2",
         *      max = "64"
         * )
         * @ORM\Column(name="company",type="string",length=255, nullable=true)
         */
        protected $company;

        /**
         * @Assert\NotBlank()
         * @Assert\Email(
         *      checkHost = true,
         *      checkMX = true
         * )
         * @ORM\Column(name="email",type="string",length=255, nullable=true)
         */
        protected $email;

        /**
         * @Assert\NotBlank()
         * @Assert\Length(
         *      min = "5",
         *      max = "250"
         * )
         * @ORM\Column(name="message",type="string",length=255)
         */
        protected $message;

        /**
         * @Assert\Regex(
         *     pattern="/\d/",
         *     match=true,
         *     message="viteloge.assert.phone"
         * )
         * @ORM\Column(name="phone",type="string",length=15, nullable=true)
         */
        protected $phone;

        /**
         * @Assert\NotBlank()
         * @Assert\Choice(
         *      callback = {"Viteloge\FrontendBundle\Component\Enum\SubjectEnum", "getValues"},
         *      multiple = false,
         * )
         * @ORM\Column(name="subject",type="string",length=255)
         */
        protected $subject;

        /**
         * @Assert\Length(
         *      min = "5",
         *      max = "250"
         * )
         * @ORM\Column(name="address",type="string",length=255, nullable=true)
         */
        protected $address;

        /**
         * @Assert\Regex(
         *     pattern="/\d/",
         *     match=true,
         *     message="viteloge.assert.phone"
         * )
         * @ORM\Column(name="postalCode",type="string",length=5, nullable=true)
         */
        protected $postalCode;

        /**
         * @Assert\NotBlank()
         * @Assert\Length(
         *      min = "2",
         *      max = "64"
         * )
         * @ORM\Column(name="city",type="string",length=50, nullable=false)
         */
        protected $city;

        /**
         * @return Contacts
         */
        protected function updateCreatedAt() {
            $this->date->setDate($this->getYear(), $this->getMonth(), $this->getDay());
            return $this;
        }

        /**
         *
         */
        public function __construct() {
            $this->setDate(new \DateTime('now'));

        }

        /**
         * Get id
         *
         * @return integer
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set date
         *
         * @param \DateTime $date
         * @return Contact
         */
        public function setDate($date) {
            try {
                $this->date = clone $date;
                $this->setYear((int)$date->format('Y'));
                $this->setMonth((int)$date->format('m'));
                $this->setDay((int)$date->format('d'));
            } catch (\Exception $e) {

            }
            return $this;
        }

        /**
         * Get date
         *
         * @return \DateTime
         */
        public function getDate()
        {
            return $this->date;
        }

        /**
         * Set year
         *
         * @param integer $year
         * @return Contact
         */
        public function setYear($year) {
            if (is_int($year)) {
                $this->year = $year;
                $this->updateCreatedAt();
            }

            return $this;
        }

        /**
         * Get year
         *
         * @return integer
         */
        public function getYear()
        {
            return $this->year;
        }

        /**
         * Set month
         *
         * @param integer $month
         * @return Contact
         */
        public function setMonth($month) {
            if (is_int($month)) {
                $this->month = $month;
                $this->updateCreatedAt();
            }

            return $this;
        }

        /**
         * Get month
         *
         * @return integer
         */
        public function getMonth()
        {
            return $this->month;
        }

        /**
         * Set day
         *
         * @param integer $day
         * @return Contact
         */
        public function setDay($day) {
            if (is_int($day)) {
                $this->day = $day;
                $this->updateCreatedAt();
            }

            return $this;
        }

        /**
         * Get day
         *
         * @return integer
         */
        public function getDay()
        {
            return $this->day;
        }

        /**
         * Set ip
         *
         * @param string $ip
         * @return Contact
         */
        public function setIp($ip)
        {
            $this->ip = $ip;

            return $this;
        }

        /**
         * Get ip
         *
         * @return string
         */
        public function getIp()
        {
            return $this->ip;
        }

        /**
         * Set ua
         *
         * @param string $ua
         * @return Contact
         */
        public function setUa($ua)
        {
            $this->ua = $ua;

            return $this;
        }

        /**
         * Get ua
         *
         * @return string
         */
        public function getUa()
        {
            return $this->ua;
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
           // $this->user = $user;
            if ($user instanceof CoreUser) {
                $this->email = $user->getEmail();
                $this->lastname = $user->getLastname();
                $this->firstname = $user->getFirstname();
                $this->phone = $user->getPhone();
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
