<?php

namespace Acreat\InseeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InseeState
 *
 * @ORM\Table(name="insee_regions", indexes={@ORM\Index(name="cityId", columns={"CHEFLIEU"})})
 * @ORM\Entity(repositoryClass="Acreat\InseeBundle\Repository\InseeStateRepository")
 */
class InseeState
{
    /**
     * @var string
     *
     * @ORM\Column(name="TNCC", type="string", length=1, nullable=true)
     */
    private $prefix;

    /**
     * @var string
     *
     * @ORM\Column(name="NCC", type="string", length=70, nullable=true)
     */
    private $uname;

    /**
     * @var string
     *
     * @ORM\Column(name="NCCENR", type="string", length=70, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="REGION", type="string", length=2)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Acreat\InseeBundle\Entity\InseeCity
     *
     * @ORM\ManyToOne(targetEntity="Acreat\InseeBundle\Entity\InseeCity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CHEFLIEU", referencedColumnName="codeInsee")
     * })
     */
    private $inseeCapital;



    /**
     * Set prefix
     *
     * @param string $prefix
     * @return InseeState
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set uname
     *
     * @param string $uname
     * @return InseeState
     */
    public function setUname($uname)
    {
        $this->uname = $uname;

        return $this;
    }

    /**
     * Get uname
     *
     * @return string
     */
    public function getUname()
    {
        return $this->uname;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return InseeState
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
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set inseeCapital
     *
     * @param \Acreat\InseeBundle\Entity\InseeCity $inseeCapital
     * @return InseeState
     */
    public function setInseeCapital(\Acreat\InseeBundle\Entity\InseeCity $inseeCapital = null)
    {
        $this->inseeCapital = $inseeCapital;

        return $this;
    }

    /**
     * Get inseeCapital
     *
     * @return \Acreat\InseeBundle\Entity\InseeCity
     */
    public function getInseeCapital()
    {
        return $this->inseeCapital;
    }
}
