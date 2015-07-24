<?php

namespace Acreat\CoreBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class TelType extends AbstractType {

        /**
         *
         */
        public function getParent() {
            return 'text';
        }

        /**
         *
         */
        public function getName() {
            return 'tel';
        }
    }

}
