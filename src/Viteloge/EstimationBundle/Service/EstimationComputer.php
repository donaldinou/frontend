<?php

namespace Viteloge\EstimationBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;

use Viteloge\EstimationBundle\Entity\Estimation;

class EstimationComputer {

    const AJUSTEMENT_EXPOSITION       = 1;
    const AJUSTEMENT_DERNIER_ETAGE    = 2;
    const AJUSTEMENT_RDC              = 3;
    const AJUSTEMENT_BASE_ANNEE       = 4;
    const AJUSTEMENT_ANNEE_AGE        = 5;
    const AJUSTEMENT_ASCENSEUR        = 6;
    const AJUSTEMENT_TERRASSE         = 7;
    const AJUSTEMENT_BASE_TERRAIN     = 9;
    const AJUSTEMENT_TERRAIN_MAX      = 10;
    const AJUSTEMENT_VUE              = 11;
    const AJUSTEMENT_MODIFIER_PARKING = 12;
    const AJUSTEMENT_MODIFIER_GARAGE  = 13;
    const AJUSTEMENT_NEUF             = 14;
    const AJUSTEMENT_BON              = 15;
    const AJUSTEMENT_TRAVAUX          = 16;
    const AJUSTEMENT_REFAIRE          = 17;
    const MIN_TERRAIN                 = 18;

    static $DEFAULT_PARAMS = array(
        self::AJUSTEMENT_EXPOSITION => 3,
        self::AJUSTEMENT_DERNIER_ETAGE => 20,
        self::AJUSTEMENT_RDC => -20,
        self::AJUSTEMENT_BASE_ANNEE => 25,
        self::AJUSTEMENT_ANNEE_AGE => 5,
        self::AJUSTEMENT_ASCENSEUR => 10,
        self::AJUSTEMENT_TERRASSE => 10,
        self::AJUSTEMENT_BASE_TERRAIN => 1,
        self::AJUSTEMENT_TERRAIN_MAX => 20,
        self::MIN_TERRAIN => 400,
        self::AJUSTEMENT_VUE => 30,
        self::AJUSTEMENT_MODIFIER_PARKING => 3,
        self::AJUSTEMENT_MODIFIER_GARAGE => 6,
        self::AJUSTEMENT_NEUF => 10,
        self::AJUSTEMENT_BON => 5,
        self::AJUSTEMENT_TRAVAUX => -10,
        self::AJUSTEMENT_REFAIRE => -25,
    );
    
    
    public function __construct( ObjectManager $om ){
        $this->om = $om;
    }

    public function estimate( Estimation $estimation ){

        $barometre_repo = $this->om->getRepository( 'Viteloge\EstimationBundle\Entity\Barometre' );
        $latest_barometre = $barometre_repo->findLatest( array(
            'insee' => $estimation->getVille(),
            'type' => $estimation->getType(),
            'transaction' => 'v'
        ) );
        if ( ! $latest_barometre ) {
            return false;
        }

        $px_base = $latest_barometre->getAvgSqm() * $estimation->surface_habitable;

        $ajustement = 0;
        $supplement = 0;
        
        $params = self::$DEFAULT_PARAMS;
        
        if ( $estimation->exposition ) {
            switch( $estimation->exposition ) {
                case Estimation::EXPOSITION_SUD:
                    $ajustement += $params[self::AJUSTEMENT_EXPOSITION];
                    break;
                case Estimation::EXPOSITION_SUD:
                    $ajustement -= $params[self::AJUSTEMENT_EXPOSITION];
                    break;
            }
        }
        if ( $estimation->etage && $estimation->nb_etages && $estimation->etage == $estimation->nb_etages ) {
            $ajustement += $params[self::AJUSTEMENT_DERNIER_ETAGE];
        }
        if ( (! is_null( $estimation->etage ) ) && $estimation->nb_etages && 0 == $estimation->etage ) {
            $ajustement += $params[self::AJUSTEMENT_RDC];
        }
        if ( $estimation->annee_construction ) {
            $current_year = date("Y");
            if ( $estimation->annee_construction < 100 ) {
                if ( $estimation->annee_construction <= $current_year ) {
                    $estimation->annee_construction = 2000 + $estimation->annee_construction;
                } else {
                    $estimation->annee_construction = 1900 + $estimation->annee_construction;                    
                }
            }
            $max_ajustement = $params[self::AJUSTEMENT_BASE_ANNEE] - ( $current_year - $estimation->annee_construction ) * $params[self::AJUSTEMENT_ANNEE_AGE];
            if ( $max_ajustement > 0 ) {
                $ajustement += $max_ajustement;
            }
        }
        
        if ( $estimation->ascenseur ) {
            $ajustement += $params[self::AJUSTEMENT_ASCENSEUR];
        }
        if ( Estimation::TYPE_BIEN_APPARTEMENT == $estimation->getType() ) {
            if ( ! $estimation->ascenseur ) {
                $ajustement -= 10;
            }
            if ( $estimation->terrasse ) {
                $ajustement += 10;
            }
        } elseif ( Estimation::TYPE_BIEN_MAISON == $estimation->getType() ) {
            if ( $estimation->surface_terrain && $estimation->surface_terrain > $params[self::MIN_TERRAIN] ) {
                $max_ajustement = $params[self::AJUSTEMENT_BASE_TERRAIN] * ($estimation->surface_terrain - $params[self::MIN_TERRAIN]) / 100;
                $ajustement += $max_ajustement > $params[self::AJUSTEMENT_TERRAIN_MAX] ? $params[self::AJUSTEMENT_TERRAIN_MAX] : $max_ajustement;
                
            }
        }
        if ( $estimation->vue ) {
            $ajustement += $params[self::AJUSTEMENT_VUE];
        }

        $etat_correspondance = array(
            Estimation::ETAT_NEUF => self::AJUSTEMENT_NEUF,
            Estimation::ETAT_BON => self::AJUSTEMENT_BON,
            Estimation::ETAT_REFAIRE => self::AJUSTEMENT_REFAIRE,
            Estimation::ETAT_TRAVAUX => self::AJUSTEMENT_TRAVAUX
        );
        if ( $estimation->etat && array_key_exists( $estimation->etat, $etat_correspondance ) ) {
            $ajustement += $etat_correspondance[$estimation->etat];
        }

        if ( $estimation->parking ) {
            $supplement += $estimation->parking * $latest_barometre->getAvgSqm() * $params[self::AJUSTEMENT_MODIFIER_PARKING];
        }
        if ( $estimation->garage ) {
            $supplement += $estimation->garage * $latest_barometre->getAvgSqm() * $params[self::AJUSTEMENT_MODIFIER_GARAGE];
        }
        
        
        $prix = $px_base * ( 100 + $ajustement ) / 100 + $supplement;
        
        return array(
            "low" => number_format( $prix * 0.95, 0, ',', ' '),
            "high" => number_format( $prix * 1.05, 0, ',', ' '),
            "debug" => array(
                "px_base" => $px_base,
                "ajustement" => $ajustement,
                "supplement" => $supplement
            )
        );
    }
}
