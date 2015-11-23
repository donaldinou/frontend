<?php

namespace Acreat\TrovitBundle\Enum {

    class AdType/* extends SplEnum*/ {

        const __default = null;

        const FOR_RENT = 'For Rent';

        const FOR_SALE = 'For Sale';

        const ROOMMATE = 'Roommate';

        const PARKING_FOR_RENT = 'Parking For Rent';

        const PARKING_FOR_SALE = 'Parking For Sale';

        const OFFICE_FOR_RENT = 'Office For Rent';

        const OFFICE_FOR_SALE = 'Office For Sale';

        const LAND_FOR_SALE = 'Land For Sale';

        const FOR_RENT_LOCAL = 'For Rent Local';

        const FOR_SALE_LOCAL = 'For Sale Local';

        const TRANSFER_LOCAL = 'Transfer Local';

        const COUNTRY_HOUSE_RENTALS = 'Contry House Rentals';

        const WAREHOUSE_FOR_RENT = 'Warehouse For Rent';

        const WAREHOUSE_FOR_SALE = 'Warehouse For Sale';

        const OVERSEAS = 'Overseas';

        const SHORT_TERM_RENTALS = 'Short Term Rentals';

        const UNLISTED_FORECLOSURE = 'Unlisted Foreclosure';

        public static function getConstList( $include_default=false ) {
            //$reflect = new \ReflectionClass(get_class($this));
            //return $reflect->getConstants());
            $result = array(
                '__default' => self::__default,
                'FOR_RENT' => self::FOR_RENT,
                'FOR_SALE' => self::FOR_SALE,
                'ROOMMATE' => self::ROOMMATE,
                'PARKING_FOR_RENT' => self::PARKING_FOR_RENT,
                'PARKING_FOR_SALE' => self::PARKING_FOR_SALE,
                'OFFICE_FOR_RENT' => self::OFFICE_FOR_RENT,
                'OFFICE_FOR_SALE' => self::OFFICE_FOR_SALE,
                'LAND_FOR_SALE' => self::LAND_FOR_SALE,
                'FOR_RENT_LOCAL' => self::FOR_RENT_LOCAL,
                'FOR_SALE_LOCAL' => self::FOR_SALE_LOCAL,
                'TRANSFER_LOCAL' => self::TRANSFER_LOCAL,
                'COUNTRY_HOUSE_RENTALS' => self::COUNTRY_HOUSE_RENTALS,
                'WAREHOUSE_FOR_RENT' => self::WAREHOUSE_FOR_RENT,
                'WAREHOUSE_FOR_SALE' => self::WAREHOUSE_FOR_SALE,
                'OVERSEAS' => self::OVERSEAS,
                'SHORT_TERM_RENTALS' => self::SHORT_TERM_RENTALS,
                'UNLISTED_FORECLOSURE' => self::UNLISTED_FORECLOSURE
            );
            if (!$include_default) {
                unset($result['__default']);
            }
            return $result;
        }

    }

}
