<?php

namespace Viteloge\EstimationBundle\Entity {

    use Doctrine\Common\Persistence\ObjectManager;
    use Viteloge\CoreBundle\Entity\Estimate;

    /**
     * handler in order to save field as object in data
     */
    class EstimationHandler {

        /**
         *
         */
        public function __construct( ObjectManager $om ){
            $this->om = $om;
        }

        /**
         *
         */
        public function save( Estimate $estimate ) {
            $this->om->persist( $estimate);
            $this->om->flush();

            return 42;
        }
    }

}
