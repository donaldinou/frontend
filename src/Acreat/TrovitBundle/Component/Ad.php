<?php

namespace Acreat\TrovitBundle\Component {

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
         * @Enum("Acreat\TrovitBundle\Enum\AdType")
         * @JMS\SerializedName("type")
         */
        protected $type;

        /**
         * @Required
         * @JMS\SerializedName("content")
         * @JMS\Type("string")
         */
        protected $content;

        // optional

        /**
         * @JMS\SerializedName("content")
         * @JMS\Type("string")
         */
        protected $mobileUrl;

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
         * @Enum("Acreat\TrovitBundle\Enum\ForeclosureType")
         * @JMS\SerializedName("foreclosure_type")
         */
        protected $foreclosureType;

        /**
         * @JMS\SerializedName("address")
         * @JMS\Type("string")
         */
        protected $address;

        /**
         * @JMS\SerializedName("floor_number")
         * @JMS\Type("string")
         */
        protected $floorNumber;

        /**
         * @JMS\SerializedName("neighborhood")
         * @JMS\Type("string")
         */
        protected $neighborhood;

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
         * @JMS\SerializedName("country")
         * @JMS\Type("string")
         */
        protected $country;

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
         * @JMS\SerializedName("orientation")
         * @JMS\Type("string")
         */
        protected $orientation;

        /**
         * @JMS\SerializedName("agency")
         * @JMS\Type("string")
         */
        protected $agency;

        /**
         * @JMS\SerializedName("mls_database")
         * @JMS\Type("string")
         */
        protected $mlsDatabase;

        /**
         * @JMS\SerializedName("floor_area")
         * @JMS\Type("integer")
         */
        protected $floorArea;

        /**
         * @JMS\SerializedName("plot_area")
         * @JMS\Type("integer")
         */
        protected $plotArea;

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
         * @JMS\SerializedName("condition")
         * @JMS\Type("string")
         */
        protected $condition;

        /**
         * @JMS\SerializedName("year")
         * @JMS\Type("integer")
         */
        protected $year;

        /**
         * @JMS\SerializedName("virtual_tour")
         * @JMS\Type("string")
         */
        protected $virtualTour;

        /**
         * @JMS\SerializedName("eco_score")
         * @JMS\Type("string")
         */
        protected $ecoScore;

        /**
         * @JMS\SerializedName("pictures")
         * @JMS\XmlList(inline=false, entry="picture")
         * @JMS\Type("Acreat\TrovitBundle\Component\Pictures")
         */
        protected $pictures;

        /**
         * @JMS\SerializedName("date")
         * @JMS\Type("DateTime<'Y-m-d'>")
         */
        protected $date;

        /**
         * @JMS\SerializedName("expiration_date")
         * @JMS\Type("DateTime<'Y-m-d'>")
         */
        protected $expirationDate;

        /**
         * @JMS\SerializedName("by_owner")
         * @JMS\Type("boolean")
         */
        protected $byOwner;

        /**
         * @JMS\SerializedName("is_rent_to_own")
         * @JMS\Type("boolean")
         */
        protected $isRentToOwn;

        /**
         * @JMS\SerializedName("parking")
         * @JMS\Type("boolean")
         */
        protected $parking;

        /**
         * @JMS\SerializedName("foreclosure")
         * @JMS\Type("boolean")
         */
        protected $foreclosure;

        /**
         * @JMS\SerializedName("is_finished")
         * @JMS\Type("boolean")
         */
        protected $isFurnished;

        /**
         * @JMS\SerializedName("is_new")
         * @JMS\Type("boolean")
         */
        protected $isNew;

    }

}
