<?php

namespace Acreat\TrovitBundle\Component {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\Serializer;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class Picture extends Serializer {

        /**
         * @JMS\SerializedName("featured")
         * @JMS\XmlAttribute
         */
        protected $featured;

        /**
         * @JMS\SerializedName("picture_url")
         * @JMS\Type("string")
         */
        protected $url;

        /**
         * @JMS\SerializedName("picture_title")
         * @JMS\Type("string")
         */
        protected $title;

    }

}
