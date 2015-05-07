<?php

namespace Viteloge\EstimationBundle\Entity;

use Doctrine\ORM\EntityRepository;

use Viteloge\FrontendBundle\Entity\City;

class BarometreRepository extends EntityRepository
{
    public function findLatest( $params ){
        $qb = $this->_em->createQueryBuilder();

        $qb
            ->select( 'barometre' )
            ->from( 'Viteloge\EstimationBundle\Entity\Barometre', 'barometre' )
            ->andWhere( 'barometre.insee = :insee' )
            ->andWhere( 'barometre.type = :type' )
            ->andWhere( 'barometre.transaction = :transaction' )
            ->addOrderBy( 'barometre.annee', 'DESC' )
            ->addOrderBy( 'barometre.mois', 'DESC' )
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

    public function findSortedSalesFor( City $city ) {
        $qb = $this->_em->createQueryBuilder();

        $qb
            ->select( 'barometre' )
            ->from( 'Viteloge\EstimationBundle\Entity\Barometre', 'barometre' )
            ->andWhere( 'barometre.insee = :insee' )
            ->andWhere( 'barometre.transaction = \'v\'' )
            ->andWhere( "barometre.type in ( 'a', 'm' )")
            ->addOrderBy( 'barometre.annee', 'ASC' )
            ->addOrderBy( 'barometre.mois', 'ASC' )
            
            ->setParameter( 'insee', $city->getCodeInsee() )           
        ;
        $result = $qb->getQuery()->getResult();
        $end_result = array( 'a' => array(), 'm' => array() );
        foreach ( $result as $barometre ) {
            $end_result[$barometre->getType()][] = array(
                'date' => $barometre->getAsDate(),
                'value' => round( $barometre->getAvgSqm() ),
                'nb' => $barometre->getNb()
            );
        }
        return $end_result;
    }
    
}
