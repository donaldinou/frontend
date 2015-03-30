<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\Common\Collections\ArrayCollection;

    /**
     * Search
     *
     * @ORM\Table(name="utilisateur", indexes={@ORM\Index(name="isHelp", columns={"help"}), @ORM\Index(name="isPartner", columns={"partenaires"}), @ORM\Index(name="mail", columns={"mail", "dateResiliation"})})
     * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\SearchRepository")
     */
    class UserSearch
    {
        /**
         * @var string
         *
         * @ORM\Column(name="civilite", type="string", length=50, nullable=false)
         */
        private $civility;

        /**
         * @var string
         *
         * @ORM\Column(name="nom", type="string", length=50, nullable=false)
         */
        private $lastname;

        /**
         * @var string
         *
         * @ORM\Column(name="prenom", type="string", length=50, nullable=false)
         */
        private $firstname;

        /**
         * @var string
         *
         * @ORM\Column(name="codepostal", type="string", length=5, nullable=false)
         */
        private $postalcode;

        /**
         * @var string
         *
         * @ORM\Column(name="mail", type="string", length=100, nullable=false)
         */
        private $mail;

        /**
         * @var enumtransaction
         *
         * @ORM\Column(name="transaction", type="enumtransaction", nullable=false)
         */
        private $transaction;

        /**
         * @var string
         *
         * @ORM\Column(name="type", type="string", length=50, nullable=false)
         */
        private $type;

        /**
         * @var string
         *
         * @ORM\Column(name="pieces", type="string", length=50, nullable=false)
         */
        private $rooms;

        /**
         * @var smallint
         *
         * @ORM\Column(name="arrond", type="smallint", nullable=false)
         */
        private $disctrictId;

        /**
         * @var integer
         *
         * @ORM\Column(name="rayon", type="smallint", nullable=false)
         */
        private $radius;

        /**
         * @var float
         *
         * @ORM\Column(name="budget_min", type="float", precision=10, scale=0, nullable=false)
         */
        private $budgetMin;

        /**
         * @var float
         *
         * @ORM\Column(name="budget_max", type="float", precision=10, scale=0, nullable=false)
         */
        private $budgetMax;

        /**
         * @var string
         *
         * @ORM\Column(name="keywords", type="string", length=255, nullable=false)
         */
        private $keywords;

        /**
         * @var boolean
         *
         * @ORM\Column(name="help", type="boolean", nullable=false)
         */
        private $isHelpEnabled;

        /**
         * @var boolean
         *
         * @ORM\Column(name="partenaires", type="boolean", nullable=false)
         */
        private $isPartnerContactEnabled;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="dateInscription", type="datetime", nullable=false)
         */
        private $createdAt;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="dateResiliation", type="datetime", nullable=true)
         */
        private $deletedAt;

        /**
         * @var string
         *
         * @ORM\Column(name="source", type="string", length=12, nullable=true)
         */
        private $source;

        /**
         * @var integer
         *
         * @ORM\Column(name="idUtilisateur", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var \Acreat\InseeBundle\Entity\InseeCity
         *
         * @ORM\ManyToOne(targetEntity="Acreat\InseeBundle\Entity\InseeCity")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="insee", referencedColumnName="codeInsee")
         * })
         */
        private $inseeCity;

        /**
         * @ORM\OneToMany(targetEntity="WebSearch", mappedBy="userSearch")
         */
        private $webSearches;

        /**
         *
         */
        public function __construct() {
            $this->webSearches = new ArrayCollection();
        }

        /**
         * Set civility
         *
         * @param string $civility
         * @return Search
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
         * Set lastname
         *
         * @param string $lastname
         * @return Search
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
         * Set firstname
         *
         * @param string $firstname
         * @return Search
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
         * Set postalcode
         *
         * @param string $postalcode
         * @return Search
         */
        public function setPostalcode($postalcode)
        {
            $this->postalcode = $postalcode;

            return $this;
        }

        /**
         * Get postalcode
         *
         * @return string
         */
        public function getPostalcode()
        {
            return $this->postalcode;
        }

        /**
         * Set mail
         *
         * @param string $mail
         * @return Search
         */
        public function setMail($mail)
        {
            $this->mail = $mail;

            return $this;
        }

        /**
         * Get mail
         *
         * @return string
         */
        public function getMail()
        {
            return $this->mail;
        }

        /**
         * Set transaction
         *
         * @param \enumtransaction $transaction
         * @return Search
         */
        public function setTransaction($transaction)
        {
            $this->transaction = $transaction;

            return $this;
        }

        /**
         * Get transaction
         *
         * @return \enumtransaction
         */
        public function getTransaction()
        {
            return $this->transaction;
        }

        /**
         * Set type
         *
         * @param string $type
         * @return Search
         */
        public function setType($type)
        {
            $this->type = $type;

            return $this;
        }

        /**
         * Get type
         *
         * @return string
         */
        public function getType()
        {
            return $this->type;
        }

        /**
         * Set rooms
         *
         * @param string $rooms
         * @return Search
         */
        public function setRooms($rooms)
        {
            $this->rooms = $rooms;

            return $this;
        }

        /**
         * Get rooms
         *
         * @return string
         */
        public function getRooms()
        {
            return $this->rooms;
        }

        /**
         * Set disctrictId
         *
         * @param integer  $disctrictId
         * @return Search
         */
        public function setDisctrictId($disctrictId)
        {
            $this->disctrictId = $disctrictId;

            return $this;
        }

        /**
         * Get disctrictId
         *
         * @return integer
         */
        public function getDisctrictId()
        {
            return $this->disctrictId;
        }

        /**
         * Set radius
         *
         * @param integer $radius
         * @return Search
         */
        public function setRadius($radius)
        {
            $this->radius = $radius;

            return $this;
        }

        /**
         * Get radius
         *
         * @return integer
         */
        public function getRadius()
        {
            return $this->radius;
        }

        /**
         * Set budgetMin
         *
         * @param float $budgetMin
         * @return Search
         */
        public function setBudgetMin($budgetMin)
        {
            $this->budgetMin = $budgetMin;

            return $this;
        }

        /**
         * Get budgetMin
         *
         * @return float
         */
        public function getBudgetMin()
        {
            return $this->budgetMin;
        }

        /**
         * Set budgetMax
         *
         * @param float $budgetMax
         * @return Search
         */
        public function setBudgetMax($budgetMax)
        {
            $this->budgetMax = $budgetMax;

            return $this;
        }

        /**
         * Get budgetMax
         *
         * @return float
         */
        public function getBudgetMax()
        {
            return $this->budgetMax;
        }

        /**
         * Set keywords
         *
         * @param string $keywords
         * @return Search
         */
        public function setKeywords($keywords)
        {
            $this->keywords = $keywords;

            return $this;
        }

        /**
         * Get keywords
         *
         * @return string
         */
        public function getKeywords()
        {
            return $this->keywords;
        }

        /**
         * Set isHelp
         *
         * @param boolean $isHelp
         * @return Search
         */
        public function setIsHelpEnabled($isHelp)
        {
            $this->isHelp = $isHelp;

            return $this;
        }

        /**
         * Get isHelp
         *
         * @return boolean
         */
        public function getIsHelpEnabled()
        {
            return $this->isHelp;
        }

        /**
         * Set isPartner
         *
         * @param boolean $isPartner
         * @return Search
         */
        public function setIsPartnerContactEnabled($isPartner)
        {
            $this->isPartner = $isPartner;

            return $this;
        }

        /**
         * Get isPartner
         *
         * @return boolean
         */
        public function getIsPartnerContactEnabled()
        {
            return $this->isPartner;
        }

        /**
         * Set createdAt
         *
         * @param \DateTime $createdAt
         * @return Search
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
         * Set deletedAt
         *
         * @param \DateTime $deletedAt
         * @return Search
         */
        public function setDeletedAt($deletedAt)
        {
            $this->deletedAt = $deletedAt;

            return $this;
        }

        /**
         * Get deletedAt
         *
         * @return \DateTime
         */
        public function getDeletedAt()
        {
            return $this->deletedAt;
        }

        /**
         * Set source
         *
         * @param string $source
         * @return Search
         */
        public function setSource($source)
        {
            $this->source = $source;

            return $this;
        }

        /**
         * Get source
         *
         * @return string
         */
        public function getSource()
        {
            return $this->source;
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
         * Set inseeCity
         *
         * @param \Acreat\InseeBundle\Entity\InseeCity $inseeCity
         * @return Search
         */
        public function setInseeCity(\Acreat\InseeBundle\Entity\InseeCity $inseeCity = null)
        {
            $this->inseeCity = $inseeCity;

            return $this;
        }

        /**
         * Get inseeCity
         *
         * @return \Acreat\InseeBundle\Entity\InseeCity
         */
        public function getInseeCity()
        {
            return $this->inseeCity;
        }

    }


}
