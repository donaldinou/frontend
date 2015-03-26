<?php

namespace Viteloge\FrontendBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Doctrine\ORM\EntityRepository;
    use Acreat\CoreBundle\Component\DBAL\EnumTransactionType;

    class AdType extends AbstractType {

        public function buildForm(FormBuilderInterface $builder, array $options) {
            if (!\Doctrine\DBAL\Types\Type::hasType('enumtransaction')) {
                \Doctrine\DBAL\Types\Type::addType('enumtransaction', 'Acreat\CoreBundle\Component\DBAL\EnumTransactionType');
            }
            $transactionChoices = EnumTransactionType::getValues();
            $transactionDefault = array_search(EnumTransactionType::getDefault(), $transactionChoices);
            $builder
                ->add('transaction', 'choice', array(
                    'choices' => $transactionChoices,
                    'expanded' => true,
                    'multiple' => false,
                    'placeholder' => 'Choose an option',
                    'preferred_choices' => array($transactionDefault),
                    'data' => $transactionDefault
                ))
                ->add('inseeCity', 'entity', array(
                    'class' => 'AcreatInseeBundle:InseeCity',
                    'property' => 'translations[en].name'
                    //'choices' => $group->getUsers(),
                ))
                ->add('type')
                ->add('rooms')
                ->add('price')
                ->add('search', 'submit');
        }

        public function getName() {
            return 'viteloge_frontend_ad';
        }

    }

}
