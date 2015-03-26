<?php

namespace Viteloge\CoreBundle\Repository {

    use Doctrine\ORM\EntityRepository;

    /**
     * SearchRepository
     */
    class SearchRepository extends EntityRepository {

        public function findAllInseeCityOrderedByCount( $limit=15 ) {
            $query = $this->getEntityManager()
                ->createQuery(
                    'SELECT IDENTITY(s.inseeCity) AS inseeCity, c.name, COUNT(s.inseeCity) AS total '.
                    'FROM VitelogeCoreBundle:Search s '.
                    'JOIN s.inseeCity c '.
                    'GROUP BY s.inseeCity, c.name '.
                    'ORDER BY total DESC'
                );
            if (!is_null($limit)) {
                $query->setMaxResults($limit);
            }
            return $query->getResult();
        }

    }


}
