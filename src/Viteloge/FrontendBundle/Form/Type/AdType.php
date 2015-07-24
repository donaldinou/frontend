<?php

namespace Viteloge\FrontendBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Doctrine\ORM\EntityRepository;
    use Viteloge\CoreBundle\Component\Enum\TransactionEnum;
    use Viteloge\CoreBundle\Component\Enum\TypeEnum;

    class AdType extends AbstractType {

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $transactionEnum = new TransactionEnum();
            $typeEnum = new TypeEnum();
            $builder
                ->add('transaction', 'choice', array(
                    'choices' => $transactionEnum->choices(),
                    'expanded' => true,
                    'multiple' => false
                ))
                ->add('inseeCity', 'search', array())
                ->add('type', 'choice', array(
                    'choices' => $typeEnum->choices(),
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => 'What',
                    'preferred_choices' => array()
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
