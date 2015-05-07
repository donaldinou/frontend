<?php

namespace Viteloge\EstimationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="barometre")
 * @ORM\Entity(repositoryClass="Viteloge\EstimationBundle\Entity\BarometreRepository")
 */
class Barometre{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $annee;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $mois;
    /**
     * @ORM\Id
     * @ORM\Column(type="string",length=5)
     */
    private $insee;
    /**
     * @ORM\Column(type="integer")
     */
    private $region;
    /**
     * @ORM\Id
     * @ORM\Column(type="string",length=3)
     */
    private $departement;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="string",length=1)
     */
    private $type;
    /**
     * @ORM\Id
     * @ORM\Column(type="string",length=1)
     */
    private $transaction;
    /**
     * @ORM\Column(type="integer")
     */
    private $nb;
    /**
     * @ORM\Column(type="float")
     */
    private $avg_sqm;
    

    /**
     * Set annee
     *
     * @param integer $annee
     * @return Barometre
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return integer 
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set mois
     *
     * @param integer $mois
     * @return Barometre
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return integer 
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set insee
     *
     * @param string $insee
     * @return Barometre
     */
    public function setInsee($insee)
    {
        $this->insee = $insee;

        return $this;
    }

    /**
     * Get insee
     *
     * @return string 
     */
    public function getInsee()
    {
        return $this->insee;
    }

    /**
     * Set region
     *
     * @param integer $region
     * @return Barometre
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return integer 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set departement
     *
     * @param string $departement
     * @return Barometre
     */
    public function setDepartement($departement)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return string 
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Barometre
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
     * @return Barometre
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
     * Set nb
     *
     * @param integer $nb
     * @return Barometre
     */
    public function setNb($nb)
    {
        $this->nb = $nb;

        return $this;
    }

    /**
     * Get nb
     *
     * @return integer 
     */
    public function getNb()
    {
        return $this->nb;
    }

    /**
     * Set avg_sqm
     *
     * @param float $avgSqm
     * @return Barometre
     */
    public function setAvgSqm($avgSqm)
    {
        $this->avg_sqm = $avgSqm;

        return $this;
    }

    /**
     * Get avg_sqm
     *
     * @return float 
     */
    public function getAvgSqm()
    {
        return $this->avg_sqm;
    }


    public function getAsDate() {
        return sprintf( '%4d%02d', $this->annee, $this->mois );
    }
}
