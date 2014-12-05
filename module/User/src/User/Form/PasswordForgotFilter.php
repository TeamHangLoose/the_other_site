<?php

namespace User\Form;

use Zend\InputFilter\InputFilter;
use ZfcUser\Options\AuthenticationOptionsInterface;

class PasswordForgotFilter extends InputFilter {

    protected $emailValidator;

    
    public function __construct(AuthenticationOptionsInterface $options) {
        $identityParams = array(
            'name' => 'identity',
            'validators' => array()
        );

        $identityFields = $options->getAuthIdentityFields();
        if ($identityFields == array('email')) {
            $validators = array('name' => 'EmailAddress');
            array_push($identityParams['validators'], $validators);
        }

        //$this->add($identityParams);

        $this->add(array(
            'name' => 'newPasswordToEmail',
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        
                    ),
                ),
            ),
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
        ));

 
    
    }

}
