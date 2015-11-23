<?php

namespace Acreat\YakazBundle\Component\Element {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\Serializer;
    use Acreat\SerializerBundle\Component\Annotation\Required;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    abstract class Ad extends Serializer {

        /**
         * @Required
         * @JMS\SerializedName("what")
         */
        protected $what;

        /**
         * @Required
         * @JMS\SerializedName("where")
         */
        protected $where;

        abstract public function __construct();

    }

}
