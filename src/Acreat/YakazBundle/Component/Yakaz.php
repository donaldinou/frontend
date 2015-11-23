<?php

namespace Acreat\YakazBundle\Component {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\Serializer;
    use Acreat\SerializerBundle\Component\Annotation\Required;

    /**
     * @JMS\ExclusionPolicy("none")
     * @JMS\XmlRoot("yakaz")
     */
    class Yakaz extends Serializer {

        /**
         * @Required
         * @JMS\SerializedName("version")
         * @JMS\XmlAttribute
         */
        protected $version;

        /**
         *
         * @JMS\SerializedName("housings")
         * @JMS\XmlList(inline=true, entry="ad-housing")
         * @JMS\Type("Acreat\YakazBundle\Component\Collection\Housings")
         */
        protected $housings;

        /**
         *
         * @JMS\SerializedName("cars")
         * @JMS\XmlList(inline=true, entry="ad-cars")
         * @JMS\Type("Acreat\YakazBundle\Component\Collection\Cars")
         */
        protected $cars;

        /**
         *
         * @JMS\SerializedName("motorbikes")
         * @JMS\XmlList(inline=true, entry="ad-motorbikes")
         * @JMS\Type("Acreat\YakazBundle\Component\Collection\Motorbikes")
         */
        protected $motorbikes;

        /**
         *
         * @JMS\SerializedName("jobs")
         * @JMS\XmlList(inline=true, entry="ad-jobs")
         * @JMS\Type("Acreat\YakazBundle\Component\Collection\Jobs")
         */
        protected $jobs;

        /**
         *
         * @JMS\SerializedName("misc")
         * @JMS\XmlList(inline=true, entry="ad-miscellaneous")
         * @JMS\Type("Acreat\YakazBundle\Component\Collection\Misc")
         */
        protected $misc;

        public function __construct() {
            $this->version = "1.0";
        }

    }

}
