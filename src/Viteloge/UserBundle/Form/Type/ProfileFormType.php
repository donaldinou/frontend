<?php

namespace Viteloge\UserBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Viteloge\CoreBundle\Component\Enum\CivilityEnum;

    class ProfileFormType extends AbstractType {

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $civility = new CivilityEnum();
            $builder
                ->add('civility', 'choice',
                    array(
                        'choices' => $civility->choices(),
                        'expanded' => false,
                        'multiple' => false,
                        'placeholder' => 'Choose an option',
                    )
                )
                ->add('firstname')
                ->add('lastname')
                ->add('phone')
                ->add('partnerContactEnabled')
                ->remove('username')
                ->remove('current_password');
        }

        public function getParent() {
            return 'fos_user_profile';
        }

        public function getName() {
            return 'viteloge_user_profile';
        }
    }

}
