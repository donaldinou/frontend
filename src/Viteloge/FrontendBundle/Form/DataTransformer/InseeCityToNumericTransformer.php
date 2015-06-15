<?php

namespace Viteloge\FrontendBundle\Form\DataTransformer {

    use Symfony\Component\Form\DataTransformerInterface;
    use Symfony\Component\Form\Exception\TransformationFailedException;
    use Doctrine\Common\Persistence\ObjectManager;
    use Acreat\InseeBundle\Entity\InseeCity;

    class InseeCityToNumericTransformer implements DataTransformerInterface {

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

        /**
         * Transforms an object (inseeCity) to a numeric.
         *
         * @param InseeCity $inseeCity
         * @return string
         */
        public function transform($inseeCity) {
            if (null === $inseeCity) {
                return '';
            }
            return $inseeCity->getId();
        }

        /**
         * Transforms a numberic to an object (inseeCity).
         *
         * @param string $id
         * @return InseeCity|null
         * @throws TransformationFailedException if object (inseeCity) is not found.
         */
        public function reverseTransform($id) {
            if (!$id) {
                return null;
            }

            $inseeCity = $this->om
                ->getRepository('AcreatInseeBundle:InseeCity')
                ->findOneBy(array('id' => $id))
            ;

            if (null === $inseeCity) {
                throw new TransformationFailedException(sprintf(
                    'InseeCity "%s" cannot be found!',
                    $id
                ));
            }

            return $inseeCity;
        }
    }

}
