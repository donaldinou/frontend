<?php

namespace Acreat\InseeBundle\Entity {

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * InseeDepartment
     *
     * @ORM\Table(name="insee_departements", indexes={@ORM\Index(name="stateId", columns={"REGION"}), @ORM\Index(name="cityId", columns={"CHEFLIEU"})})
     * @ORM\Entity(repositoryClass="Acreat\InseeBundle\Repository\InseeDepartmentRepository")
     */
    class InseeDepartment extends InseeEntity {

        /**
         * @var string
         *
         * @ORM\Column(name="TNCC", type="string", length=1, nullable=true)
         */
        protected $prefix;

        /**
         * @var string
         *
         * @ORM\Column(name="NCC", type="string", length=70, nullable=true)
         */
        protected $uname;

        /**
         * @var string
         *
         * @ORM\Column(name="NCCENR", type="string", length=70, nullable=true)
         */
        protected $name;

        /**
         * @var string
         *
         * @ORM\Column(name="DEP", type="string", length=3)
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        protected $id;

        /**
         * @var \Acreat\InseeBundle\Entity\InseeState
         *
         * @ORM\ManyToOne(targetEntity="Acreat\InseeBundle\Entity\InseeState", inversedBy="inseeDepartments")
         * @ORM\JoinColumns({
         *      @ORM\JoinColumn(name="REGION", referencedColumnName="REGION")
         * })
         */
        protected $inseeState;

        /**
         * @var \Acreat\InseeBundle\Entity\InseeCity
         *
         * @ORM\ManyToOne(targetEntity="Acreat\InseeBundle\Entity\InseeCity")
         * @ORM\JoinColumns({
         *      @ORM\JoinColumn(name="CHEFLIEU", referencedColumnName="codeInsee")
         * })
         */
        protected $inseeCapital;

        /**
         * @ORM\OneToMany(targetEntity="Acreat\InseeBundle\Entity\InseeCity", mappedBy="inseeDepartment")
         */
        protected $inseeCities;

        /**
         *
         */
        public function __construct() {
            $this->inseeCities = new ArrayCollection();
        }

        /**
         * Set prefix
         *
         * @param string $prefix
         * @return InseeDepartement
         */
        public function setPrefix($prefix) {
            $this->prefix = $prefix;

            return $this;
        }

        /**
         * Get prefix
         *
         * @return string
         */
        public function getPrefix() {
            return $this->prefix;
        }

        /**
         * Set uname
         *
         * @param string $uname
         * @return InseeDepartement
         */
        public function setUname($uname) {
            $this->uname = $uname;

            return $this;
        }

        /**
         * Get uname
         *
         * @return string
         */
        public function getUname() {
            return $this->uname;
        }

        /**
         * Set name
         *
         * @param string $name
         * @return InseeDepartement
         */
        public function setName($name) {
            $this->name = $name;

            return $this;
        }

        /**
         * Get name
         *
         * @return string
         */
        public function getName() {
            return $this->name;
        }

        /**
         * Get id
         *
         * @return string
         */
        public function getId() {
            return $this->id;
        }

        /**
         * Set inseeState
         *
         * @param \Acreat\InseeBundle\Entity\InseeState $inseeState
         * @return InseeDepartement
         */
        public function setInseeState(\Acreat\InseeBundle\Entity\InseeState $inseeState = null) {
            $this->inseeState = $inseeState;

            return $this;
        }

        /**
         * Get inseeState
         *
         * @return \Acreat\InseeBundle\Entity\InseeState
         */
        public function getInseeState() {
            return $this->inseeState;
        }

        /**
         * Set inseeCapital
         *
         * @param \Acreat\InseeBundle\Entity\InseeCity $inseeCapital
         * @return InseeDepartement
         */
        public function setInseeCapital(\Acreat\InseeBundle\Entity\InseeCity $inseeCapital = null) {
            $this->inseeCapital = $inseeCapital;

            return $this;
        }

        /**
         * Get inseeCapital
         *
         * @return \Acreat\InseeBundle\Entity\InseeCity
         */
        public function getInseeCapital() {
            return $this->inseeCapital;
        }

        /**
         * Add inseeDepartment
         *
         * @param \Acreat\InseeBundle\Entity\InseeDepartment $inseeCity
         * @return InseeDepartment
         */
        public function addInseeCity(\Acreat\InseeBundle\Entity\InseeCity $inseeCity) {
            $this->inseeDepartments[] = $inseeDepartment;

            return $this;
        }

        /**
         * Remove inseeCity
         *
         * @param \Acreat\InseeBundle\Entity\InseeCity $inseeCity
         */
        public function removeInseeCity(\Acreat\InseeBundle\Entity\InseeCity $inseeCity) {
            $this->inseecities->removeElement($inseeCity);
        }

        /**
         * Get inseeCities
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getInseeCities() {
            return $this->inseeCities;
        }

    }

}
