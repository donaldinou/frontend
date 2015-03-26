<?php
namespace Acreat\CoreBundle\Component\DBAL;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class EnumType extends Type {
    const __default = null;

    protected $name;
    protected $values = array();

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
        $values = array_map(function($val) { return "'".$val."'"; }, $this->values);
        return "ENUM(".implode(", ", $values).") COMMENT '(DC2Type:".$this->name.")'";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform) {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform) {
        if (!in_array($value, $this->values)) {
            throw new \InvalidArgumentException("Invalid '".$this->name."' value.");
        }
        return $value;
    }

    public function getName() {
        return $this->name;
    }

    public static function getDefault() {
        return static::__default;
    }

    public static function getValues() {
         throw new \BadMethodCallException(
            'You must override the getValues() method in the concrete command class.'
        );
    }
}
