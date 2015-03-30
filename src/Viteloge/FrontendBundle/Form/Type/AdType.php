<?php

namespace Viteloge\FrontendBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Doctrine\ORM\EntityRepository;
    use Acreat\CoreBundle\Component\DBAL\EnumTransactionType;

    class AdType extends AbstractType {

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $transactionChoices = EnumTransactionType::getValues();
            $transactionDefault = array_search(EnumTransactionType::getDefault(), $transactionChoices);
            $builder
                ->add('transaction', 'choice', array(
                    'choices' => $transactionChoices,
                    'expanded' => true,
                    'multiple' => false,
                    //'preferred_choices' => array($transactionDefault),
                    //'data' => $transactionDefault
                ))
                /*->add('inseeCity', 'entity', array(
                    'class' => 'AcreatInseeBundle:InseeCity',
                    'property' => 'name',
                    'expanded' => true,
                    'multiple' => true,
                    //'choices' => $group->getUsers(),
                ))*/
                ->add('inseeCity', 'search', array())
                ->add('type', 'choice', array(
                    'choices' => array( 'Appartement', 'Maison' ),
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => 'What',
                    'preferred_choices' => array(),
                    'data' => null
                ))
                ->add('rooms', null, array())
                ->add('price', 'money', array())
                ->add('search', 'submit');
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver){
            $resolver->setDefaults(array(
                'data_class' => 'Viteloge\CoreBundle\Entity\Ad',
            ));
        }

        public function getName() {
            return 'viteloge_frontend_ad';
        }

    }

}
