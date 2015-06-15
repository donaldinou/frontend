<?php

namespace Viteloge\CoreBundle\Component\Enum {

    class UserSearchSourceEnum extends Enum {

        const __default = '';

        const WEB = 'websearch';

        const CONFIGURE = 'configure';

        const LANDING = 'landing';

        const DIRECT = 'direct';

        public function choices() {
            return array(
                self::WEB => 'usersearch.web',
                self::CONFIGURE => 'usersearch.configure',
                self::LANDING => 'usersearch.landing'
            );
        }

    }

}
