<?php

namespace Acreat\MitulaBundle\Component {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\Serializer;
    use Acreat\SerializerBundle\Component\Annotation\Required;

    /**
     *
     * @JMS\ExclusionPolicy("none")
     * @JMS\XmlRoot("mitula")
     */
    class Mitula extends Serializer {

        /**
         * @Required
         * @JMS\SerializedName("ads")
         * @JMS\XmlList(inline=true, entry="ad")
         * @JMS\Type("Acreat\MitulaBundle\Component\Collection\Ads")
         */
        protected $ads;

        public function __construct() {
            $this->ads = new Collection\Ads();
        }

    }

}
