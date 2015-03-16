<?php

namespace Viteloge\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ad
 *
 * @ORM\Table(name="export_annonce", indexes={@ORM\Index(name="cityId", columns={"codeInsee"}), @ORM\Index(name="agencyId", columns={"idAgence"}), @ORM\Index(name="transaction", columns={"transaction"}), @ORM\Index(name="rooms", columns={"nbpiece"}), @ORM\Index(name="price", columns={"prix"}), @ORM\Index(name="districtId", columns={"arrondissement"}), @ORM\Index(name="agencySpecial", columns={"specifAgence"}), @ORM\Index(name="privilegeId", columns={"idPrivilege"}), @ORM\Index(name="privilegeCode", columns={"codePrivilege"}), @ORM\Index(name="privilegeRank", columns={"rankPrivilege"}), @ORM\Index(name="description", columns={"description"}), @ORM\Index(name="order", columns={"ordre"}), @ORM\Index(name="createdAt", columns={"dateInsert"}), @ORM\Index(name="updatedAt", columns={"dateUpdate"})})
 * @ORM\Entity
 */
class Ad
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idAgence", type="integer", nullable=false)
     */
    private $agencyId;

    /**
     * @var string
     *
     * @ORM\Column(name="agence", type="string", length=255, nullable=false)
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
     * @var integer
     *
     * @ORM\Column(name="nbpiece", type="integer", nullable=false)
     */
    private $rooms;

    /**
     * @var integer
     *
     * @ORM\Column(name="Surface", type="integer", nullable=true)
     */
    private $surface;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="commune", type="string", length=150, nullable=false)
     */
    private $cityName;

    /**
     * @var smaillint
     *
     * @ORM\Column(name="arrondissement", type="smaillint", nullable=false)
     */
    private $districtId;

    /**
     * @var string
     *
     * @ORM\Column(name="codepostal", type="string", length=8, nullable=false)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="description_mku", type="text", nullable=true)
     */
    private $descriptionMku;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=200, nullable=false)
     */
    private $photo;

    /**
     * @var float
     *
     * @ORM\Column(name="ordre", type="float", precision=10, scale=0, nullable=false)
     */
    private $order;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateInsert", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateUpdate", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="limitPublication", type="smallint", nullable=false)
     */
    private $publicationLimit;

    /**
     * @var integer
     *
     * @ORM\Column(name="idPrivilege", type="integer", nullable=false)
     */
    private $privilegeId;

    /**
     * @var string
     *
     * @ORM\Column(name="codePrivilege", type="string", length=10, nullable=false)
     */
    private $privilegeCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="rankPrivilege", type="smallint", nullable=false)
     */
    private $privilegeRank;

    /**
     * @var integer
     *
     * @ORM\Column(name="idAnnonce", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Viteloge\CoreBundle\Entity\InseeCity
     *
     * @ORM\ManyToOne(targetEntity="Viteloge\CoreBundle\Entity\InseeCity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="codeInsee", referencedColumnName="id")
     * })
     */
    private $inseeCity;



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
    public function setRooms($rooms)
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * Get rooms
     *
     * @return integer 
     */
    public function getRooms()
    {
        return $this->rooms;
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
     * @param \smaillint $districtId
     * @return Ad
     */
    public function setDistrictId(\smaillint $districtId)
    {
        $this->districtId = $districtId;

        return $this;
    }

    /**
     * Get districtId
     *
     * @return \smaillint 
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
     * Set description
     *
     * @param string $description
     * @return Ad
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set descriptionMku
     *
     * @param string $descriptionMku
     * @return Ad
     */
    public function setDescriptionMku($descriptionMku)
    {
        $this->descriptionMku = $descriptionMku;

        return $this;
    }

    /**
     * Get descriptionMku
     *
     * @return string 
     */
    public function getDescriptionMku()
    {
        return $this->descriptionMku;
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
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
     * @param \Viteloge\CoreBundle\Entity\InseeCity $inseeCity
     * @return Ad
     */
    public function setInseeCity(\Viteloge\CoreBundle\Entity\InseeCity $inseeCity = null)
    {
        $this->inseeCity = $inseeCity;

        return $this;
    }

    /**
     * Get inseeCity
     *
     * @return \Viteloge\CoreBundle\Entity\InseeCity 
     */
    public function getInseeCity()
    {
        return $this->inseeCity;
    }
}
