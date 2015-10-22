<?php

namespace Viteloge\FrontendBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;
    use Acreat\InseeBundle\Entity\InseeCity;

    /**
     * @ORM\Entity
     */
    class Api {

        /**
         * @var integer
         *
         * @ORM\Id
         * @ORM\Column(name="id", type="integer")
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var \Acreat\InseeBundle\Entity\InseeCity
         *
         * @Assert\Type(type="Acreat\InseeBundle\Entity\InseeCity")
         * @Assert\Valid()
         * @Assert\NotBlank()
         */
        protected $inseeCity;

        /**
         * Constructor
         */
        public function __construct() {
            $this->inseeCity = null;
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
         * Set inseeCity
         *
         * @param \Acreat\InseeBundle\Entity\InseeCity $inseeCity
         * @return Api
         */
        public function setInseeCity(\Acreat\InseeBundle\Entity\InseeCity $inseeCity) {
            $this->inseeCity = $inseeCity;

            return $this;
        }

    }

}
