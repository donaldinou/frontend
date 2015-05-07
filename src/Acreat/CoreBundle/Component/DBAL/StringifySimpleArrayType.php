<?php

namespace Acreat\CoreBundle\Component\DBAL {

    use Doctrine\DBAL\Types\SimpleArrayType;
    use Doctrine\DBAL\Types\Type;
    use Doctrine\DBAL\Platforms\AbstractPlatform;

    class StringifySimpleArrayType extends SimpleArrayType {

        public function convertToDatabaseValue($value, AbstractPlatform $platform) {
            if (!$value) {
                return '';
            }
            return implode(',', $value);
        }

        /**
         * {@inheritdoc}
         */
        public function convertToPHPValue($value, AbstractPlatform $platform) {
            if ($value === null || '' == $value ) {
                return array();
            }
            $value = (is_resource($value)) ? stream_get_contents($value) : $value;
            return explode(',', $value);
        }


        public function getName() {
            return 'stringy_' . Type::SIMPLE_ARRAY;
        }

    }

}
