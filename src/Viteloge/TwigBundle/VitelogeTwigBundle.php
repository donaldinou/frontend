<?php

namespace Viteloge\TwigBundle {

    use Symfony\Component\HttpKernel\Bundle\Bundle;

    class VitelogeTwigBundle extends Bundle {

        public function getParent() {
            return 'TwigBundle';
        }

    }

}
