<?php

namespace Acreat\CoreBundle\Component\ORM {

    use Doctrine\ORM\EntityRepository as EntityRepositoryBase;

    class EntityRepository extends EntityRepositoryBase {

        /**
         * Find by methods with filtered criteria.
         * It takes only fields who curently exist in entity
         *
         * @param array      $criteria
         * @param array|null $orderBy
         * @param int|null   $limit
         * @param int|null   $offset
         *
         * @return array The objects.
         */
        public function findByFiltered(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
            foreach ($criteria as $key => $value) {
                if (!$this->getClassMetadata()->hasField($key) && !$this->getClassMetadata()->hasAssociation($key)) {
                    unset($criteria[$key]);
                }
            }
            foreach ($orderBy as $key => $value) {
                if (!$this->getClassMetadata()->hasField($key) && !$this->getClassMetadata()->hasAssociation($key)) {
                    unset($orderBy[$key]);
                }
            }
            return $this->findBy($criteria, $orderBy, $limit, $offset);
        }

    }

}
