<?php

namespace Acreat\YakazBundle\Component\Element {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\Serializer;
    use Acreat\SerializerBundle\Component\Annotation\Required;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class What extends Serializer {

        /**
         * @Required
         * @JMS\SerializedName("title")
         * @JMS\Type("string")
         */
        protected $title;

        /**
         * @Required
         * @JMS\SerializedName("description")
         * @JMS\Type("string")
         */
        protected $description;

        /**
         * @Required
         * @JMS\SerializedName("ad-url")
         * @JMS\Type("string")
         */
        protected $url;

        /**
         * @JMS\SerializedName("pictures")
         * @JMS\XmlList(inline=true, entry="picture-url")
         * @JMS\Type("Acreat\YakazBundle\Component\Collection\Pictures")
         */
        protected $pictures;

        /**
         * @JMS\SerializedName("expiration-date")
         * @JMS\Type("string")
         */
        protected $expirationDate;

        /**
         * @JMS\SerializedName("contact")
         * @JMS\Type("string")
         */
        protected $contact;

        /**
         * @JMS\SerializedName("phone")
         * @JMS\Type("string")
         */
        protected $phone;

        /**
         * @JMS\SerializedName("advertiser-type")
         * @JMS\Type("string")
         */
        protected $adverstiserType;

        /**
         * @JMS\SerializedName("offer-want")
         * @JMS\Type("string")
         */
        protected $offerWant;

    }

}
