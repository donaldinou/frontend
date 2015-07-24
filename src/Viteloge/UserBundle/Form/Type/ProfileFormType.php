<?php

namespace Viteloge\UserBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Viteloge\CoreBundle\Component\Enum\CivilityEnum;

    class ProfileFormType extends AbstractType {

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $civility = new CivilityEnum();
            $builder
                ->add('civility', 'choice', array(
                    'label' => 'profile.civility',
                    'choices' => $civility->choices(),
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => 'profile.civility'
                ))
                ->add('firstname', 'text', array(
                    'label' => 'profile.firstname'
                ))
                ->add('lastname', 'text', array(
                    'label' => 'profile.lastname'
                ))
                ->add('email', 'email', array(
                    'label' => 'profile.email'
                ))
                ->add('phone', 'tel', array(
                    'label' => 'profile.phone',
                    'pattern' => '^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$'
                ))
                ->add('partnerContactEnabled', 'checkbox', array(
                    'label' => 'profile.partnercontactenabled'
                ))
                ->remove('username')
                ->remove('current_password');
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver) {
            $resolver->setDefaults(array(
                'translation_domain'  => 'FOSUserBundle'
            ));
        }

        public function getParent() {
            return 'fos_user_profile';
        }

        public function getName() {
            return 'viteloge_user_profile';
        }
    }

}
