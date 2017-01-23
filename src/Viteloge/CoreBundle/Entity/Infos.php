<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;
    use Viteloge\CoreBundle\Entity\Ad;


    /**
     * Contacts
     *
     * @ORM\Table(name="infos", indexes={@ORM\Index(name="date", columns={"date"})})
     * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\InfosRepository")
     */
    class Infos
    {
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
         * @var string
         *
         * @ORM\Column(name="genre", type="string", length=15, nullable=false)
         */
        protected $genre;

        /**
         * @var integer
         *
         * @ORM\Column(name="idAgence", type="integer", nullable=false)
         */
        protected $agencyId;

        /**
         * @var string
         *
         * @ORM\Column(name="agence", type="string", length=100, nullable=false)
         */
        protected $agencyName;

        /**
         * @var string
         *
         * @ORM\Column(name="specifAgence", type="string", length=255, nullable=false)
         */
        protected $agencySpecial;

        /**
         * @var string
         *
         * @ORM\Column(name="url", type="string", length=255, nullable=false)
         */
        protected $url;

        /**
         * @var string
         *
         * @ORM\Column(name="transaction", type="string", length=1, nullable=false)
         */
        protected $transaction;

        /**
         * @var string
         *
         * @ORM\Column(name="type", type="string", length=50, nullable=false)
         */
        protected $type;

        /**
         * @var boolean
         *
         * @ORM\Column(name="nbpiece", type="integer", nullable=false)
         */
        protected $rooms;

        /**
         * @var float
         *
         * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
         */
        protected $price;

        /**
         * @var string
         *
         * @ORM\Column(name="commune", type="string", length=255, nullable=false)
         */
        protected $cityName;

        /**
         * @var integer
         *
         * @ORM\Column(name="arrondissement", type="smallint", nullable=false)
         */
        protected $districtId;

        /**
         * @var string
         *
         * @ORM\Column(name="codepostal", type="string", length=8, nullable=false)
         */
        protected $postalcode;

        /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @var \Viteloge\CoreBundle\Entity\Ad
         *
         * @ORM\ManyToOne(targetEntity="Viteloge\CoreBundle\Entity\Ad")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="idAnnonce", referencedColumnName="idAnnonce")
         * })
         */
        protected $ad;

        /**
         * @var \Acreat\InseeBundle\Entity\InseeCity
         *
         * @ORM\ManyToOne(targetEntity="Acreat\InseeBundle\Entity\InseeCity")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="codeInsee", referencedColumnName="codeInsee")
         * })
         */
        protected $inseeCity;


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
         *
         */
        public function initFromAd(Ad $ad) {
            $this->setAd($ad);
            $this->setInseeCity($ad->getInseeCity());
            $this->setAgencyId($ad->getAgencyId());
            $this->setAgencyName($ad->getAgencyName());
            $this->setAgencySpecial($ad->getAgencySpecial());
            $this->setUrl($ad->getUrl());
            $this->setTransaction($ad->getTransaction());
            $this->setType($ad->getType());
            $this->setRooms($ad->getRooms());
            $this->setPrice($ad->getPrice());
            $this->setCityName($ad->getCityName());
            $this->setDistrictId($ad->getDistrictId());
            $this->setPostalCode($ad->getPostalCode());

            return $this;
        }

        /**
         *
         */
        public function initFromSearchAd(Ad $ad,$url) {

            $this->setAd($ad);
            $this->setInseeCity($ad->getInseeCity());
            $this->setAgencyId($ad->getAgencyId());
            $this->setAgencyName($ad->getAgencyName());
            $this->setAgencySpecial($ad->getAgencySpecial());
            $this->setTransaction($ad->getTransaction());
            $this->setType($ad->getType());
            $this->setRooms($ad->getRooms());
            $this->setPrice($ad->getPrice());
            $this->setCityName($ad->getCityName());
            $this->setDistrictId($ad->getDistrictId());
            $this->setPostalCode($ad->getPostalCode());
            $this->setUrl($url);

            return $this;
        }

        /**
         * Set date
         *
         * @param \DateTime $date
         * @return Infos
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
         * @return Infos
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
         * @return Infos
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
         * @return Infos
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
         * @return Infos
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
         * @return Infos
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
         * Set genre
         *
         * @param string $genre
         * @return Infos
         */
        public function setGenre($genre)
        {
            $this->genre = $genre;

            return $this;
        }

        /**
         * Get genre
         *
         * @return string
         */
        public function getGenre()
        {
            return $this->genre;
        }

        /**
         * Set agencyId
         *
         * @param integer $agencyId
         * @return Infos
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
         * @return Infos
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
         * @return Infos
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
         * @return Infos
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
         * @return Infos
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
         * @return Infos
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
         * @return Infos
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
         * @return Infos
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
         * @return Infos
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
         * Set districtId
         *
         * @param integer $districtId
         * @return Infos
         */
        public function setDistrictId($districtId)
        {
            $this->districtId = $districtId;

            return $this;
        }

        /**
         * Get districtId
         *
         * @return integer
         */
        public function getDistrictId()
        {
            return $this->districtId;
        }

        /**
         * Set postalcode
         *
         * @param string $postalcode
         * @return Infos
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
         * @return Infos
         */
        public function setAd(Ad $ad = null)
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
         * @return Infos
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
