<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use FOS\UserBundle\Model\User as BaseUser;

    /**
     * User
     *
     * @ORM\Table(name="accounts", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_CAC89EAC92FC23A8", columns={"username_canonical"}), @ORM\UniqueConstraint(name="UNIQ_CAC89EACA0D96FBF", columns={"email_canonical"}), @ORM\UniqueConstraint(name="email_unique", columns={"email"})})
     * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\AccountRepository")
     */
    class User extends BaseUser {

        /**
         * @var string
         *
         * @ORM\Column(name="username", type="string", length=255, nullable=false)
         */
        private $username;

        /**
         * @var string
         *
         * @ORM\Column(name="usernamername_canonical", type="string", length=255, nullable=false)
         */
        private $usernameCanonical;

        /**
         * @var string
         *
         * @ORM\Column(name="email", type="string", length=255, nullable=false)
         */
        private $email;

        /**
         * @var string
         *
         * @ORM\Column(name="email_canonical", type="string", length=255, nullable=false)
         */
        private $emailCanonical;

        /**
         * @var boolean
         *
         * @ORM\Column(name="enabled", type="boolean", nullable=false)
         */
        private $enabled;

        /**
         * @var string
         *
         * @ORM\Column(name="salt", type="string", length=255, nullable=false)
         */
        private $salt;

        /**
         * @var string
         *
         * @ORM\Column(name="password", type="string", length=255, nullable=false)
         */
        private $password;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="last_login", type="datetime", nullable=true)
         */
        private $lastLogin;

        /**
         * @var boolean
         *
         * @ORM\Column(name="locked", type="boolean", nullable=false)
         */
        private $locked;

        /**
         * @var boolean
         *
         * @ORM\Column(name="expired", type="boolean", nullable=false)
         */
        private $expired;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="expires_at", type="datetime", nullable=true)
         */
        private $expiresAt;

        /**
         * @var string
         *
         * @ORM\Column(name="confirmation_token", type="string", length=255, nullable=true)
         */
        private $confirmationToken;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="password_requested_at", type="datetime", nullable=true)
         */
        private $passwordRequestedAt;

        /**
         * @var array
         *
         * @ORM\Column(name="roles", type="array", nullable=false)
         */
        private $roles;

        /**
         * @var boolean
         *
         * @ORM\Column(name="credentials_expired", type="boolean", nullable=false)
         */
        private $credentialsExpired;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="credentials_expire_at", type="datetime", nullable=true)
         */
        private $credentialsExpireAt;

        /**
         * @var string
         *
         * @ORM\Column(name="civilite", type="string", length=5, nullable=true)
         */
        private $civility;

        /**
         * @var string
         *
         * @ORM\Column(name="firstName", type="string", length=255, nullable=true)
         */
        private $firstname;

        /**
         * @var string
         *
         * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
         */
        private $lastname;

        /**
         * @var string
         *
         * @ORM\Column(name="phone", type="string", length=25, nullable=true)
         */
        private $phone;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="created_at", type="datetime", nullable=false)
         */
        private $createdAt;

        /**
         * Allow commercial contact
         * @var boolean
         *
         * @ORM\Column(name="partenaires", type="boolean", nullable=false)
         */
        private $isPartnerContactEnabled;

        /**
         * @var boolean
         *
         * @ORM\Column(name="disable_internal_emails", type="boolean", nullable=false)
         */
        private $isInternalMailDisabled;

        /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         *
         */
        public function __construct() {
            parent::__construct();
            $this->createdAt = new DateTime('now');
            $this->isPartnerContactEnabled = true;
            $this->isInternalMailDisabled = false;
        }

        /**
         * Set username
         *
         * @param string $username
         * @return Account
         */
        public function setUsername($username)
        {
            $this->username = $username;

            return $this;
        }

        /**
         * Get username
         *
         * @return string
         */
        public function getUsername()
        {
            return $this->username;
        }

        /**
         * Set usernameCanonical
         *
         * @param string $usernameCanonical
         * @return Account
         */
        public function setUsernameCanonical($usernameCanonical)
        {
            $this->usernameCanonical = $usernameCanonical;

            return $this;
        }

        /**
         * Get usernameCanonical
         *
         * @return string
         */
        public function getUsernameCanonical()
        {
            return $this->usernameCanonical;
        }

        /**
         * Set email
         *
         * @param string $email
         * @return Account
         */
        public function setEmail($email)
        {
            $this->email = $email;

            return $this;
        }

        /**
         * Get email
         *
         * @return string
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * Set emailCanonical
         *
         * @param string $emailCanonical
         * @return Account
         */
        public function setEmailCanonical($emailCanonical)
        {
            $this->emailCanonical = $emailCanonical;

            return $this;
        }

        /**
         * Get emailCanonical
         *
         * @return string
         */
        public function getEmailCanonical()
        {
            return $this->emailCanonical;
        }

        /**
         * Set enabled
         *
         * @param boolean $enabled
         * @return Account
         */
        public function setEnabled($enabled)
        {
            $this->enabled = $enabled;

            return $this;
        }

        /**
         * Get enabled
         *
         * @return boolean
         */
        public function getEnabled()
        {
            return $this->enabled;
        }

        /**
         * Set salt
         *
         * @param string $salt
         * @return Account
         */
        public function setSalt($salt)
        {
            $this->salt = $salt;

            return $this;
        }

        /**
         * Get salt
         *
         * @return string
         */
        public function getSalt()
        {
            return $this->salt;
        }

        /**
         * Set password
         *
         * @param string $password
         * @return Account
         */
        public function setPassword($password)
        {
            $this->password = $password;

            return $this;
        }

        /**
         * Get password
         *
         * @return string
         */
        public function getPassword()
        {
            return $this->password;
        }

        /**
         * Set lastLogin
         *
         * @param \DateTime $lastLogin
         * @return Account
         */
        public function setLastLogin($lastLogin)
        {
            $this->lastLogin = $lastLogin;

            return $this;
        }

        /**
         * Get lastLogin
         *
         * @return \DateTime
         */
        public function getLastLogin()
        {
            return $this->lastLogin;
        }

        /**
         * Set locked
         *
         * @param boolean $locked
         * @return Account
         */
        public function setLocked($locked)
        {
            $this->locked = $locked;

            return $this;
        }

        /**
         * Get locked
         *
         * @return boolean
         */
        public function getLocked()
        {
            return $this->locked;
        }

        /**
         * Set expired
         *
         * @param boolean $expired
         * @return Account
         */
        public function setExpired($expired)
        {
            $this->expired = $expired;

            return $this;
        }

        /**
         * Get expired
         *
         * @return boolean
         */
        public function getExpired()
        {
            return $this->expired;
        }

        /**
         * Set expiresAt
         *
         * @param \DateTime $expiresAt
         * @return Account
         */
        public function setExpiresAt($expiresAt)
        {
            $this->expiresAt = $expiresAt;

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
            $this->confirmationToken = $confirmationToken;

            return $this;
        }

        /**
         * Get confirmationToken
         *
         * @return string
         */
        public function getConfirmationToken()
        {
            return $this->confirmationToken;
        }

        /**
         * Set passwordRequestedAt
         *
         * @param \DateTime $passwordRequestedAt
         * @return Account
         */
        public function setPasswordRequestedAt($passwordRequestedAt)
        {
            $this->passwordRequestedAt = $passwordRequestedAt;

            return $this;
        }

        /**
         * Get passwordRequestedAt
         *
         * @return \DateTime
         */
        public function getPasswordRequestedAt()
        {
            return $this->passwordRequestedAt;
        }

        /**
         * Set roles
         *
         * @param array $roles
         * @return Account
         */
        public function setRoles($roles)
        {
            $this->roles = $roles;

            return $this;
        }

        /**
         * Get roles
         *
         * @return array
         */
        public function getRoles()
        {
            return $this->roles;
        }

        /**
         * Set credentialsExpired
         *
         * @param boolean $credentialsExpired
         * @return Account
         */
        public function setCredentialsExpired($credentialsExpired)
        {
            $this->credentialsExpired = $credentialsExpired;

            return $this;
        }

        /**
         * Get credentialsExpired
         *
         * @return boolean
         */
        public function getCredentialsExpired()
        {
            return $this->credentialsExpired;
        }

        /**
         * Set credentialsExpireAt
         *
         * @param \DateTime $credentialsExpireAt
         * @return Account
         */
        public function setCredentialsExpireAt($credentialsExpireAt)
        {
            $this->credentialsExpireAt = $credentialsExpireAt;

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
         * Set civility
         *
         * @param string $civility
         * @return Account
         */
        public function setCivility($civility)
        {
            $this->civility = $civility;

            return $this;
        }

        /**
         * Get civility
         *
         * @return string
         */
        public function getCivility()
        {
            return $this->civility;
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
        public function getFirstname()
        {
            return $this->firstname;
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
        public function getLastname()
        {
            return $this->lastname;
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
        public function setCreatedAt($createdAt)
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
         * Set isPartnerContactEnabled
         *
         * @param boolean $isPartner
         * @return Account
         */
        public function setIsPartnerContactEnabled($isPartner)
        {
            $this->isPartner = $isPartner;

            return $this;
        }

        /**
         * Get isPartnerContactEnabled
         *
         * @return boolean
         */
        public function getIsPartnerContactEnabled()
        {
            return $this->isPartner;
        }

        /**
         * Set isInternalMailDisabled
         *
         * @param boolean $isInternalMailDisabled
         * @return Account
         */
        public function setIsInternalMailDisabled($isInternalMailDisabled)
        {
            $this->isInternalMailDisabled = $isInternalMailDisabled;

            return $this;
        }

        /**
         * Get isInternalMailDisabled
         *
         * @return boolean
         */
        public function getIsInternalMailDisabled()
        {
            return $this->isInternalMailDisabled;
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
    }


}
