<?php

namespace Viteloge\CoreBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Viteloge\CoreBundle\SearchEntity\AdSearch;
    use Viteloge\CoreBundle\Component\DBAL\EnumTransactionType;
    use Viteloge\CoreBundle\Component\Enum\TypeEnum;
    use Viteloge\CoreBundle\Component\Enum\RoomEnum;
    use Viteloge\CoreBundle\Component\Enum\DistanceEnum;

    class AdSearchType extends AbstractType {

        /**
         *
         */
        public function buildForm(FormBuilderInterface $builder, array $options) {
            $transactionChoices = EnumTransactionType::getValues();
            $transactionDefault = EnumTransactionType::getDefault();
            $typeEnum = new TypeEnum();
            $roomEnum = new RoomEnum();
            $distanceEnum = new DistanceEnum();
            $builder
                ->add('transaction', 'choice', array(
                    'choices' => $transactionChoices,
                    'expanded' => true,
                    'multiple' => false,
                    'preferred_choices' => array(),
                    'data' => $transactionDefault,
                ))
                ->add('where', 'text', array(
                    //'data_class' => 'Acreat\InseeBundle\Entity\InseeCity'
                ))
                ->add('what', 'choice', array(
                    'choices' => $typeEnum->choices(),
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => 'ad.type.what',
                    'preferred_choices' => array(),
                    'data' => null
                ))
                ->add('rooms', 'choice', array(
                    'choices' => $roomEnum->choices(),
                    'expanded' => false,
                    'multiple' => true
                ))
                ->add('keywords', 'text', array(
                    'label' => 'search.keywords',
                    'required' => false
                ))
                ->add('minPrice', 'money', array(
                    'label' => 'search.price_min',
                    'required' => false,
                    'precision' => 0
                ))
                ->add('maxPrice', 'money', array(
                    'label' => 'search.price_max',
                    'required' => false,
                    'precision' => 0
                ))
                ->add('radius', 'choice', array(
                    'label' => 'search.rayon',
                    'choices' => $distanceEnum->choices(),
                    'required' => false,
                    'empty_value' => false
                ))
                ->add('search', 'submit')
            ;
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver) {
            parent::setDefaultOptions($resolver);
            $resolver->setDefaults(
                array(
                    'data_class' => 'Viteloge\CoreBundle\SearchEntity\Ad'
                )
            );
        }

        public function getName() {
            return 'viteloge_core_adsearch';
        }

    }

}
