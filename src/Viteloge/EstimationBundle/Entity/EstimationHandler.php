<?php

namespace Viteloge\EstimationBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;

class EstimationHandler{
    public function __construct( ObjectManager $om ){
        $this->om = $om;
    }

    public function save( Estimation $estimation ) {
        $this->om->persist( $estimation );
        $this->om->flush();

        return 42;
    }
}
