<?php

namespace Viteloge\FrontendBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;

    class MessageType extends AbstractType {

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder
                ->add('firtname')
                ->add('lastname')
                ->add('message', 'text')
                ->add('email')
                ->add('phone')
                ->add('viteloge.send', 'submit');
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
