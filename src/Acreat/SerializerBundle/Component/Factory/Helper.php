<?php

namespace Acreat\SerializerBundle\Component\Factory {

    use Doctrine\Common\Annotations\AnnotationReader;
    use Acreat\SerializerBundle\Component\Annotation\Required;
    use Acreat\SerializerBundle\Component\Annotation\Enum;
    use Acreat\SerializerBundle\Component\Helper\JsonHelper;
    use Acreat\SerializerBundle\Component\Helper\XMLHelper;
    use Acreat\SerializerBundle\Component\Helper\YamlHelper;

    class Helper {

        /**
         *
         */
        public static function PreSerialize( $class ) {
            $annotationReader = new AnnotationReader();
            $rc = new \ReflectionClass($class);
            $props = $rc->getProperties( \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE );
            foreach ($props as $key => $prop) {
                $prop->setAccessible(true);
                $name = $prop->getName();
                $value = $prop->getValue($class);
                $annotations = $annotationReader->getPropertyAnnotations($prop);
                foreach ($annotations as $annotation) {
                    if ($annotation instanceof Required && is_null($value)) {
                        throw new \InvalidArgumentException('The property ['.$name.'] in class : '.get_class($class).' must be set!' );
                    }
                    elseif ($annotation instanceof Enum) {
                        if (!in_array($value, forward_static_call( array($annotation->value, $annotation->handler), true ))) {
                            throw new \UnexpectedValueException('The value ['.print_r($value, true).'] in class : '.get_class($class).' is not a correct value for enumerator : '.$annotation->value );
                        }
                    }
                }
            }
        }

        public static function serialize($object, $format) {
            switch ($format) {
                case 'yaml':
                    return YamlHelper::serialize($object);
                    break;

                case 'xml':
                    return XMLHelper::serialize($object);
                    break;

                case 'json':
                default:
                    JsonHelper::serialize($object);
                    break;
            }
        }

    }

}
