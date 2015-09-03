<?php

namespace Viteloge\CoreBundle\Repository {

    use Acreat\CoreBundle\Component\ORM\EntityRepository;
    use Acreat\InseeBundle\Entity\InseeCity;

    /**
     * BarometerRepository
     *
     * This class was generated by the Doctrine ORM. Add your own custom
     * repository methods below.
     */
    class BarometerRepository extends EntityRepository {

        /**
         * Legacy from EstimationBundle
         */
        public function findLatest( $params ){
            $qb = $this->_em->createQueryBuilder();

            $qb
                ->select( 'barometre' )
                ->from( 'VitelogeCoreBundle:Barometer', 'barometre' )
                ->andWhere( 'barometre.inseeCity = :insee' )
                ->andWhere( 'barometre.type = :type' )
                ->andWhere( 'barometre.transaction = :transaction' )
                ->addOrderBy( 'barometre.year', 'DESC' )
                ->addOrderBy( 'barometre.month', 'DESC' )
                ->setMaxResults( 1 )

                ->setParameter( 'insee', $params['insee'] )
                ->setParameter( 'type', $params['type'] )
                ->setParameter( 'transaction', $params['transaction'] )

            ;
            $result = $qb->getQuery()->getResult();
            if ( count( $result ) > 0 ) {
                return $result[0];
            }
            return null;
        }

        /**
         * Legacy from EstimationBundle
         */
        public function findSortedSalesFor( InseeCity $city ) {
            $qb = $this->_em->createQueryBuilder();

            $qb
                ->select( 'barometre' )
                ->from( 'VitelogeCoreBundle:Barometer', 'barometre' )
                ->andWhere( 'barometre.inseeCity = :insee' )
                ->andWhere( 'barometre.transaction = \'v\'' )
                ->andWhere( "barometre.type in ( 'a', 'm' )")
                ->addOrderBy( 'barometre.year', 'ASC' )
                ->addOrderBy( 'barometre.month', 'ASC' )

                ->setParameter( 'insee', $city->getId() )
            ;
            $result = $qb->getQuery()->getResult();
            $end_result = array( 'a' => array(), 'm' => array() );
            foreach ( $result as $barometre ) {
                //var_dump(get_class($barometre));die;
                $end_result[$barometre->getType()][] = array(
                    //'date' => $barometre->getCreatedAt(),
                    'date' => sprintf( '%4d%02d', $barometre->getYear(), $barometre->getMonth() ),
                    'value' => round( $barometre->getAvgSqm() ),
                    'nb' => $barometre->getTotal()
                );
            }

            $lastHome = end($end_result['m']);
            $lastAppt = end($end_result['m']);
            $end_result['total'] = (float)$lastHome['nb']+(float)$lastAppt['nb'];
            return $end_result;
        }
    }

}
