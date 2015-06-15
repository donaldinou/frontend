<?php
namespace Viteloge\FrontendBundle\Form\Type {

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Doctrine\Common\Persistence\ObjectManager;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Viteloge\FrontendBundle\Form\DataTransformer\InseeCityToNumericTransformer;

    class InseeCitySelectorType extends AbstractType {

        /**
         * @var ObjectManager
         */
        private $om;

        /**
         * @param ObjectManager $om
         */
        public function __construct(ObjectManager $om) {
            $this->om = $om;
        }

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $transformer = new InseeCityToNumericTransformer($this->om);
            $builder->addModelTransformer($transformer);
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver) {
            $resolver->setDefaults(array(
                'invalid_message' => 'The selected city does not exist',
            ));
        }

        public function getParent() {
            return 'entity';
        }

        public function getName() {
            return 'insee_city_selector';
        }
    }

}
