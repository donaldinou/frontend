<?php

namespace Acreat\YakazBundle\Component\Element\Ad {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Annotation\Required;
    use Acreat\SerializerBundle\Component\Annotation\Enum;
    use Acreat\YakazBundle\Component\Element\Ad;
    use Acreat\YakazBundle\Component\Element\What;
    use Acreat\YakazBundle\Component\Element\Where;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class Car extends Ad {

        /**
         * @Required
         * @Enum("Acreat\YakazBundle\Enum\Cars")
         * @JMS\SerializedName("category")
         * @JMS\XmlAttribute
         */
        protected $category;

        public function __construct() {
            $this->what = new What\Car();
            $this->where = new Where();
        }

    }

}
