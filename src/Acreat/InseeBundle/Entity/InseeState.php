<?php

namespace Acreat\InseeBundle\Entity {

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * InseeState
     *
     * @ORM\Table(name="insee_regions", indexes={@ORM\Index(name="cityId", columns={"CHEFLIEU"})})
     * @ORM\Entity(repositoryClass="Acreat\InseeBundle\Repository\InseeStateRepository")
     */
    class InseeState extends InseeEntity {

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
         * @ORM\Column(name="REGION", type="string", length=2)
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        protected $id;

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
         * @ORM\OneToMany(targetEntity="Acreat\InseeBundle\Entity\InseeDepartment", mappedBy="inseeState")
         */
        protected $inseeDepartments;

        /**
         *
         */
        public function __construct() {
            $this->inseeDepartments = new ArrayCollection();
        }

        /**
         * Set prefix
         *
         * @param string $prefix
         * @return InseeState
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
         * @return InseeState
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
         * @return InseeState
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
         * Set inseeCapital
         *
         * @param \Acreat\InseeBundle\Entity\InseeCity $inseeCapital
         * @return InseeState
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
         * @param \Acreat\InseeBundle\Entity\InseeDepartment $inseeDepartment
         * @return InseeState
         */
        public function addInseeDepartment(\Acreat\InseeBundle\Entity\InseeDepartment $inseeDepartment) {
            $this->inseeDepartments[] = $inseeDepartment;

            return $this;
        }

        /**
         * Remove inseeDepartment
         *
         * @param \Acreat\InseeBundle\Entity\InseeDepartment $inseeDepartment
         */
        public function removeInseeDepartment(\Acreat\InseeBundle\Entity\InseeDepartment $inseeDepartment) {
            $this->inseeDepartments->removeElement($inseeDepartment);
        }

        /**
         * Get inseeDepartments
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getInseeDepartments() {
            return $this->inseeDepartments;
        }

    }

}
