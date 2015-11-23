<?php

namespace Acreat\TrovitBundle\Component {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\Serializer;
    use Acreat\SerializerBundle\Component\Annotation\Required;

    class Price extends Serializer {

        /**
         * @JMS\SerializedName("period")
         * @JMS\XmlAttribute
         */
        protected $period;

        /**
         * @JMS\SerializedName("currency")
         * @JMS\XmlAttribute
         */
        protected $currency;

        protected $value;

    }

}
