<?php

namespace Acreat\TrovitBundle\Component {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\Serializer;
    use Acreat\SerializerBundle\Component\Annotation\Required;

    /**
     * http://about.trovit.com/your-ads-on-trovit/france/flux-fr-immo/
     * @JMS\ExclusionPolicy("none")
     * @JMS\XmlRoot("trovit")
     */
    class Root extends Serializer {

        /**
         * @Required
         * @JMS\SerializedName("ads")
         * @JMS\XmlList(inline=true, entry="ad")
         * @JMS\Type("Acreat\TrovitBundle\Component\Ads")
         */
        protected $ads;

        public function __construct() {
            $this->ads = new Ads();
        }

    }

}
