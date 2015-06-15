<?php

namespace Acreat\InseeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InseeArea
 *
 * @ORM\Table(name="insee_quartiers", indexes={@ORM\Index(name="cityId", columns={"insee"})})
 * @ORM\Entity(repositoryClass="Acreat\InseeBundle\Repository\InseeAreaRepository")
 */
class InseeArea
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="text", nullable=false)
     */
    protected $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="polyline", type="text", nullable=false)
     */
    protected $polyline;

    /**
     * @var string
     *
     * @ORM\Column(name="levels", type="string", length=255, nullable=false)
     */
    protected $levels;

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
     *   @ORM\JoinColumn(name="insee", referencedColumnName="codeInsee")
     * })
     */
    protected $inseeCity;



    /**
     * Set name
     *
     * @param string $name
     * @return InseeArea
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return InseeArea
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
     * Set polyline
     *
     * @param string $polyline
     * @return InseeArea
     */
    public function setPolyline($polyline)
    {
        $this->polyline = $polyline;

        return $this;
    }

    /**
     * Get polyline
     *
     * @return string
     */
    public function getPolyline()
    {
        return $this->polyline;
    }

    /**
     * Set levels
     *
     * @param string $levels
     * @return InseeArea
     */
    public function setLevels($levels)
    {
        $this->levels = $levels;

        return $this;
    }

    /**
     * Get levels
     *
     * @return string
     */
    public function getLevels()
    {
        return $this->levels;
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
     * @return InseeArea
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
