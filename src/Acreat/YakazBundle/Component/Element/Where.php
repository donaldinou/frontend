<?php

namespace Acreat\YakazBundle\Component\Element {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\Serializer;
    use Acreat\SerializerBundle\Component\Annotation\Required;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class Where extends Serializer {

        /**
         * @Required
         * @JMS\SerializedName("city-name")
         * @JMS\Type("string")
         */
        protected $cityName;

        /**
         * @Required
         * @JMS\SerializedName("zip-code")
         * @JMS\Type("string")
         */
        protected $zipCode;

        /**
         * @Required
         * @JMS\SerializedName("country")
         * @JMS\Type("string")
         */
        protected $country;

        /**
         * @JMS\SerializedName("street-address")
         * @JMS\Type("string")
         */
        protected $streetAddress;

        /**
         * @JMS\SerializedName("region-name")
         * @JMS\Type("string")
         */
        protected $regionName;

        /**
         * @JMS\SerializedName("region-code")
         * @JMS\Type("string")
         */
        protected $regionCode;

    }

}
