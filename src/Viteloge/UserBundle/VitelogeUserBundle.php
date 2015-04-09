<?php

namespace Viteloge\UserBundle {

    use Symfony\Component\HttpKernel\Bundle\Bundle;

    class VitelogeUserBundle extends Bundle {

        public function getParent() {
            return 'FOSUserBundle';
        }

    }

}
