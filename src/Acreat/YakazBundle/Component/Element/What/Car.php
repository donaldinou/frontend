<?php

namespace Acreat\YakazBundle\Component\Element\What {

    use JMS\Serializer\Annotation as JMS;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class Car extends Conveyance {

        /**
         * @JMS\SerializedName("engine")
         * @JMS\Type("string")
         */
        protected $engine;

    }

}
