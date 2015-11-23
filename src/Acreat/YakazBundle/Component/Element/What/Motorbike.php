<?php

namespace Acreat\YakazBundle\Component\Element\What {

    use JMS\Serializer\Annotation as JMS;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class Motorbike extends Conveyance {

        /**
         * @JMS\SerializedName("cc")
         * @JMS\Type("string")
         */
        protected $cc;

    }

}
