<?php
namespace User\Form;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use ZfcBase\Form\ProvidesEventsForm;
use ZfcUser\Options\AuthenticationOptionsInterface;
use Zend\Form\Form;
use Zend\Form\Element;


/**
 * Description of PasswordForgot
 *
 * @author sommer
 */
class PasswordForgot extends ProvidesEventsForm{
    
    
    protected $authOptions;

    public function __construct($name, AuthenticationOptionsInterface $options) {
        $this->setAuthenticationOptions($options);
        parent::__construct($name);

        $this->add(array(
            'name' => 'identity',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));

        $this->add(array(
            'name' => 'newPasswordToEmail',
            'options' => array(
                'label' => 'Your Email adress',
            ),
            'attributes' => array(
                'type' => 'email',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Submit',
                'type' => 'submit'
            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }

    /**
     * Set Authentication-related Options
     *
     * @param AuthenticationOptionsInterface $authOptions
     * @return Login
     */
    public function setAuthenticationOptions(AuthenticationOptionsInterface $authOptions) {
        $this->authOptions = $authOptions;
        return $this;
    }

    /**
     * Get Authentication-related Options
     *
     * @return AuthenticationOptionsInterface
     */
    public function getAuthenticationOptions() {
        return $this->authOptions;
    }

}
