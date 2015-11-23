<?php

namespace Acreat\MitulaBundle\Component\Element {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\SerializerBundle\Component\Serializer\Serializer;
    use Acreat\SerializerBundle\Component\Annotation\Required;
    use Acreat\SerializerBundle\Component\Annotation\Enum;

    /**
     *
     * @JMS\ExclusionPolicy("none")
     */
    class Ad extends Serializer {

        /**
         * @Required
         * @JMS\SerializedName("id")
         * @JMS\Type("string")
         */
        protected $id;

        /**
         * @Required
         * @JMS\SerializedName("url")
         * @JMS\Type("string")
         */
        protected $url;

        /**
         * @Required
         * @JMS\SerializedName("title")
         * @JMS\Type("string")
         */
        protected $title;

        /**
         * @Required
         * @Enum("Acreat\MitulaBundle\Enum\AdType")
         * @JMS\SerializedName("type")
         */
        protected $type;

        /**
         * @Required
         * @JMS\SerializedName("content")
         * @JMS\Type("string")
         */
        protected $content;

        /**
         * @JMS\SerializedName("price")
         * @JMS\Type("double")
         */
        protected $price;

        /**
         * @JMS\SerializedName("property_type")
         * @JMS\Type("string")
         */
        protected $propertyType;

        /**
         * @JMS\SerializedName("address")
         * @JMS\Type("string")
         */
        protected $address;

        /**
         * @JMS\SerializedName("city_area")
         * @JMS\Type("string")
         */
        protected $cityArea;

        /**
         * @JMS\SerializedName("city")
         * @JMS\Type("string")
         */
        protected $city;

        /**
         * @JMS\SerializedName("region")
         * @JMS\Type("string")
         */
        protected $region;

        /**
         * @JMS\SerializedName("postcode")
         * @JMS\Type("string")
         */
        protected $postcode;

        /**
         * @JMS\SerializedName("latitude")
         * @JMS\Type("double")
         */
        protected $latitude;

        /**
         * @JMS\SerializedName("longitude")
         * @JMS\Type("double")
         */
        protected $longitude;

        /**
         * @JMS\SerializedName("agency")
         * @JMS\Type("string")
         */
        protected $agency;

        /**
         * @JMS\SerializedName("floor_area")
         * @JMS\Type("Acreat\MitulaBundle\Component\Attribute\FloorArea")
         */
        protected $floorArea;

        /**
         * @JMS\SerializedName("rooms")
         * @JMS\Type("string")
         */
        protected $rooms;

        /**
         * @JMS\SerializedName("bathrooms")
         * @JMS\Type("string")
         */
        protected $bathrooms;

        /**
         * @JMS\SerializedName("virtual_tour")
         * @JMS\Type("string")
         */
        protected $virtualTour;

        /**
         * @JMS\SerializedName("pictures")
         * @JMS\XmlList(inline=false, entry="picture")
         * @JMS\Type("Acreat\MitulaBundle\Component\Collection\Pictures")
         */
        protected $pictures;

        /**
         * @JMS\SerializedName("date")
         * @JMS\Type("DateTime<'Y-m-d'>")
         */
        protected $date;

        /**
         * @JMS\SerializedName("time")
         * @JMS\Type("string")
         */
        protected $time;

        /**
         * @JMS\SerializedName("parking")
         * @JMS\Type("boolean")
         */
        protected $parking;

        /**
         * @JMS\SerializedName("is_new")
         * @JMS\Type("boolean")
         */
        protected $isNew;

    }

}
