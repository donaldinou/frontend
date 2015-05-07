<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * Barometer
     *
     * @ORM\Table(name="barometre", indexes={@ORM\Index(name="state", columns={"region"}), @ORM\Index(name="department", columns={"departement"}), @ORM\Index(name="itt", columns={"insee", "transaction", "type"})})
     * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\BarometerRepository")
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
         * @var \Acreat\InseeBundle\Entity\InseeCity
         *
         * @ORM\ManyToOne(targetEntity="Acreat\InseeBundle\Entity\InseeCity")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="insee", referencedColumnName="CodeInsee")
         * })
         */
        private $inseeCity;

        /**
         * @var \Acreat\InseeBundle\Entity\InseeState
         *
         * @ORM\ManyToOne(targetEntity="Acreat\InseeBundle\Entity\InseeState")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="region", referencedColumnName="REGION")
         * })
         */
        private $inseeState;

        /**
         * @var \Acreat\InseeBundle\Entity\InseeDepartment
         *
         * @ORM\ManyToOne(targetEntity="Acreat\InseeBundle\Entity\InseeDepartment")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="departement", referencedColumnName="DEP")
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
         * @param \Acreat\InseeBundle\Entity\InseeCity $inseeCity
         * @return Barometer
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
         * Set inseeState
         *
         * @param \Acreat\InseeBundle\Entity\InseeState $inseeState
         * @return Barometer
         */
        public function setInseeState(\Acreat\InseeBundle\Entity\InseeState $inseeState = null)
        {
            $this->inseeState = $inseeState;

            return $this;
        }

        /**
         * Get inseeState
         *
         * @return \Acreat\InseeBundle\Entity\InseeState
         */
        public function getInseeState()
        {
            return $this->inseeState;
        }

        /**
         * Set inseeDepartment
         *
         * @param \Acreat\InseeBundle\Entity\InseeDepartment $inseeDepartment
         * @return Barometer
         */
        public function setInseeDepartment(\Acreat\InseeBundle\Entity\InseeDepartment $inseeDepartment = null)
        {
            $this->inseeDepartment = $inseeDepartment;

            return $this;
        }

        /**
         * Get inseeDepartment
         *
         * @return \Acreat\InseeBundle\Entity\InseeDepartment
         */
        public function getInseeDepartment()
        {
            return $this->inseeDepartment;
        }
    }


}
