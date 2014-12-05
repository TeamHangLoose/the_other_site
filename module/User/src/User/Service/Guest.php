<?php

namespace User\Service;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Zend\Authentication\AuthenticationService;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Crypt\Password\Bcrypt;
use Zend\Stdlib\Hydrator;
use ZfcBase\EventManager\EventProvider;
use ZfcUser\Mapper\UserInterface as UserMapperInterface;
use ZfcUser\Options\UserServiceOptionsInterface;

/**
 * Description of guest
 *
 * @author sommer
 */
class Guest extends EventProvider implements ServiceManagerAwareInterface {

    /**
     * @var UserMapperInterface
     */
    protected $userMapper;

    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var UserServiceOptionsInterface
     */
    protected $options;

    /**
     * @var Hydrator\ClassMethods
     */
    protected $formHydrator;

    public function sendForgotPasswordSmtp($param) {

        // echo $param['newPasswordToEmail'];

        /*
         * 
         * @todo 
         *  check if user exist.
         *  if exist 
         *        generate new password
         *        put in db
         *        send mail with new password            
         *
         * 
         */
  
        /*
          $message = new \Zend\Mail\Message();
          $message->setBody('This is the body');
          $message->setFrom('myemail@mydomain.com');
          $message->addTo('someone@somedomain.com');
          $message->setSubject('Test subject');


          $smtpOptions = new \Zend\Mail\Transport\SmtpOptions();

          $smtpOptions->setHost('smtp.gmail.com')
          ->setConnectionClass('login')
          ->setName('smtp.gmail.com')
          ->setConnectionConfig(array(
          'username' => 'YOUR GMAIL ADDRESS',
          'password' => 'YOUR PASSWORD',
          'ssl' => 'tls',
          )
          );

          $transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
          $transport->send($message);
         * 
         * 
         */

        return false;
    }

    /**
     * getUserMapper
     *
     * @return UserMapperInterface
     */
    public function getUserMapper() {
        if (null === $this->userMapper) {
            $this->userMapper = $this->getServiceManager()->get('zfcuser_user_mapper');
        }
        return $this->userMapper;
    }

    /**
     * setUserMapper
     *
     * @param UserMapperInterface $userMapper
     * @return User
     */
    public function setUserMapper(UserMapperInterface $userMapper) {
        $this->userMapper = $userMapper;
        return $this;
    }

    /**
     * getAuthService
     *
     * @return AuthenticationService
     */
    public function getAuthService() {
        if (null === $this->authService) {
            $this->authService = $this->getServiceManager()->get('zfcuser_auth_service');
        }
        return $this->authService;
    }

    /**
     * setAuthenticationService
     *
     * @param AuthenticationService $authService
     * @return User
     */
    public function setAuthService(AuthenticationService $authService) {
        $this->authService = $authService;
        return $this;
    }

    /**
     * get service options
     *
     * @return UserServiceOptionsInterface
     */
    public function getOptions() {
        if (!$this->options instanceof UserServiceOptionsInterface) {
            $this->setOptions($this->getServiceManager()->get('zfcuser_module_options'));
        }
        return $this->options;
    }

    /**
     * set service options
     *
     * @param UserServiceOptionsInterface $options
     */
    public function setOptions(UserServiceOptionsInterface $options) {
        $this->options = $options;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager() {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Return the Form Hydrator
     *
     * @return \Zend\Stdlib\Hydrator\ClassMethods
     */
    public function getFormHydrator() {
        if (!$this->formHydrator instanceof Hydrator\HydratorInterface) {
            $this->setFormHydrator($this->getServiceManager()->get('zfcuser_register_form_hydrator'));
        }

        return $this->formHydrator;
    }

    /**
     * Set the Form Hydrator to use
     *
     * @param Hydrator\HydratorInterface $formHydrator
     * @return User
     */
    public function setFormHydrator(Hydrator\HydratorInterface $formHydrator) {
        $this->formHydrator = $formHydrator;
        return $this;
    }

    

}
