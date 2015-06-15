<?php

namespace Acreat\InseeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InseeCityhall
 *
 * @ORM\Table(name="insee_mairies", indexes={@ORM\Index(name="population", columns={"population"})})
 * @ORM\Entity(repositoryClass="Acreat\InseeBundle\Repository\InseeCityhallRepository")
 */
class InseeCityhall
{
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=false)
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=5, nullable=false)
     */
    protected $postalcode;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=false)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=50, nullable=false)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=50, nullable=false)
     */
    protected $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=false)
     */
    protected $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="maire", type="string", length=255, nullable=false)
     */
    protected $mayor;

    /**
     * @var integer
     *
     * @ORM\Column(name="population", type="integer", nullable=false)
     */
    protected $population;

    /**
     * @var string
     *
     * @ORM\Column(name="codeInsee", type="string", length=5)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $cityId;



    /**
     * Set address
     *
     * @param string $address
     * @return InseeCityhall
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set postalcode
     *
     * @param string $postalcode
     * @return InseeCityhall
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
     * Set city
     *
     * @param string $city
     * @return InseeCityhall
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return InseeCityhall
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
     * Set fax
     *
     * @param string $fax
     * @return InseeCityhall
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return InseeCityhall
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
     * Set url
     *
     * @param string $url
     * @return InseeCityhall
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
     * Set mayor
     *
     * @param string $mayor
     * @return InseeCityhall
     */
    public function setMayor($mayor)
    {
        $this->mayor = $mayor;

        return $this;
    }

    /**
     * Get mayor
     *
     * @return string
     */
    public function getMayor()
    {
        return $this->mayor;
    }

    /**
     * Set population
     *
     * @param integer $population
     * @return InseeCityhall
     */
    public function setPopulation($population)
    {
        $this->population = $population;

        return $this;
    }

    /**
     * Get population
     *
     * @return integer
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * Get cityId
     *
     * @return string
     */
    public function getCityId()
    {
        return $this->cityId;
    }
}
