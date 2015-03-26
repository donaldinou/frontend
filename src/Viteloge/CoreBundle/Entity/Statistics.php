<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;

    /**
     * Statistics
     *
     * @ORM\Table(name="statistiques", indexes={@ORM\Index(name="date", columns={"date"})})
     * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\StatisticsRepository")
     */
    class Statistics
    {
        /**
         * @var \DateTime
         *
         * @ORM\Column(name="date", type="datetime", nullable=false)
         */
        private $date;

        /**
         * @var integer
         *
         * @ORM\Column(name="annee", type="smallint", nullable=false)
         */
        private $year;

        /**
         * @var integer
         *
         * @ORM\Column(name="mois", type="smallint", nullable=false)
         */
        private $month;

        /**
         * @var integer
         *
         * @ORM\Column(name="jour", type="smallint", nullable=false)
         */
        private $day;

        /**
         * @var string
         *
         * @ORM\Column(name="ip", type="string", length=15, nullable=false)
         */
        private $ip;

        /**
         * @var string
         *
         * @ORM\Column(name="UA", type="string", length=128, nullable=false)
         */
        private $ua;

        /**
         * @var integer
         *
         * @ORM\Column(name="idAgence", type="integer", nullable=false)
         */
        private $agencyId;

        /**
         * @var string
         *
         * @ORM\Column(name="agence", type="string", length=100, nullable=false)
         */
        private $agencyName;

        /**
         * @var string
         *
         * @ORM\Column(name="specifAgence", type="string", length=255, nullable=false)
         */
        private $agencySpecial;

        /**
         * @var string
         *
         * @ORM\Column(name="url", type="string", length=255, nullable=false)
         */
        private $url;

        /**
         * @var string
         *
         * @ORM\Column(name="transaction", type="string", length=1, nullable=false)
         */
        private $transaction;

        /**
         * @var string
         *
         * @ORM\Column(name="type", type="string", length=50, nullable=false)
         */
        private $type;

        /**
         * @var boolean
         *
         * @ORM\Column(name="nbpiece", type="boolean", nullable=false)
         */
        private $rooms;

        /**
         * @var float
         *
         * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
         */
        private $price;

        /**
         * @var string
         *
         * @ORM\Column(name="commune", type="string", length=255, nullable=false)
         */
        private $cityName;

        /**
         * @var integer
         *
         * @ORM\Column(name="arrondissement", type="smallint", nullable=false)
         */
        private $distictId;

        /**
         * @var string
         *
         * @ORM\Column(name="codepostal", type="string", length=8, nullable=false)
         */
        private $postalcode;

        /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var \Viteloge\CoreBundle\Entity\Ad
         *
         * @ORM\ManyToOne(targetEntity="Viteloge\CoreBundle\Entity\Ad")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="idAnnonce", referencedColumnName="idAnnonce")
         * })
         */
        private $ad;

        /**
         * @var \Acreat\InseeBundle\Entity\InseeCity
         *
         * @ORM\ManyToOne(targetEntity="Acreat\InseeBundle\Entity\InseeCity")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="codeInsee", referencedColumnName="codeInsee")
         * })
         */
        private $inseeCity;



        /**
         * Set date
         *
         * @param \DateTime $date
         * @return Statistics
         */
        public function setDate($date)
        {
            $this->date = $date;

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
         * @return Statistics
         */
        public function setYear($year)
        {
            $this->year = $year;

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
         * @return Statistics
         */
        public function setMonth($month)
        {
            $this->month = $month;

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
         * @return Statistics
         */
        public function setDay($day)
        {
            $this->day = $day;

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
         * @return Statistics
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
         * @return Statistics
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
         * Set agencyId
         *
         * @param integer $agencyId
         * @return Statistics
         */
        public function setAgencyId($agencyId)
        {
            $this->agencyId = $agencyId;

            return $this;
        }

        /**
         * Get agencyId
         *
         * @return integer
         */
        public function getAgencyId()
        {
            return $this->agencyId;
        }

        /**
         * Set agencyName
         *
         * @param string $agencyName
         * @return Statistics
         */
        public function setAgencyName($agencyName)
        {
            $this->agencyName = $agencyName;

            return $this;
        }

        /**
         * Get agencyName
         *
         * @return string
         */
        public function getAgencyName()
        {
            return $this->agencyName;
        }

        /**
         * Set agencySpecial
         *
         * @param string $agencySpecial
         * @return Statistics
         */
        public function setAgencySpecial($agencySpecial)
        {
            $this->agencySpecial = $agencySpecial;

            return $this;
        }

        /**
         * Get agencySpecial
         *
         * @return string
         */
        public function getAgencySpecial()
        {
            return $this->agencySpecial;
        }

        /**
         * Set url
         *
         * @param string $url
         * @return Statistics
         */
        public function setUrl($url)
        {
            $this->url = $url;

            return $this;
        }

        /**
         * Get url
         *
         * @return string
         */
        public function getUrl()
        {
            return $this->url;
        }

        /**
         * Set transaction
         *
         * @param string $transaction
         * @return Statistics
         */
        public function setTransaction($transaction)
        {
            $this->transaction = $transaction;

            return $this;
        }

        /**
         * Get transaction
         *
         * @return string
         */
        public function getTransaction()
        {
            return $this->transaction;
        }

        /**
         * Set type
         *
         * @param string $type
         * @return Statistics
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
         * @param boolean $rooms
         * @return Statistics
         */
        public function setRooms($rooms)
        {
            $this->rooms = $rooms;

            return $this;
        }

        /**
         * Get rooms
         *
         * @return boolean
         */
        public function getRooms()
        {
            return $this->rooms;
        }

        /**
         * Set price
         *
         * @param float $price
         * @return Statistics
         */
        public function setPrice($price)
        {
            $this->price = $price;

            return $this;
        }

        /**
         * Get price
         *
         * @return float
         */
        public function getPrice()
        {
            return $this->price;
        }

        /**
         * Set cityName
         *
         * @param string $cityName
         * @return Statistics
         */
        public function setCityName($cityName)
        {
            $this->cityName = $cityName;

            return $this;
        }

        /**
         * Get cityName
         *
         * @return string
         */
        public function getCityName()
        {
            return $this->cityName;
        }

        /**
         * Set distictId
         *
         * @param integer $distictId
         * @return Statistics
         */
        public function setDistictId($distictId)
        {
            $this->distictId = $distictId;

            return $this;
        }

        /**
         * Get distictId
         *
         * @return integer
         */
        public function getDistictId()
        {
            return $this->distictId;
        }

        /**
         * Set postalcode
         *
         * @param string $postalcode
         * @return Statistics
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
         * Get id
         *
         * @return integer
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set ad
         *
         * @param \Viteloge\CoreBundle\Entity\Ad $ad
         * @return Statistics
         */
        public function setAd(\Viteloge\CoreBundle\Entity\Ad $ad = null)
        {
            $this->ad = $ad;

            return $this;
        }

        /**
         * Get ad
         *
         * @return \Viteloge\CoreBundle\Entity\Ad
         */
        public function getAd()
        {
            return $this->ad;
        }

        /**
         * Set inseeCity
         *
         * @param \Acreat\InseeBundle\Entity\InseeCity $inseeCity
         * @return Statistics
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

