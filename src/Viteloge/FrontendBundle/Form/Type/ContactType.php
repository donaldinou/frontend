<?php

namespace Viteloge\FrontendBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Viteloge\FrontendBundle\Component\Enum\SubjectEnum;

    class ContactType extends AbstractType {

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $subjectEnum = new SubjectEnum();
            $builder
                ->add('firstname')
                ->add('lastname')
                ->add('company')
                ->add('phone')
                ->add('email')
                ->add('message', 'textarea')
                ->add('subject', 'choice', array(
                    'choices' => $subjectEnum->choices(),
                    'expanded' => false,
                    'multiple' => false,
                    'required' => true,
                    'empty_value' => 'contact.empty_value',
                    'preferred_choices' => array()
                ))
                ->add('address', 'textarea')
                ->add('postalCode')
                ->add('city')
                ->add('send', 'submit');
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver){
            $resolver->setDefaults(array(
                'data_class' => 'Viteloge\FrontendBundle\Entity\Contact',
                'intention' => 'task_form',
            ));
        }

        public function getName() {
            return 'viteloge_frontend_contact';
        }

    }

}
