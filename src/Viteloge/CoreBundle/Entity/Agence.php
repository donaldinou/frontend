<?php

namespace Viteloge\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Viteloge\CoreBundle\Entity\Agence
 *
 * @ORM\Table(name="agence")
 * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\AgenceRepository")
 */
class Agence
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="idAgence", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="idAgenceMere",type="integer",nullable=true)
     */
    private $idAgenceMere;

    /**
     * @ORM\Column(name="specifAgence",type="string",length=255)
     */
    private $specif = '';
    /**
     * @ORM\Column(name="nomAgence",type="string",length=255)
     */
    private $nom;
    /**
     * @ORM\Column(name="mailAgence",type="string",length=255)
     */
    private $mail = '';
    /**
     * @ORM\Column(name="adresseAgence",type="string",length=255)
     */
    private $adresse = '';
    /**
     * @ORM\Column(name="cpAgence",type="string",length=5)
     */
    private $cp = '';
    /**
     * @ORM\Column(name="villeAgence",type="string",length=50)
     */
    private $ville = '';
    /**
     * @ORM\Column(name="telAgence",type="string",length=15)
     */
    private $tel = '';
    /**
     * @ORM\Column(name="faxAgence",type="string",length=15)
     */
    private $fax = '';
    /**
     * @ORM\Column(name="urlAgence",type="string",length=255)
     */
    private $url = '';
    /**
     * @ORM\Column(name="civiliteContactAgence",type="string",length=20)
     */
    private $civiliteContact = '';
    /**
     * @ORM\Column(name="nomContactAgence",type="string",length=100)
     */
    private $nomContact = '';
    /**
     * @ORM\Column(name="dptAgence",type="string",length=255)
     */
    private $departement = '';
    /**
     * @ORM\Column(name="nbAnnonceAgence",type="integer")
     */
    private $nbAnnonce = '';
    /**
     * @ORM\Column(name="dateCreation",type="date")
     */
    private $dateCreation;
    /**
     * @ORM\Column(name="idPrivilege",type="integer",nullable=true)
     */
    private $idPrivilege;
    /**
     * @ORM\Column(name="inactive",type="boolean")
     */
    private $inactive;

    /**
     * @ORM\OneToMany(targetEntity="Agence", mappedBy="agenceMere")
     */
    private $filles;
    /**
     * @ORM\ManyToOne(targetEntity="Agence",inversedBy="filles",fetch="EAGER" )
     * @ORM\JoinColumn(name="idAgenceMere", referencedColumnName="idAgence")
     */
    private $agenceMere;



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
     * Methode magique __get()
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Methode magique __set()
     */
    public function __set($property, $value)
    {
        if ( is_null( $value ) && ! is_null( $this->$property ) ) {
            $value = '';
        }
        $this->$property = $value;
    }
    public function __isset($name)
    {
        return property_exists($this, $name);
    }

    public function __toString()
    {
        if ( $this->nom ) {
            return $this->nom;
        } else {
            return "";
        }
    }

    public function getCountTraitements()
    {
        return count( $this->traitements );
    }
    public function getActive()
    {
        return ! $this->inactive;
    }

    public function getNomAgenceMere()
    {
        if ( 0 == $this->idAgenceMere ) {
            return null;
        }
        return $this->agenceMere->nom;
    }

    public function getHasXml()
    {
        return count( $this->xml_feeds ) > 0;
    }

    public function getPrivilegiee()
    {
        return $this->idPrivilege != 0;
    }

    public function getAncienne()
    {
        return preg_match( '/^\(ancien client\)/i', $this->nom );
    }

    public function getTel(){
        return $this->tel;
    }


}
