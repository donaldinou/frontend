<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * Distance
     *
     * @ORM\Table(name="distancier", indexes={@ORM\Index(name="distance", columns={"distance"}), @ORM\Index(name="cityIdFrom", columns={"codeInseeFrom"}), @ORM\Index(name="cityIdTo", columns={"codeInseeTo"})})
     * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\DistanceRepository")
     */
    class Distance
    {
        /**
         * @var float
         *
         * @ORM\Column(name="distance", type="float", precision=10, scale=0, nullable=false)
         */
        protected $distance;

        /**
         * @var float
         *
         * @ORM\Column(name="google_distance", type="float", precision=10, scale=0, nullable=false)
         */
        protected $googleDistance;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="google_time", type="time", nullable=false)
         */
        protected $googleTime;

        /**
         * @var string
         *
         * @ORM\Column(name="codeInseeFrom", type="string")
         * @ORM\Id
         */
        protected $codeInseeFrom;

        /**
         * @var string
         *
         * @ORM\Column(name="codeInseeTo", type="string")
         * @ORM\Id
         */
        protected $codeInseeTo;

        /**
         * @var \Acreat\InseeBundle\Entity\InseeCity
         *
         * @ORM\ManyToOne(targetEntity="Acreat\InseeBundle\Entity\InseeCity")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="codeInseeFrom", referencedColumnName="codeInsee")
         * })
         */
        protected $inseeFrom;

        /**
         * @var \Acreat\InseeBundle\Entity\InseeCity
         *
         * @ORM\ManyToOne(targetEntity="Acreat\InseeBundle\Entity\InseeCity")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="codeInseeTo", referencedColumnName="codeInsee")
         * })
         */
        protected $inseeTo;



        /**
         * Set distance
         *
         * @param float $distance
         * @return Distance
         */
        public function setDistance($distance)
        {
            $this->distance = $distance;

            return $this;
        }

        /**
         * Get distance
         *
         * @return float
         */
        public function getDistance()
        {
            return $this->distance;
        }

        /**
         * Set googleDistance
         *
         * @param float $googleDistance
         * @return Distance
         */
        public function setGoogleDistance($googleDistance)
        {
            $this->googleDistance = $googleDistance;

            return $this;
        }

        /**
         * Get googleDistance
         *
         * @return float
         */
        public function getGoogleDistance()
        {
            return $this->googleDistance;
        }

        /**
         * Set googleTime
         *
         * @param \DateTime $googleTime
         * @return Distance
         */
        public function setGoogleTime($googleTime)
        {
            $this->googleTime = $googleTime;

            return $this;
        }

        /**
         * Get googleTime
         *
         * @return \DateTime
         */
        public function getGoogleTime()
        {
            return $this->googleTime;
        }

        /**
         * Set inseeFrom
         *
         * @param \Acreat\InseeBundle\Entity\InseeCity $inseeFrom
         * @return Distance
         */
        public function setInseeFrom(\Acreat\InseeBundle\Entity\InseeCity $inseeFrom = null)
        {
            $this->inseeFrom = $inseeFrom;

            return $this;
        }

        /**
         * Get inseeFrom
         *
         * @return \Acreat\InseeBundle\Entity\InseeCity
         */
        public function getInseeFrom()
        {
            return $this->inseeFrom;
        }

        /**
         * Set inseeTo
         *
         * @param \Acreat\InseeBundle\Entity\InseeCity $inseeTo
         * @return Distance
         */
        public function setInseeTo(\Acreat\InseeBundle\Entity\InseeCity $inseeTo = null)
        {
            $this->inseeTo = $inseeTo;

            return $this;
        }

        /**
         * Get inseeTo
         *
         * @return \Acreat\InseeBundle\Entity\InseeCity
         */
        public function getInseeTo()
        {
            return $this->inseeTo;
        }
    }


}
