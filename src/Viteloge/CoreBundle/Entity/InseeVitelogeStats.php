<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * InseeVitelogeStats
     *
     * @ORM\Table(name="insee_viteloge_stats", indexes={@ORM\Index(name="count", columns={"count"}), @ORM\Index(name="count_priv", columns={"count_priv"}), @ORM\Index(name="codeDepartement", columns={"departement"}), @ORM\Index(name="codeRegion", columns={"region"})})
     * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\InseeVitelogeRepository")
     */
    class InseeVitelogeStats
    {
        /**
         * @var integer
         *
         * @ORM\Column(name="count", type="integer", nullable=false)
         */
        private $count;

        /**
         * @var integer
         *
         * @ORM\Column(name="count_priv", type="integer", nullable=false)
         */
        private $countPriv;

        /**
         * @var string
         *
         * @ORM\Column(name="transaction", type="string", length=1)
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="NONE")
         */
        private $transaction;

        /**
         * @var string
         *
         * @ORM\Column(name="type", type="string", length=1)
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="NONE")
         */
        private $type;

        /**
         * @var \Acreat\InseeBundle\Entity\InseeCity
         *
         * @ORM\ManyToOne(targetEntity="Acreat\InseeBundle\Entity\InseeCity")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="insee", referencedColumnName="codeInsee")
         * })
         */
        private $inseeCity;

        /**
         * @var \Acreat\InseeBundle\Entity\InseeDepartement
         *
         * @ORM\ManyToOne(targetEntity="Acreat\InseeBundle\Entity\InseeDepartment")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="departement", referencedColumnName="DEP")
         * })
         */
        private $inseeDepartement;

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
         * Set count
         *
         * @param integer $count
         * @return InseeVitelogeStats
         */
        public function setCount($count)
        {
            $this->count = $count;

            return $this;
        }

        /**
         * Get count
         *
         * @return integer
         */
        public function getCount()
        {
            return $this->count;
        }

        /**
         * Set countPriv
         *
         * @param integer $countPriv
         * @return InseeVitelogeStats
         */
        public function setCountPriv($countPriv)
        {
            $this->countPriv = $countPriv;

            return $this;
        }

        /**
         * Get countPriv
         *
         * @return integer
         */
        public function getCountPriv()
        {
            return $this->countPriv;
        }

        /**
         * Set transaction
         *
         * @param string $transaction
         * @return InseeVitelogeStats
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
         * @return InseeVitelogeStats
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
         * Set inseeCity
         *
         * @param \Acreat\InseeBundle\Entity\InseeCity $inseeCity
         * @return InseeVitelogeStats
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
         * Set inseeDepartement
         *
         * @param \Acreat\InseeBundle\Entity\InseeDepartement $inseeDepartement
         * @return InseeVitelogeStats
         */
        public function setInseeDepartement(\Acreat\InseeBundle\Entity\InseeDepartement $inseeDepartement = null)
        {
            $this->inseeDepartement = $inseeDepartement;

            return $this;
        }

        /**
         * Get inseeDepartement
         *
         * @return \Acreat\InseeBundle\Entity\InseeDepartement
         */
        public function getInseeDepartement()
        {
            return $this->inseeDepartement;
        }

        /**
         * Set inseeState
         *
         * @param \Acreat\InseeBundle\Entity\InseeState $inseeState
         * @return InseeVitelogeStats
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
    }


}
