<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\Common\Collections\ArrayCollection;
    use Symfony\Component\Validator\Constraints as Assert;
    use FOS\UserBundle\Model\User as BaseUser;
    use Viteloge\Corebundle\Component\Enum\CivilityEnum;

    /**
     * User
     *
     * @ORM\Table(name="accounts", uniqueConstraints={
     *      @ORM\UniqueConstraint(name="UNIQ_CAC89EAC92FC23A8", columns={
     *          "username_canonical"
     *      }),
     *      @ORM\UniqueConstraint(name="UNIQ_CAC89EACA0D96FBF", columns={
     *          "email_canonical"
     *      }),
     *      @ORM\UniqueConstraint(name="email_unique", columns={
     *          "email"
     *      })
     * })
     * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\UserRepository")
     */
    class User extends BaseUser {

        /**
         * @var string
         *
         * @ORM\Column(name="civilite", type="string", length=5, nullable=true)
         * @Assert\Choice(
         *      callback = {"Viteloge\CoreBundle\Component\Enum\CivilityEnum", "getValues"},
         *      multiple = false,
         * )
         */
        protected $civility;

        /**
         * @var string
         *
         * @ORM\Column(name="firstName", type="string", length=255, nullable=true)
         * @Assert\Length(
         *      max = 64,
         *      maxMessage = "assert.length.user.validate.max.firstname"
         * )
         */
        protected $firstname;

        /**
         * @var string
         *
         * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
         * @Assert\Length(
         *      max = 64,
         *      maxMessage = "assert.length.user.validate.max.lastname"
         * )
         */
        protected $lastname;

        /**
         * @var string
         *
         * @ORM\Column(name="phone", type="string", length=25, nullable=true)
         * @Assert\Regex(
         *     pattern = "/^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/",
         *     match = true,
         *     message = "assert.regex.user.validate.phone"
         * )
         */
        protected $phone;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="created_at", type="datetime", nullable=false)
         */
        protected $createdAt;

        /**
         * Allow commercial contact
         * @var boolean
         *
         * @ORM\Column(name="partenaires", type="boolean", nullable=false)
         */
        protected $partnerContactEnabled;

        /**
         * @var boolean
         *
         * @ORM\Column(name="disable_internal_emails", type="boolean", nullable=false)
         */
        protected $internalMailDisabled;

        /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        protected $id;

        /**
         * @var string
         *
         * @ORM\Column(name="facebook_id", type="string", nullable=true)
         */
        protected $facebookId;

        /**
         * @var string
         *
         * @ORM\Column(name="twitter_id", type="string", nullable=true)
         */
        protected $twitterId;

        /**
         * @var string
         *
         * @ORM\Column(name="google_id", type="string", nullable=true)
         */
        protected $googleId;

        /**
         * @var string
         *
         * @ORM\Column(name="github_id", type="string", nullable=true)
         */
        protected $githubId;

        /**
         * Note: We cannot fetch eager because of softdeleteable
         * @ORM\OneToMany(targetEntity="Viteloge\CoreBundle\Entity\WebSearch", mappedBy="user")
         */
        private $webSearches;

        /**
         *
         */
        public function __construct() {
            parent::__construct();
            $this->createdAt = new \DateTime('now');
            $this->partnerContactEnabled = true;
            $this->internalMailDisabled = false;
            $this->webSearches = new ArrayCollection();
        }

        /**
         *
         */
        public function calculateRatioProfile() {
            $percent = 40;
            $percent += (!empty($this->civility)) ? 10 : 0;
            $percent += (!empty($this->firstname)) ? 10 : 0;
            $percent += (!empty($this->lastname)) ? 10 : 0;
            $percent += (!empty($this->email)) ? 10 : 0;
            $percent += (!empty($this->phone)) ? 10 : 0;
            $percent += (count($this->webSearches)>0) ? 10 : 0;
            return $percent;
        }

        /**
         * Set username
         *
         * @param string $username
         * @return Account
         */
        public function setUsername($username)
        {
            parent::setUsername($username);

            return $this;
        }

        /**
         * Get username
         *
         * @return string
         */
        public function getUsername()
        {
            return parent::getUsername();
        }

        /**
         * Set usernameCanonical
         *
         * @param string $usernameCanonical
         * @return Account
         */
        public function setUsernameCanonical($usernameCanonical)
        {
            parent::setUsernameCanonical($usernameCanonical);

            return $this;
        }

        /**
         * Get usernameCanonical
         *
         * @return string
         */
        public function getUsernameCanonical()
        {
            return parent::getUsernameCanonical();
        }

        /**
         * Set email
         *
         * @param string $email
         * @return Account
         */
        public function setEmail($email)
        {
            parent::setEmail($email);
            $this->setUsername($email);

            return $this;
        }

        /**
         * Get email
         *
         * @return string
         */
        public function getEmail()
        {
            return parent::getEmail();
        }

        /**
         * Set emailCanonical
         *
         * @param string $emailCanonical
         * @return Account
         */
        public function setEmailCanonical($emailCanonical)
        {
            parent::setEmailCanonical($emailCanonical);
            $this->setUsernameCanonical($emailCanonical);

            return $this;
        }

        /**
         * Get emailCanonical
         *
         * @return string
         */
        public function getEmailCanonical()
        {
            return parent::getEmailCanonical();
        }

        /**
         * Set enabled
         *
         * @param boolean $enabled
         * @return Account
         */
        public function setEnabled($enabled)
        {
            parent::setEnabled($enabled);

            return $this;
        }

        /**
         * Get enabled
         *
         * @return boolean
         */
        public function isEnabled()
        {
            return parent::isEnabled();
        }

        /**
         * Get salt
         *
         * @return string
         */
        public function getSalt()
        {
            return parent::getSalt();
        }

        /**
         * Set password
         *
         * @param string $password
         * @return Account
         */
        public function setPassword($password)
        {
            parent::setPassword($password);

            return $this;
        }

        /**
         * Get password
         *
         * @return string
         */
        public function getPassword()
        {
            return parent::getPassword();
        }

        /**
         * Set lastLogin
         *
         * @param \DateTime $lastLogin
         * @return Account
         */
        public function setLastLogin(\DateTime $lastLogin = null)
        {
            parent::setLastLogin($lastLogin);

            return $this;
        }

        /**
         * Get lastLogin
         *
         * @return \DateTime
         */
        public function getLastLogin()
        {
            return parent::getLastLogin();
        }

        /**
         * Set locked
         *
         * @param boolean $locked
         * @return Account
         */
        public function setLocked($locked)
        {
            parent::setLocked($locked);

            return $this;
        }

        /**
         * Get locked
         *
         * @return boolean
         */
        public function isLocked()
        {
            return parent::isLocked();
        }

        /**
         * Set expired
         *
         * @param boolean $expired
         * @return Account
         */
        public function setExpired($expired)
        {
            parent::setExpired($expired);

            return $this;
        }

        /**
         * Get expired
         *
         * @return boolean
         */
        public function isExpired()
        {
            return parent::isExpired();
        }

        /**
         * Set expiresAt
         *
         * @param \DateTime $expiresAt
         * @return Account
         */
        public function setExpiresAt(\DateTime $expiresAt = null)
        {
            parent:setExpiresAt($expiresAt);

            return $this;
        }

        /**
         * Get expiresAt
         *
         * @return \DateTime
         */
        public function getExpiresAt()
        {
            return $this->expiresAt;
        }

        /**
         * Set confirmationToken
         *
         * @param string $confirmationToken
         * @return Account
         */
        public function setConfirmationToken($confirmationToken)
        {
            parent::setConfirmationToken($confirmationToken);

            return $this;
        }

        /**
         * Get confirmationToken
         *
         * @return string
         */
        public function getConfirmationToken()
        {
            return parent::getConfirmationToken();
        }

        /**
         * Set passwordRequestedAt
         *
         * @param \DateTime $passwordRequestedAt
         * @return Account
         */
        public function setPasswordRequestedAt(\DateTime $passwordRequestedAt = null)
        {
            parent::setPasswordRequestedAt($passwordRequestedAt);

            return $this;
        }

        /**
         * Get passwordRequestedAt
         *
         * @return \DateTime
         */
        public function getPasswordRequestedAt()
        {
            return parent::getPasswordRequestedAt();
        }

        /**
         * Set roles
         *
         * @param array $roles
         * @return Account
         */
        public function setRoles(array $roles)
        {
            return parent::setRoles($roles);
        }

        /**
         * Get roles
         *
         * @return array
         */
        public function getRoles()
        {
            return parent::getRoles();
        }

        /**
         * Set credentialsExpired
         *
         * @param boolean $credentialsExpired
         * @return Account
         */
        public function setCredentialsExpired($credentialsExpired)
        {
            parent::setCredentialsExpired($credentialsExpired);

            return $this;
        }

        /**
         * Get credentialsExpired
         *
         * @return boolean
         */
        public function isCredentialsExpired()
        {
            return parent::isCredentialsExpired();
        }

        /**
         * Set credentialsExpireAt
         *
         * @param \DateTime $credentialsExpireAt
         * @return Account
         */
        public function setCredentialsExpireAt(\DateTime $credentialsExpireAt = null)
        {
            parent::setCredentialsExpireAt($credentialsExpireAt);

            return $this;
        }

        /**
         * Get credentialsExpireAt
         *
         * @return \DateTime
         */
        public function getCredentialsExpireAt()
        {
            return $this->credentialsExpireAt;
        }

        /**
         * Return firstname and lastname
         *
         * @return string
         */
        public function getFullname() {
            $fullname = trim(preg_replace('/\s+/', ' ', $this->getCivility().' '.$this->getFirstname().' '.$this->getLastname()));
            if (empty($fullname) || $fullname == $this->getCivility()) {
                $fullname = $this->getEmail();
            }
            return $fullname;
        }

        /**
         * Set civility
         *
         * @param string $civility
         * @return Account
         */
        public function setCivility($civility) {
            if (!$civility instanceof CivilityEnum) {
                $object = new CivilityEnum();
                if (in_array($civility, $object->getConstList())) {
                    $this->civility = $civility;
                }
            } else {
                $this->civility = (string)$civility;
            }

            return $this;
        }

        /**
         * Get civility
         *
         * @return string
         */
        public function getCivility() {
            return (!empty($this->civility)) ? $this->civility : \Viteloge\CoreBundle\Component\Enum\CivilityEnum::MISTER;
        }

        /**
         * Set firstname
         *
         * @param string $firstname
         * @return Account
         */
        public function setFirstname($firstname)
        {
            $this->firstname = $firstname;

            return $this;
        }

        /**
         * Get firstname
         *
         * @return string
         */
        public function getFirstname() {
            return (!empty($this->firstname)) ? $this->firstname : '';
        }

        /**
         * Set lastname
         *
         * @param string $lastname
         * @return Account
         */
        public function setLastname($lastname)
        {
            $this->lastname = $lastname;

            return $this;
        }

        /**
         * Get lastname
         *
         * @return string
         */
        public function getLastname() {
            return (!empty($this->lastname)) ? $this->lastname : '';
        }

        /**
         * Set phone
         *
         * @param string $phone
         * @return Account
         */
        public function setPhone($phone)
        {
            $this->phone = $phone;

            return $this;
        }

        /**
         * Get phone
         *
         * @return string
         */
        public function getPhone()
        {
            return $this->phone;
        }

        /**
         * Set createdAt
         *
         * @param \DateTime $createdAt
         * @return Account
         */
        public function setCreatedAt(\DateTime $createdAt = null)
        {
            $this->createdAt = $createdAt;

            return $this;
        }

        /**
         * Get createdAt
         *
         * @return \DateTime
         */
        public function getCreatedAt()
        {
            return $this->createdAt;
        }

        /**
         * Set partnerContactEnabled
         *
         * @param boolean $partnerContactEnabled
         * @return Account
         */
        public function setPartnerContactEnabled($partnerContactEnabled)
        {
            $this->partnerContactEnabled = (boolean)$partnerContactEnabled;

            return $this;
        }

        /**
         * is partnerContactEnabled
         *
         * @return boolean
         */
        public function isPartnerContactEnabled()
        {
            return (boolean)$this->partnerContactEnabled;
        }

        /**
         * Set internalMailDisabled
         *
         * @param boolean $isInternalMailDisabled
         * @return Account
         */
        public function setInternalMailDisabled($isInternalMailDisabled)
        {
            $this->isInternalMailDisabled = (boolean)$isInternalMailDisabled;

            return $this;
        }

        /**
         * Get isInternalMailDisabled
         *
         * @return boolean
         */
        public function isInternalMailDisabled()
        {
            return (boolean)$this->isInternalMailDisabled;
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
         * Set FacebookId
         *
         * @param integer $facebookId
         * @return Account
         */
        public function setFacebookId($facebookId) {
            $this->facebookId = $facebookId;
            return $this;
        }

        /**
         * Get FacebookId
         *
         * @return integer
         */
        public function getFacebookId() {
            return $this->facebookId;
        }

        /**
         * Set TwitterId
         *
         * @param integer $twitterId
         * @return Account
         */
        public function setTwitterId($twitterId) {
            $this->twitterId = $twitterId;
            return $this;
        }

        /**
         * Get FacebookId
         *
         * @return integer
         */
        public function getTwitterId() {
            return $this->twitterId;
        }

        /**
         * Set FacebookId
         *
         * @param integer $facebookId
         * @return Account
         */
        public function setGoogleId($googleId) {
            $this->googleId = $googleId;
            return $this;
        }

        /**
         * Get FacebookId
         *
         * @return integer
         */
        public function getGoogleId() {
            return $this->googleId;
        }

        /**
         * Set githubId
         *
         * @param integer $facebookId
         * @return Account
         */
        public function setGithubId($githubId) {
            $this->githubId = $githubId;
            return $this;
        }

        /**
         * Get FacebookId
         *
         * @return integer
         */
        public function getGithubId() {
            return $this->githubId;
        }

        /**
         * Add webSearches
         *
         * @param \Viteloge\CoreBundle\Entity\WebSearch $webSearches
         * @return UserSearch
         */
        public function addWebSearch(\Viteloge\CoreBundle\Entity\WebSearch $webSearches)
        {
            $this->webSearches[] = $webSearches;

            return $this;
        }

        /**
         * Remove webSearches
         *
         * @param \Viteloge\CoreBundle\Entity\WebSearch $webSearches
         */
        public function removeWebSearch(\Viteloge\CoreBundle\Entity\WebSearch $webSearches)
        {
            $this->webSearches->removeElement($webSearches);
        }

        /**
         * Get webSearches
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getWebSearches()
        {
            return $this->webSearches;
        }

        /**
         * @return int
         */
        public function webSearchesTotalNewMatches() {
            $result = 0;
            foreach ($this->getWebSearches() as $key => $webSearches) {
                $result += (int)$webSearches->getNewmatches();
            }
            return $result;
        }

    }


}
