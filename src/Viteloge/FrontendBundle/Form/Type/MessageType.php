<?php

namespace Viteloge\FrontendBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;

    class MessageType extends AbstractType {

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder
                ->add('firstname', null, array(
                    'label' => 'message.firstname',
                ))
                ->add('lastname', null, array(
                    'label' => 'message.lastname'
                ))
                ->add('message', 'textarea', array(
                    'label' => 'message.message'
                ))
                ->add('email', null, array(
                    'label' => 'message.email'
                ))
                ->add('phone', null, array(
                    'label' => 'message.phone'
                ))
                ->add('send', 'submit');
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver){
            $resolver->setDefaults(array(
                'data_class' => 'Viteloge\FrontendBundle\Entity\Message',
                'intention' => 'task_form',
            ));
        }

        public function getName() {
            return 'viteloge_frontend_message';
        }

    }

}
