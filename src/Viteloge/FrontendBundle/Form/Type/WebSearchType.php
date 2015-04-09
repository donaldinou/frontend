<?php

namespace Viteloge\FrontendBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Doctrine\ORM\EntityRepository;
    use Viteloge\CoreBundle\Component\DBAL\EnumTransactionType;

    class WebSearchType extends AbstractType {

        public function buildForm(FormBuilderInterface $builder, array $options) {

        }

        public function setDefaultOptions(OptionsResolverInterface $resolver){
            $resolver->setDefaults(array(
                'data_class' => 'Viteloge\CoreBundle\Entity\WebSearch',
            ));
        }

        public function getName() {
            return 'viteloge_frontend_websearch';
        }

    }

}
