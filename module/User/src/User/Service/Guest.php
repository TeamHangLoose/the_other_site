<?php

namespace User\Service;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of guest
 *
 * @author sommer
 */
class Guest extends \ZfcUser\Service\User {

    /**
     * @var Form
     */
    protected $passwordForgotForm;

    public function sendForgotPasswordSmtp($data) {

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
        $class = $this->getOptions()->getUserEntityClass();
        $user = new $class;
        $form = $this->getRegisterForm();
        $form->setHydrator($this->getFormHydrator());
        $form->bind($user);
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $request = $this->getRequest();
        $form    = $this->getLoginForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }

        if (!$request->isPost()) {
            return array(
                'loginForm' => $form,
                'redirect'  => $redirect,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
            );
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN).($redirect ? '?redirect='. rawurlencode($redirect) : ''));
        }

        // clear adapters
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

        return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));

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

        return true;
    }

    /**
     * @return Form
     */
    public function getPasswordForgotForm() {
        if (null === $this->passwordForgotForm) {
            $this->passwordForgotForm = $this->getServiceManager()->get('zfcuser_password_forgot_form');
        }
        return $this->passwordForgotForm;
    }

    /**
     * @param Form $changePasswordForm
     * @return User
     */
    public function setPasswordForgotForm(Form $passwordForgotForm) {
        $this->passwordForgotForm = $passwordForgotForm;
        return $this;
    }

}
