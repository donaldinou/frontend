<?php

namespace Acreat\YakazBundle\Component\Element\What {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\YakazBundle\Component\Element\What;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class Conveyance extends What {

        /**
         * @JMS\SerializedName("price")
         */
        protected $price;

        /**
         * @JMS\SerializedName("version")
         * @JMS\Type("string")
         */
        protected $distance;

        /**
         * @JMS\SerializedName("ads")
         * @JMS\Type("string")
         */
        protected $year;

        /**
         * @JMS\SerializedName("ads")
         * @JMS\Type("string")
         */
        protected $brand;

        /**
         * @JMS\SerializedName("ads")
         * @JMS\Type("string")
         */
        protected $model;

        /**
         * @JMS\SerializedName("ads")
         * @JMS\Type("string")
         */
        protected $color;

    }

}
