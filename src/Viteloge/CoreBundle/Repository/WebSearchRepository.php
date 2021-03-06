<?php

namespace Viteloge\CoreBundle\Repository {

    use Doctrine\DBAL\LockMode;
    use Acreat\CoreBundle\Component\ORM\EntityRepository;

    /**
     * WebSearchRepository
     *
     * This class was generated by the Doctrine ORM. Add your own custom
     * repository methods below.
     */
    class WebSearchRepository extends EntityRepository {

        /**
         * Finds an entity by its primary key / identifier.
         *
         * @param mixed    $id          The identifier.
         * @param int      $lockMode    The lock mode.
         * @param int|null $lockVersion The lock version.
         *
         * @return object|null The entity instance or NULL if the entity can not be found.
         */
        public function findHistory($id, $lockMode = LockMode::NONE, $lockVersion = null) {
            $filters = $this->_em->getFilters();
            $filters->disable('softdeleteable');
            return $this->_em->find($this->_entityName, $id, $lockMode, $lockVersion);
        }

    }

}
