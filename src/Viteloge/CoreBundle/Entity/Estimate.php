<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * Estimate
     *
     * @ORM\Table(name="estimations")
     * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\EstimateRepository")
     */
    class Estimate
    {
        /**
         * @var json_array
         *
         * @ORM\Column(name="data", type="json_array", nullable=true)
         */
        protected $data;

        /**
         * @var string
         *
         * @ORM\Column(name="nom", type="string", length=255, nullable=true)
         */
        protected $lastname;

        /**
         * @var string
         *
         * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
         */
        protected $firstname;

        /**
         * @var string
         *
         * @ORM\Column(name="mail", type="string", length=255, nullable=true)
         * @Assert\NotBlank()
         * @Assert\Email()
         */
        protected $mail;

        /**
         * @var string
         *
         * @ORM\Column(name="tel", type="string", length=255, nullable=true)
         */
        protected $phone;

        /**
         * @var string
         *
         * @ORM\Column(name="type", type="string", length=1, nullable=false)
         * @Assert\NotBlank()
         */
        protected $type;

        /**
         * @var boolean
         *
         * @ORM\Column(name="demande_agence", type="boolean", nullable=false)
         */
        protected $agencyRequest;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="created_at", type="datetime", nullable=false)
         */
        protected $createdAt;

        /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        protected $id;

        /**
         * @var \Acreat\InseeBundle\Entity\InseeCity
         *
         * @ORM\ManyToOne(targetEntity="Acreat\InseeBundle\Entity\InseeCity")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="code_insee", referencedColumnName="codeInsee")
         * })
         * @Assert\Type(type="Acreat\InseeBundle\Entity\InseeCity")
         * @Assert\Valid()
         * @Assert\NotBlank()
         */
        protected $inseeCity;

        /**
         * Fields used in data column
         */
        private $fields = array(
            'numero',
            'type_voie',
            'voie',
            'codepostal',

            'nb_pieces',
            'nb_sdb',
            'surface_habitable',
            'surface_terrain',
            'exposition',
            'etage',
            'nb_etages',
            'nb_niveaux',
            'annee_construction',

            'ascenseur',
            'balcon',
            'terrasse',
            'parking',
            'garage',
            'travaux',
            'vue',
            'vue_detail',

            'proprietaire',
            'etat',
            'usage',
            'delai'
        );

        /**
         * Constructor
         * @return void
         */
        public function __construct() {
            $this->createdAt = new \DateTime('now');
            $this->data = array();
            $this->agencyRequest = false;
        }

        /**
         * Magic getter for fields
         *
         * @return mixed
         */
        public function __get( $name ) {
            $result = null;
            if ( in_array( $name, $this->fields ) ) {
                if ( array_key_exists( $name, $this->data ) ) {
                    $result = $this->data[$name];
                }
            } else {
                $trace = debug_backtrace();
                trigger_error(
                    'Undefined property via __get(): ' . $name .
                    ' in ' . $trace[0]['file'] .
                    ' on line ' . $trace[0]['line'],
                    E_USER_NOTICE);
            }
            return $result;
        }

        /**
         * Magic setter for fields
         *
         * @return Estimate
         */
        public function __set( $name, $value ) {
            if ( in_array( $name, $this->fields ) ) {
                $this->data[$name] = $value;
            } else {
                $trace = debug_backtrace();
                trigger_error(
                    'Undefined property via __get(): ' . $name .
                    ' in ' . $trace[0]['file'] .
                    ' on line ' . $trace[0]['line'],
                    E_USER_NOTICE);
            }
            return $this;
        }

        /**
         * Set data
         *
         * @param \json_array $data
         * @return Estimate
         */
        public function setData($data)
        {
            $this->data = $data;

            return $this;
        }

        /**
         * Get data
         *
         * @return \json_array
         */
        public function getData()
        {
            return $this->data;
        }

        /**
         * Set lastname
         *
         * @param string $lastname
         * @return Estimate
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
         * @return Estimate
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
         * Set mail
         *
         * @param string $mail
         * @return Estimate
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
         * Set phone
         *
         * @param string $phone
         * @return Estimate
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
         * Set type
         *
         * @param string $type
         * @return Estimate
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
         * Set agencyRequest
         *
         * @param boolean $agencyRequest
         * @return Estimate
         */
        public function setAgencyRequest($agencyRequest)
        {
            $this->agencyRequest = $agencyRequest;

            return $this;
        }

        /**
         * Get isAgencyRequest
         *
         * @return boolean
         */
        public function hasAgencyRequest()
        {
            return $this->agencyRequest;
        }

        /**
         * Set createdAt
         *
         * @param \DateTime $createdAt
         * @return Estimate
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
         * @return Estimate
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
