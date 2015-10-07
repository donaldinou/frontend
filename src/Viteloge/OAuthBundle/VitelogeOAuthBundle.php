<?php

namespace Viteloge\OAuthBundle {

    use Symfony\Component\HttpKernel\Bundle\Bundle;

    class VitelogeOAuthBundle extends Bundle {

        public function getParent() {
            return 'HWIOAuthBundle';
        }

    }
}
