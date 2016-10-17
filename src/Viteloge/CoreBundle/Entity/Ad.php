<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;
    use FOS\ElasticaBundle\Configuration\Search;

    /**
     * Ad
     *
     * @Search(repositoryClass="Viteloge\CoreBundle\SearchRepository\AdRepository")
     * @ORM\Table(name="export_annonce", indexes={
     *     @ORM\Index(
     *         name="cityId",
     *         columns={"codeInsee"}
     *     ),
     *     @ORM\Index(
     *         name="agencyId",
     *         columns={"idAgence"}
     *     ),
     *     @ORM\Index(
     *         name="transaction",
     *         columns={"transaction"}
     *     ),
     *     @ORM\Index(
     *         name="rooms",
     *         columns={"nbpiece"}
     *     ),
     *     @ORM\Index(
     *         name="price",
     *         columns={"prix"}
     *     ),
     *     @ORM\Index(
     *         name="districtId",
     *         columns={"arrondissement"}
     *     ),
     *     @ORM\Index(
     *         name="agencySpecial",
     *         columns={"specifAgence"}
     *     ),
     *     @ORM\Index(
     *         name="privilegeId",
     *         columns={"idPrivilege"}
     *     ),
     *     @ORM\Index(
     *         name="privilegeCode",
     *         columns={"codePrivilege"}
     *     ),
     *     @ORM\Index(
     *         name="privilegeRank",
     *         columns={"rankPrivilege"}
     *     ),
     *     @ORM\Index(
     *         name="description",
     *         columns={"description"}),
     *     @ORM\Index(
     *         name="order",
     *         columns={"ordre"}
     *     ),
     *     @ORM\Index(
     *         name="createdAt",
     *         columns={"dateInsert"}
     *      ),
     *     @ORM\Index(
     *         name="updatedAt",
     *         columns={"dateUpdate"}
     *     )
     * })
     * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\AdRepository")
     * @ORM\HasLifecycleCallbacks
     */
    class Ad
    {

        /**
         * @var integer
         */
        const AGENCY_ID_NEW = 6725;

        /**
         * @var integer
         *
         * @ORM\Column(name="idAnnonce", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        protected $id;

        /**
         * @var integer
         *
         * @ORM\Column(name="idAgence", type="integer", nullable=false)
         */
        protected $agencyId;

        /**
         * @var string
         *
         * @ORM\Column(name="agence", type="string", length=255, nullable=false)
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
         * @var integer
         *
         * @ORM\Column(name="nbpiece", type="integer", nullable=false)
         */
        protected $rooms;

        /**
         * @var integer
         *
         * ORM\Column(name="bedrooms", type="integer", nullable=false)
         */
        protected $bedrooms;

        /**
         * @var integer
         *
         * @ORM\Column(name="Surface", type="integer", nullable=true)
         */
        protected $surface;

        /**
         * @var float
         *
         * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
         */
        protected $price;

        /**
         * @var string
         *
         * @ORM\Column(name="commune", type="string", length=150, nullable=false)
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
        protected $postalCode;

        /**
         * @var string
         *
         * @ORM\Column(name="description", type="text", nullable=false)
         */
        protected $description;

        /**
         * @var string
         *
         * @ORM\Column(name="description_mku", type="text", nullable=true)
         */
        protected $descriptionMku;

        /**
         * @var string
         *
         * @ORM\Column(name="photo", type="string", length=200, nullable=false)
         */
        protected $photo;

        /**
         * @var float
         *
         * @ORM\Column(name="ordre", type="float", precision=10, scale=0, nullable=false)
         */
        protected $order;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="dateInsert", type="datetime", nullable=false)
         */
        protected $createdAt;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="dateUpdate", type="datetime", nullable=false)
         */
        protected $updatedAt;

        /**
         * @var integer
         *
         * @ORM\Column(name="limitPublication", type="smallint", nullable=false)
         */
        protected $publicationLimit;

        /**
         * @var integer
         *
         * @ORM\Column(name="idPrivilege", type="integer", nullable=false)
         */
        protected $privilegeId;

        /**
         * @var string
         *
         * @ORM\Column(name="codePrivilege", type="string", length=10, nullable=false)
         */
        protected $privilegeCode;

        /**
         * @var integer
         *
         * @ORM\Column(name="rankPrivilege", type="smallint", nullable=false)
         */
        protected $privilegeRank;

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
         * @var \Viteloge\CoreBundle\Entity\Privilege
         */
        protected $privilege;

        /**
         * @var boolean
         */
        protected $isVitelogeNewAgency;

        /**
         * @var string
         */
        protected $program;

        /**
         * @var string
         */
        protected $programCount;

        /**
         * Constructor
         *
         * @return void
         */
        public function __construct() {
            $this->createdAt = new \DateTime('now');
            $this->isVitelogeNewAgency = false;
        }

        /**
         * Set agencyId
         *
         * @param integer $agencyId
         * @return Ad
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
         * @return Ad
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
         * @return Ad
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
         * Get agencyDomainName
         *
         * @return string
         */
        public function getAgencyDomainName() {
            $result = null;
            if (!empty($this->url)) {
                $result = parse_url($this->url, PHP_URL_HOST);
            }
            return $result;
        }

        /**
         * Set url
         *
         * @param string $url
         * @return Ad
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
         * @return Ad
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
         * @return Ad
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
         * @param integer $rooms
         * @return Ad
         */
        public function setRooms($rooms) {
            $this->rooms = $rooms;

            return $this;
        }

        /**
         * Get rooms
         *
         * @return integer
         */
        public function getRooms() {
            return $this->rooms;
        }

        /**
         * Set bedrooms
         *
         * @param integer $bedrooms
         * @return Ad
         */
        public function setBedrooms($bedrooms) {
            $this->bedrooms = $bedrooms;

            return $this;
        }

        /**
         * Get bedrooms
         *
         * @return integer
         */
        public function getBedrooms() {
            return $this->bedrooms;
        }

        /**
         * Set surface
         *
         * @param integer $surface
         * @return Ad
         */
        public function setSurface($surface)
        {
            $this->surface = $surface;

            return $this;
        }

        /**
         * Get surface
         *
         * @return integer
         */
        public function getSurface()
        {
            return $this->surface;
        }

        /**
         * Set price
         *
         * @param float $price
         * @return Ad
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
         * @return Ad
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
         * @return Ad
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
         * Set postalCode
         *
         * @param string $postalCode
         * @return Ad
         */
        public function setPostalCode($postalCode)
        {
            $this->postalCode = $postalCode;

            return $this;
        }

        /**
         * Get postalCode
         *
         * @return string
         */
        public function getPostalCode()
        {
            return $this->postalCode;
        }

        /**
         *
         */
        protected function keywordify($text) {
            // MOTS CLES
            if(!empty($_GET["keywords"])) {
                $keywords   = trim( preg_replace( array( "#/#", "/(\S)(\s+)(\S)/" ),array( "", "$1|$3" ), $_GET["keywords"] ) );
                $keywords_array = explode("|", $keywords);
                $kw_place = array();

                // Rep?age des mots cl? en dehors de la coupe
                foreach($keywords_array as $keyword)
                {
                    $pos = stripos($descriptif, $keyword);
                    while($pos !== false)
                    {
                        if($pos > $new_strlen)
                        {
                            $kw_place[$pos] = substr($descriptif, $pos, strlen($keyword));
                            break;
                        }
                        $pos = stripos($descriptif, $keyword, $pos+1);
                    }
                }

                ksort ( $kw_place );
                foreach( $kw_place as $keyword )
                    $new_descriptif .= " ".$keyword." ...";

                // Application du regex
                $new_descriptif = preg_replace("/{".preg_quote($keywords)."}/i","<span class='keyword'>\\0</span>",$new_descriptif);
            }
            return $new_descriptif;
        }

        /**
         * Set description
         *
         * @param string $description
         * @return Ad
         */
        public function setDescription($description) {
            $this->description = trim(ucfirst(strtolower(utf8_encode($description))));

            return $this;
        }

        /**
         * Get description
         *
         * @return string
         */
        public function getDescription() {
            /*$test_utf8_regex = "^([\\x00-\\x7f]|[\\xc2-\\xdf][\\x80-\\xbf]|\\xe0[\\xa0-\\xbf][\\x80-\\xbf]|[\\xe1-\\xec][\\x80-\\xbf]{2}|\\xed[\\x80-\\x9f][\\x80-\\xbf]|\\xef[\\x80-\\xbf][\\x80-\\xbc]|\\xee[\\x80-\\xbf]{2}|\\xf0[\\x90-\\xbf][\\x80-\\xbf]{2}|[\\xf1-\\xf3][\\x80-\\xbf]{3}|\\xf4[\\x80-\\x8f][\\x80-\\xbf]{2})*$";
            if(preg_match("/$test_utf8_regex/si", strtolower($this->description))) {
                $this->description = utf8_decode($this->description);
            }*/
           // return trim(ucfirst(strtolower($this->description)));
             return trim($this->description);
        }

        /**
         * Get description with advanced links
         *
         * @return strig
         */
        public function getAdvancedDescription() {
            $descriptionMku = $this->getDescriptionMku();
            return (!empty($descriptionMku)) ? $descriptionMku : $this->getDescription();
         //   return  $this->getDescription();
        }

        /**
         * Set descriptionMku
         *
         * @param string $descriptionMku
         * @return Ad
         */
        public function setDescriptionMku($descriptionMku) {
            $this->descriptionMku = trim(ucfirst(strtolower($descriptionMku)));

            return $this;
        }

        /**
         * Get descriptionMku
         *
         * @return string
         */
        public function getDescriptionMku() {
            return trim($this->descriptionMku);
        }

        /**
         * Set photo
         *
         * @param string $photo
         * @return Ad
         */
        public function setPhoto($photo)
        {
            $this->photo = $photo;

            return $this;
        }

        /**
         * Get photo
         *
         * @return string
         */
        public function getPhoto()
        {
            return $this->photo;
        }

        /**
         * Set order
         *
         * @param float $order
         * @return Ad
         */
        public function setOrder($order)
        {
            $this->order = $order;

            return $this;
        }

        /**
         * Get order
         *
         * @return float
         */
        public function getOrder()
        {
            return $this->order;
        }

        /**
         * Set createdAt
         *
         * @param \DateTime $createdAt
         * @return Ad
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
         * Set updatedAt
         *
         * @param \DateTime $updatedAt
         * @return Ad
         */
        public function setUpdatedAt($updatedAt)
        {
            $this->updatedAt = $updatedAt;

            return $this;
        }

        /**
         * Get updatedAt
         *
         * @return \DateTime
         */
        public function getUpdatedAt() {
            // BUGFIX : always return a valid DateTime in order to use cache policy
            return (!empty($this->updatedAt)) ? $this->updatedAt : new \DateTime();
        }

        /**
         * Set publicationLimit
         *
         * @param integer $publicationLimit
         * @return Ad
         */
        public function setPublicationLimit($publicationLimit)
        {
            $this->publicationLimit = $publicationLimit;

            return $this;
        }

        /**
         * Get publicationLimit
         *
         * @return integer
         */
        public function getPublicationLimit()
        {
            return $this->publicationLimit;
        }

        /**
         * Set privilegeId
         *
         * @param integer $privilegeId
         * @return Ad
         */
        public function setPrivilegeId($privilegeId)
        {
            $this->privilegeId = $privilegeId;

            return $this;
        }

        /**
         * Get privilegeId
         *
         * @return integer
         */
        public function getPrivilegeId()
        {
            return $this->privilegeId;
        }

        /**
         * Set privilegeCode
         *
         * @param string $privilegeCode
         * @return Ad
         */
        public function setPrivilegeCode($privilegeCode)
        {
            $this->privilegeCode = $privilegeCode;

            return $this;
        }

        /**
         * Get privilegeCode
         *
         * @return string
         */
        public function getPrivilegeCode()
        {
            return $this->privilegeCode;
        }

        /**
         * Set privilegeRank
         *
         * @param integer $privilegeRank
         * @return Ad
         */
        public function setPrivilegeRank($privilegeRank)
        {
            $this->privilegeRank = $privilegeRank;

            return $this;
        }

        /**
         * Get privilegeRank
         *
         * @return integer
         */
        public function getPrivilegeRank()
        {
            return $this->privilegeRank;
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
         * @return Ad
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

        /**
         * Get the privilege object from the privilege code
         *
         * @return string
         */
        public function getPrivilege() {
            if (!$this->privilege instanceof Privilege) {
                $this->privilege = new Privilege($this->privilegeCode);
            }
            return $this->privilege;
        }

        /**
         * Init program with matches array
         *
         * @param array $matches
         * @return Ad
         */
        private function initProgram(array $matches) {
            $this->program = $matches[1];
            $this->programCount = $matches[2];
            return $this;
        }

        /**
         * Is agency is from neuf.viteloge.com
         *
         * @return boolean
         */
        public function isVitelogeNewAgency() {
            $result = preg_match( '/^NEUF\[([^\]]*)\|(\d+)\]$/si', $this->getAgencyName(), $matches );
            if ($result && !$this->isVitelogeNewAgency) {
                $this->isVitelogeNewAgency = $result;
                $this->initProgram($matches);
            }
            return $result;
        }

        /**
         * Return the program
         *
         * @return string
         */
        public function getProgram() {
            return ($this->isVitelogeNewAgency()) ? $this->program : '';
        }

        /**
         * Set the program
         *
         * @param string $program
         * @return Ad
         */
        public function setProgram( $program ) {
            $this->program = $program;
        }

        /**
         * @return string
         */
        public function getProgramCount() {
            return ($this->isVitelogeNewAgency()) ? $this->programCount : '';
        }

        /**
         * Set The program count
         *
         * @param string $programCount
         * @return Ad
         */
        public function setProgramCount($programCount) {
            $this->programCount = $programcount;
        }

        /**
         * @ORM\PreUpdate
         */
        public function setUpdatedAtValue() {
            $this->updatedAt = new \DateTime();
        }

    }


}
