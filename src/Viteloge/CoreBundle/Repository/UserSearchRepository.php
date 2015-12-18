<?php

namespace Viteloge\CoreBundle\Repository {

    use Acreat\CoreBundle\Component\ORM\EntityRepository;

    /**
     * UserSearchRepository
     */
    class UserSearchRepository extends EntityRepository {

        public function findAllInseeCityOrderedByCount( $limit=15 ) {
            $query = $this->getEntityManager()
                ->createQuery(
                    'SELECT IDENTITY(s.inseeCity) AS inseeCity, c.name, COUNT(s.inseeCity) AS total '.
                    'FROM VitelogeCoreBundle:UserSearch s '.
                    'JOIN s.inseeCity c '.
                    'GROUP BY s.inseeCity, c.name '.
                    'ORDER BY total DESC'
                );
            if (!is_null($limit)) {
                $query->setMaxResults($limit);
            }
            return $query->getResult();
        }

        /**
         * legacy @see SendgridController
         *
         */
        public function disableEmails( $email ) {
            $dbh = $this->_em->getConnection();
            return $dbh->executeUpdate( 'UPDATE utilisateur SET dateResiliation = NOW() WHERE dateResiliation is NULL and mail = ?', array($email) );
        }

    }


}
