<?php

namespace Acreat\YakazBundle\Component\Element\What {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\Serializer;
    use Acreat\SerializerBundle\Component\Annotation\Required;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class Profile extends Serializer {

        /**
         * @Required
         * @JMS\SerializedName("contact")
         * @JMS\Type("string")
         */
        protected $contact;

        /**
         * @JMS\SerializedName("name")
         * @JMS\Type("string")
         */
        protected $name;

        /**
         * @JMS\SerializedName("phone")
         * @JMS\Type("string")
         */
        protected $phone;

        /**
         * @JMS\SerializedName("website")
         * @JMS\Type("string")
         */
        protected $website;

        /**
         * @JMS\SerializedName("pictures")
         * @JMS\XmlList(inline=true, entry="picture-url")
         * @JMS\Type("Acreat\YakazBundle\Component\Collection\Pictures")
         */
        protected $pictures;

        /**
         * @JMS\SerializedName("shortDescription")
         * @JMS\Type("string")
         */
        protected $shortDescription;

        /**
         * @JMS\SerializedName("description")
         * @JMS\Type("string")
         */
        protected $description;

        /**
         * @JMS\SerializedName("universes")
         * @JMS\Type("string")
         */
        protected $universes;

    }

}
