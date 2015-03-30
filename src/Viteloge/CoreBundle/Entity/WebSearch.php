<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;

    /**
     * WebSearch
     *
     * @ORM\Table(name="web_searches", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_774729F45D419CCB", columns={"idUtilisateur"})}, indexes={@ORM\Index(name="IDX_774729F47E3C61F9", columns={"owner_id"})})
     * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\WebSearchRepository")
     */
    class WebSearch
    {
        /**
         * @var string
         *
         * @ORM\Column(name="title", type="string", length=255, nullable=true)
         */
        private $title;

        /**
         * @var integer
         *
         * @ORM\Column(name="totalMatches", type="integer", nullable=false)
         */
        private $totalmatches;

        /**
         * @var integer
         *
         * @ORM\Column(name="newMatches", type="integer", nullable=false)
         */
        private $newmatches;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="lastUpdate", type="datetime", nullable=true)
         */
        private $updatedAt;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
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
         * @ORM\ManyToOne(targetEntity="Viteloge\CoreBundle\Entity\User")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
         * })
         */
        private $user;

        /**
         * @var \Viteloge\CoreBundle\Entity\UserSearch
         *
         * @ORM\ManyToOne(targetEntity="Viteloge\CoreBundle\Entity\UserSearch")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="idUtilisateur", referencedColumnName="idUtilisateur")
         * })
         */
        private $userSearch;



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
        public function getUpdatedAt()
        {
            return $this->updatedAt;
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
    }

}
