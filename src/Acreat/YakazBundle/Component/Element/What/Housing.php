<?php

namespace Acreat\YakazBundle\Component\Element\What {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\YakazBundle\Component\Element\What;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class Housing extends What {

        /**
         * @JMS\SerializedName("price")
         */
        protected $price;

        /**
         * @JMS\SerializedName("rooms")
         * @JMS\Type("string")
         */
        protected $rooms;

        /**
         * @JMS\SerializedName("bedrooms")
         * @JMS\Type("string")
         */
        protected $bedrooms;

        /**
         * @JMS\SerializedName("bathrooms")
         * @JMS\Type("string")
         */
        protected $bathrooms;

        /**
         * @JMS\SerializedName("surface")
         */
        protected $surface;

    }

}
