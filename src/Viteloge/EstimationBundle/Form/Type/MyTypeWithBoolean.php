<?php

namespace Viteloge\EstimationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

abstract class MyTypeWithBoolean extends AbstractType
{
    public static $BOOL_CHOICES = array( true => 'estimation.bool.oui', false => 'estimation.bool.non' );
    

    protected function makeBool( $label, $required = false ) {
        return array(
            'choices' => self::$BOOL_CHOICES,
            'expanded' => true,
            'required' => $required,
            'label' => $label,
            'empty_value' => false
        )
        ;
    }
}
