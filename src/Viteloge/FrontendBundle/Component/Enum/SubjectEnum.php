<?php

namespace Viteloge\FrontendBundle\Component\Enum {

    use Viteloge\CoreBundle\Component\Enum\Enum;

    class SubjectEnum extends Enum {

        const __default = null;

        const NATIONAL_AD = 1;

        const LOCAL_AD = 2;

        const HIGHLIGHT_AD = 3;

        const PARTNER = 4;

        const BUG = 5;

        const TECHNICAL_ASSIST = 6;

        const MISC_QUESTION = 7;

        public function choices() {
            return array(
                self::NATIONAL_AD => 'contact.subject.nationalad',
                self::LOCAL_AD => 'contact.subject.localad',
                self::HIGHLIGHT_AD => 'contact.subject.highlightad',
                self::PARTNER => 'contact.subject.partner',
                self::BUG => 'contact.subject.bug',
                self::TECHNICAL_ASSIST => 'contact.subject.technicalassist',
                self::MISC_QUESTION => 'contact.subject.miscquestion',
            );
        }

    }

}
