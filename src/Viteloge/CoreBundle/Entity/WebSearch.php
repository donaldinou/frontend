<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;
    use Gedmo\Mapping\Annotation as Gedmo;

    /**
     * WebSearch
     *
     * @ORM\Table(
     *      name="web_searches", uniqueConstraints={
     *          @ORM\UniqueConstraint(
     *              name="UNIQ_774729F45D419CCB",
     *              columns={
     *                  "idUtilisateur"
     *              }
     *          )
     *      },
     *      indexes={
     *          @ORM\Index(
     *              name="IDX_774729F47E3C61F9",
     *              columns={
     *                  "owner_id"
     *              }
     *          )
     *      }
     * )
     * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\WebSearchRepository")
     * @ORM\HasLifecycleCallbacks
     * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
     */
    class WebSearch
    {
        /**
         * @var string
         *
         * @ORM\Column(name="title", type="string", length=255, nullable=true)
         * @Assert\NotBlank()
         * @Assert\Length(
         *      min = 3,
         *      max = 100,
         *      minMessage = "The title must be at least {{ limit }} characters long",
         *      maxMessage = "The title cannot be longer than {{ limit }} characters"
         * )
         * @Assert\Type(
         *     type="string"
         * )
         */
        private $title;

        /**
         * @var integer
         *
         * @ORM\Column(name="totalMatches", type="integer", nullable=false)
         * @Assert\GreaterThanOrEqual(
         *     value = 0
         * )
         * @Assert\Type(
         *     type="integer"
         * )
         */
        private $totalmatches;

        /**
         * @var integer
         *
         * @ORM\Column(name="newMatches", type="integer", nullable=false)
         * @Assert\GreaterThanOrEqual(
         *     value = 0
         * )
         * @Assert\Type(
         *     type="integer"
         * )
         */
        private $newmatches;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="lastUpdate", type="datetime", nullable=true)
         * @Assert\DateTime()
         */
        private $updatedAt;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
         * @Assert\DateTime()
         */
        private $deletedAt;

        /**
         * @var json_array
         *
         * @ORM\Column(name="cached_properties", type="json_array", nullable=true)
         */
        private $cachedProperties;

        /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var \Viteloge\CoreBundle\Entity\User
         *
         * @ORM\ManyToOne(targetEntity="Viteloge\CoreBundle\Entity\User", inversedBy="webSearches")
         * @ORM\JoinColumns({
         *      @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
         * })
         * @Assert\Type(type="Viteloge\CoreBundle\Entity\User")
         * @Assert\Valid()
         */
        private $user;

        /**
         * @var \Viteloge\CoreBundle\Entity\UserSearch
         *
         * @ORM\OneToOne(targetEntity="Viteloge\CoreBundle\Entity\UserSearch", fetch="EAGER", cascade={"persist"})
         * @ORM\JoinColumns({
         *      @ORM\JoinColumn(name="idUtilisateur", referencedColumnName="idUtilisateur")
         * })
         * @Assert\Type(type="Viteloge\CoreBundle\Entity\UserSearch")
         * @Assert\Valid()
         */
        private $userSearch;

        /**
         * Constructor
         * @return void
         */
        public function __construct() {
            $this->newmatches = 0;
            $this->totalmatches = 0;
        }

        /**
         * Set title
         *
         * @param string $title
         * @return WebSearches
         */
        public function setTitle($title)
        {
            $this->title = $title;

            return $this;
        }

        /**
         * Get title
         *
         * @return string
         */
        public function getTitle()
        {
            return $this->title;
        }

        /**
         * Set totalmatches
         *
         * @param integer $totalmatches
         * @return WebSearches
         */
        public function setTotalmatches($totalmatches)
        {
            $this->totalmatches = $totalmatches;

            return $this;
        }

        /**
         * Get totalmatches
         *
         * @return integer
         */
        public function getTotalmatches()
        {
            return $this->totalmatches;
        }

        /**
         * Set newmatches
         *
         * @param integer $newmatches
         * @return WebSearches
         */
        public function setNewmatches($newmatches)
        {
            $this->newmatches = $newmatches;

            return $this;
        }

        /**
         * Get newmatches
         *
         * @return integer
         */
        public function getNewmatches()
        {
            return $this->newmatches;
        }

        /**
         * Set updatedAt
         *
         * @param \DateTime $updatedAt
         * @return WebSearches
         */
        public function setUpdatedAt($updatedAt)
        {
            $this->updatedAt = $updatedAt;

            return $this;
        }

        /**
         * Get updatedAt
         *
         * @return \DateTime
         */
        public function getUpdatedAt() {
            // BUGFIX : always return a valid DateTime in order to use cache policy
            return (!empty($this->updatedAt)) ? $this->updatedAt : new \DateTime();
        }

        /**
         * Set deletedAt
         *
         * @param \DateTime $deletedAt
         * @return WebSearches
         */
        public function setDeletedAt($deletedAt)
        {
            $this->deletedAt = $deletedAt;

            return $this;
        }

        /**
         * Get deletedAt
         *
         * @return \DateTime
         */
        public function getDeletedAt()
        {
            return $this->deletedAt;
        }

        /**
         * Set cachedProperties
         *
         * @param \json_array $cachedProperties
         * @return WebSearches
         */
        public function setCachedProperties($cachedProperties)
        {
            $this->cachedProperties = $cachedProperties;

            return $this;
        }

        /**
         * Get cachedProperties
         *
         * @return \jsonarray
         */
        public function getCachedProperties()
        {
            return $this->cachedProperties;
        }

        /**
         * Get id
         *
         * @return integer
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set user
         *
         * @param \Viteloge\CoreBundle\Entity\User $user
         * @return WebSearches
         */
        public function setUser(\Viteloge\CoreBundle\Entity\User $user = null)
        {
            $this->user = $user;

            return $this;
        }

        /**
         * Get account
         *
         * @return \Viteloge\CoreBundle\Entity\User
         */
        public function getUser()
        {
            return $this->user;
        }

        /**
         * Set userSearch
         *
         * @param \Viteloge\CoreBundle\Entity\UserSearch $userSearch
         * @return WebSearches
         */
        public function setUserSearch(\Viteloge\CoreBundle\Entity\UserSearch $userSearch = null)
        {
            $this->userSearch = $userSearch;

            return $this;
        }

        /**
         * Get search
         *
         * @return \Viteloge\CoreBundle\Entity\Search
         */
        public function getUserSearch()
        {
            return $this->userSearch;
        }

        /**
         * @ORM\PreUpdate
         */
        public function setUpdatedAtValue() {
            $this->setUpdatedAt(new \DateTime());
            return $this;
        }

    }

}
