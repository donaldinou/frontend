<?php

namespace Viteloge\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Barometer
 *
 * @ORM\Table(name="barometre", indexes={@ORM\Index(name="state", columns={"region"}), @ORM\Index(name="department", columns={"departement"}), @ORM\Index(name="itt", columns={"insee", "transaction", "type"})})
 * @ORM\Entity
 */
class Barometer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="nb", type="integer", nullable=false)
     */
    private $total;

    /**
     * @var float
     *
     * @ORM\Column(name="avg_sqm", type="float", precision=10, scale=0, nullable=false)
     */
    private $avgSqm;

    /**
     * @var integer
     *
     * @ORM\Column(name="annee", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $year;

    /**
     * @var integer
     *
     * @ORM\Column(name="mois", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $month;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=1)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction", type="string", length=1)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $transaction;

    /**
     * @var \Viteloge\CoreBundle\Entity\InseeCity
     *
     * @ORM\ManyToOne(targetEntity="Viteloge\CoreBundle\Entity\InseeCity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="insee", referencedColumnName="id")
     * })
     */
    private $inseeCity;

    /**
     * @var \Viteloge\CoreBundle\Entity\InseeState
     *
     * @ORM\ManyToOne(targetEntity="Viteloge\CoreBundle\Entity\InseeState")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region", referencedColumnName="id")
     * })
     */
    private $inseeState;

    /**
     * @var \Viteloge\CoreBundle\Entity\InseeDepartment
     *
     * @ORM\ManyToOne(targetEntity="Viteloge\CoreBundle\Entity\InseeDepartment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="departement", referencedColumnName="id")
     * })
     */
    private $inseeDepartment;



    /**
     * Set total
     *
     * @param integer $total
     * @return Barometer
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set avgSqm
     *
     * @param float $avgSqm
     * @return Barometer
     */
    public function setAvgSqm($avgSqm)
    {
        $this->avgSqm = $avgSqm;

        return $this;
    }

    /**
     * Get avgSqm
     *
     * @return float 
     */
    public function getAvgSqm()
    {
        return $this->avgSqm;
    }

    /**
     * Set year
     *
     * @param integer $year
     * @return Barometer
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
     * @return Barometer
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
     * Set type
     *
     * @param string $type
     * @return Barometer
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
     * @param string $transaction
     * @return Barometer
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
     * Set inseeCity
     *
     * @param \Viteloge\CoreBundle\Entity\InseeCity $inseeCity
     * @return Barometer
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

    /**
     * Set inseeState
     *
     * @param \Viteloge\CoreBundle\Entity\InseeState $inseeState
     * @return Barometer
     */
    public function setInseeState(\Viteloge\CoreBundle\Entity\InseeState $inseeState = null)
    {
        $this->inseeState = $inseeState;

        return $this;
    }

    /**
     * Get inseeState
     *
     * @return \Viteloge\CoreBundle\Entity\InseeState 
     */
    public function getInseeState()
    {
        return $this->inseeState;
    }

    /**
     * Set inseeDepartment
     *
     * @param \Viteloge\CoreBundle\Entity\InseeDepartment $inseeDepartment
     * @return Barometer
     */
    public function setInseeDepartment(\Viteloge\CoreBundle\Entity\InseeDepartment $inseeDepartment = null)
    {
        $this->inseeDepartment = $inseeDepartment;

        return $this;
    }

    /**
     * Get inseeDepartment
     *
     * @return \Viteloge\CoreBundle\Entity\InseeDepartment 
     */
    public function getInseeDepartment()
    {
        return $this->inseeDepartment;
    }
}
