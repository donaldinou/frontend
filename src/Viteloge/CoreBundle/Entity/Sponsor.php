<?php

namespace Viteloge\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sponsor
 *
 * @ORM\Table(name="sponsor")
 * @ORM\Entity
 */
class Sponsor
{
    /**
     * @var string
     *
     * @ORM\Column(name="typeSponsor", type="string", length=20, nullable=false)
     */
    private $type;

    /**
     * @var enumtransaction
     *
     * @ORM\Column(name="transacSponsor", type="enumtransaction", nullable=false)
     */
    private $transaction;

    /**
     * @var string
     *
     * @ORM\Column(name="titreSponsor", type="string", length=50, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="texteSponsor", type="string", length=100, nullable=false)
     */
    private $data;

    /**
     * @var string
     *
     * @ORM\Column(name="urlSponsor", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="linkSponsor", type="string", length=255, nullable=false)
     */
    private $link;

    /**
     * @var integer
     *
     * @ORM\Column(name="idSponsor", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Viteloge\CoreBundle\Entity\InseeCity
     *
     * @ORM\ManyToOne(targetEntity="Viteloge\CoreBundle\Entity\InseeCity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="inseeSponsor", referencedColumnName="id")
     * })
     */
    private $inseeCity;



    /**
     * Set type
     *
     * @param string $type
     * @return Sponsor
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
     * Set transaction
     *
     * @param \enumtransaction $transaction
     * @return Sponsor
     */
    public function setTransaction(\enumtransaction $transaction)
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
     * Set title
     *
     * @param string $title
     * @return Sponsor
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set data
     *
     * @param string $data
     * @return Sponsor
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Sponsor
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
     * Set link
     *
     * @param string $link
     * @return Sponsor
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
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
     * @return Sponsor
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
