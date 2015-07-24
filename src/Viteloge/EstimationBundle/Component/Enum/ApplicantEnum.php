<?php

namespace Viteloge\EstimationBundle\Component\Enum {

    use Viteloge\CoreBundle\Component\Enum\Enum;

    class ApplicantEnum extends Enum {

        const __default = null;

        const OWNER = 'P';

        const TENANT = 'L';

        const PURCHASER = 'A';

        const AGENT = 'I';

        public function choices() {
            return array(
                self::OWNER => 'estimate.applicant.owner',
                self::TENANT => 'estimate.applicant.tenant',
                self::PURCHASER => 'estimate.applicant.purchaser',
                self::AGENT => 'estimate.applicant.agent'
            );
        }

    }

}
