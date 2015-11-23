<?php

namespace Acreat\SerializerBundle\Component\Serializer {

    interface iSerializer {

        public function PreSerialize();

        public function serialize($format);

    }

}
