<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * CityData
     *
     * @ORM\Table(name="city_data")
     * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\CityDataRepository")
     */
    class CityData {

        /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        protected $id;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="created_at", type="datetime", nullable=false)
         */
        protected $createdAt;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="updated_at", type="datetime", nullable=false)
         */
        protected $updatedAt;

        /**
         * @var text
         *
         * @ORM\Column(name="description", type="text", nullable=true)
         */
        protected $description;

        /**
         * @var \Acreat\InseeBundle\Entity\InseeCity
         *
         * @ORM\OneToOne(targetEntity="Acreat\InseeBundle\Entity\InseeCity")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="code_insee", referencedColumnName="codeInsee")
         * })
         * @Assert\Type(type="Acreat\InseeBundle\Entity\InseeCity")
         * @Assert\Valid()
         */
        protected $inseeCity;

        /**
         *
         */
        public function __construct() {
            $this->createdAt = new \DateTime('now');
        }

        /**
         * Set description
         *
         * @param string $description
         * @return CityData
         */
        public function setDescription($description) {
            $this->description = $description;

            return $this;
        }

        /**
         * Get description
         *
         * @return string
         */
        public function getDescription() {
            return $this->description;
        }

        /**
         * Set inseeCity
         *
         * @param \Acreat\InseeBundle\Entity\InseeCity $inseeCity
         * @return CityData
         */
        public function setInseeCity(\Acreat\InseeBundle\Entity\InseeCity $inseeCity = null) {
            $this->inseeCity = $inseeCity;

            return $this;
        }

        /**
         * Get inseeCity
         *
         * @return \Acreat\InseeBundle\Entity\InseeCity
         */
        public function getInseeCity() {
            return $this->inseeCity;
        }

        /**
         * Set createdAt
         *
         * @param \DateTime $createdAt
         * @return CityData
         */
        public function setCreatedAt($createdAt) {
            $this->createdAt = $createdAt;

            return $this;
        }

        /**
         * Return DateTime from the year and month
         *
         * @return \DateTime
         */
        public function getCreatedAt() {
            return $this->createdAt;
        }

        /**
         * Set updatedAt
         *
         * @param \DateTime $updatedAt
         * @return Ad
         */
        public function setUpdatedAt($updatedAt) {
            $this->updatedAt = $updatedAt;

            return $this;
        }

        /**
         * Get updatedAt
         *
         * @return \DateTime
         */
        public function getUpdatedAt() {
            return $this->updatedAt;
        }

    }


}
