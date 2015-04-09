<?php

namespace Viteloge\FrontendBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Doctrine\ORM\EntityRepository;
    use Viteloge\CoreBundle\Component\DBAL\EnumTransactionType;

    class UserSearchType extends AbstractType {

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $transactionChoices = EnumTransactionType::getValues();
            $transactionDefault = array_search(EnumTransactionType::getDefault(), $transactionChoices);
            $builder
                ->add('transaction', 'choice', array(
                    'choices' => $transactionChoices,
                    'expanded' => true,
                    'multiple' => false
                ))
                //->add('inseeCity', 'search', array())
                ->add('type', 'choice', array(
                    'choices' => array( 'Appartement', 'Maison' ),
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => 'What',
                    'preferred_choices' => array(),
                    'data' => null
                ))
                ->add('rooms', null, array())
                ->add('save', 'submit');
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver){
            $resolver->setDefaults(array(
                'data_class' => 'Viteloge\CoreBundle\Entity\UserSearch',
            ));
        }

        public function getName() {
            return 'viteloge_frontend_usersearch';
        }

    }

}
