<?php

namespace Viteloge\UserBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;

    class RegistrationFormType extends AbstractType {

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder->remove('username');
        }

        public function getParent() {
            return 'fos_user_registration';
        }

        public function getName() {
            return 'viteloge_user_registration';
        }
    }

}
