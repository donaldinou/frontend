<?php

namespace Acreat\YakazBundle\Component\Element\What {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\YakazBundle\Component\Element\What;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class Miscellaneous extends What {

        /**
         * @JMS\SerializedName("price")
         */
        protected $price;

    }

}
