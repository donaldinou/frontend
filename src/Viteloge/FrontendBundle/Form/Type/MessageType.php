<?php

namespace Viteloge\FrontendBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;

    class MessageType extends AbstractType {

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder
                ->add('firstname')
                ->add('lastname')
                ->add('message', 'textarea')
                ->add('email')
                ->add('phone')
                ->add('send', 'submit');
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver){
            $resolver->setDefaults(array(
                'data_class' => 'Viteloge\FrontendBundle\Entity\Message',
            ));
        }

        public function getName() {
            return 'viteloge_frontend_message';
        }

    }

}
