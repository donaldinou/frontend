<?php

namespace Acreat\YakazBundle\Component\Attribute {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\Serializer;
    use Acreat\SerializerBundle\Component\Annotation\Required;
    use Acreat\SerializerBundle\Component\Annotation\Enum;
    use Acreat\YakazBundle\Enum\Boolean;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class StreetAddress extends Serializer {

        /**
         * @Required
         * @Enum("Acreat\YakazBundle\Enum\Boolean")
         * @JMS\SerializedName("display-address")
         * @JMS\XmlAttribute
         */
        protected $displayAddress;

        /**
         * @Required
         * @JMS\XmlValue
         */
        protected $value;

        public function __construct( $value='' ) {
            $this->unit = Boolean::__default;
            $this->value = $value;
        }

    }

}
