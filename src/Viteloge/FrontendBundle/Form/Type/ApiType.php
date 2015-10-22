<?php

namespace Viteloge\FrontendBundle\Form\Type {

    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvent;
    use Symfony\Component\Form\FormEvents;
    use Doctrine\ORM\EntityRepository;
    use Doctrine\ORM\EntityManager;
    use Acreat\InseeBundle\Entity\InseeCity;

    class ApiType extends AbstractType {

        protected $em;

        public function __construct(EntityManager $em) {
            $this->em = $em;
        }

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder
                ->add('inseeCity', 'text', array(
                    'label' => 'usersearch.inseecity',
                    'data_class' => 'Acreat\InseeBundle\Entity\InseeCity',
                    'required' => true,
                    'empty_data' => null
                ))
                ->add('send', 'submit');

            $formModifier = function (FormInterface $form, /*InseeCity*/ $inseeCity) {
                $choices = (empty($inseeCity)) ? array() : array($inseeCity);
                $form->add('inseeCity', 'entity', array(
                    'class' => 'AcreatInseeBundle:InseeCity',
                    'data_class' => null,
                    'property' => 'getNameAndPostalcode',
                    'group_by' => 'inseeDepartment',
                    'label' => 'usersearch.inseecity',
                    'choices' => $choices,
                    'expanded' => false,
                    'multiple' => false,
                    'data' => $inseeCity, // not really necessary
                    'required' => true,
                    'empty_value' => '',
                    'empty_data' => null,
                    'mapped' => true
                ));
            };

            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $inseeCity = ($data) ? $data->getInseeCity() : null;
                    $formModifier($form, $inseeCity);
                }
            );

            $builder->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    if ($form->has('inseeCity')) {
                        $inseeCity = $form->get('inseeCity')->getData();
                        if (!($inseeCity instanceof InseeCity) || $inseeCity->getId() != $data['inseeCity']) {
                            $inseeCity = $this->em->getRepository('AcreatInseeBundle:InseeCity')->findOneById($data['inseeCity']);
                            $formModifier($form, $inseeCity);
                        }
                    }
                }
            );
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver){
            $resolver->setDefaults(array(
                'data_class' => 'Viteloge\FrontendBundle\Entity\Api',
                'intention' => 'task_form',
            ));
        }

        public function getName() {
            return 'viteloge_frontend_api';
        }

    }

}
