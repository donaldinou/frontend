<?php

namespace Viteloge\FrontendBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvent;
    use Symfony\Component\Form\FormEvents;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue;
    use Viteloge\FrontendBundle\Component\Enum\SubjectEnum;

    class RecommandType extends AbstractType {

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $subjectEnum = new SubjectEnum();
            $builder
                ->add('firstname')
                ->add('lastname')
                ->add('email')
                ->add('message', 'textarea')
                ->add('emails', 'collection', array(
                    'type'   => 'email',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'delete_empty' => true,
                    'prototype' => true,
                    'options'  => array(
                        'required'  => false,
                        'attr'      => array(
                            'class' => ''
                        )
                    ),
                ))
                ->add('recaptcha', 'ewz_recaptcha', array(
                    'attr' => array(
                        'options' => array(
                            'theme' => 'light',
                            'type'  => 'image'
                        )
                    ),
                    'mapped'      => false,
                    'constraints' => array(
                        new IsTrue()
                    )
                ))
                ->add('send', 'submit');

            $builder->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $data = $event->getForm()->getData();
                    $emails = $data->getEmails();
                    if ($emails->isEmpty()) {
                        $data->addEmail('');
                    }
                }
            );
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver){
            $resolver->setDefaults(array(
                'data_class' => 'Viteloge\FrontendBundle\Entity\Recommand',
                'intention' => 'task_form',
            ));
        }

        public function getName() {
            return 'viteloge_frontend_recommand';
        }

    }

}
