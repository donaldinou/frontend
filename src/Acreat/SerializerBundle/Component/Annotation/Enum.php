<?php

namespace Acreat\SerializerBundle\Component\Annotation {

    /**
     * @Annotation
     */
    final class Enum {

        public $value;

        public $handler = 'getConstList';

    }

}
