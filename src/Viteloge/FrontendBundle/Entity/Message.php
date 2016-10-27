<?php

namespace Viteloge\FrontendBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;
    use Viteloge\CoreBundle\Entity\User as CoreUser;
    use Viteloge\CoreBundle\Entity\Ad;

    /**
     * Message
     *
     * @ORM\Table(name="message")
     * @ORM\Entity(repositoryClass="Viteloge\FrontendBundle\Repository\MessageRepository")
     */
    class Message {

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
         *   @ORM\JoinColumn(name="idUser", referencedColumnName="id")
         * })
         */
        protected $user;

        /**
         *
         * @ORM\ManyToOne(targetEntity="Viteloge\CoreBundle\Entity\Ad")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="idAnnonce", referencedColumnName="idAnnonce")
         * })
         */
        protected $ad;

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
         * @Assert\NotBlank()
         * @Assert\Email(
         *      checkHost = true,
         *      checkMX = true
         * )
         * @ORM\Column(name="email",type="string",length=255)
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
         * @Assert\NotBlank()
         * @Assert\Regex(
         *     pattern="/\d/",
         *     match=true,
         *     message="viteloge.assert.phone"
         * )
         * @ORM\Column(name="phone",type="string",length=15)
         */
        protected $phone;

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
        public function __construct(Ad $ad) {
            $this->setAd($ad);
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
         * @return Message
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
         * @return Message
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
         * @return Message
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
         * @return Message
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
         * @return Message
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
         * @return Message
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
