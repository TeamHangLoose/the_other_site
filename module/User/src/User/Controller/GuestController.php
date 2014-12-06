<?php

namespace User\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Zend\Mvc\Controller\AbstractActionController;
use ZfcUser\Service\User as UserService;
use ZfcUser\Options\UserControllerOptionsInterface;

/**
 * Description of GuestController
 *
 * @author sommer
 */
class GuestController extends AbstractActionController {

    const ROUTE_PASSWORDFORGOT = 'password-forgot';
    const ROUTE_LOGIN = 'zfcuser/login';

    protected $passwordForgotForm;

    /**
     * @var UserService
     */
    protected $guestService;

    /**
     * @var GuestControllerOptionsInterface
     */
    protected $options;

    public function passwordforgotAction() {
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $request = $this->getRequest();
        $form = $this->getPasswordForgotForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }
     
          if (!$request->isPost()) {
          return array(
          'passwordForgotForm' => $form,
          'redirect'  => $redirect,
          'enableRegistration' => $this->getOptions()->getEnableRegistration(),
          );
          } 

        $form->setData($request->getPost());


        $r = '';
        foreach ($request as $key => $value) {
            $r = $r . $key . '  ' . $value . '<br>';
        }
        echo $r;

        /*
          if (!$form->isValid()) {
          $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
          return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_PASSWORDFORGOT).($redirect ? '?redirect='. rawurlencode($redirect) : ''));
          }
         */

        // clear adapters
        //$this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        //$this->zfcUserAuthentication()->getAuthService()->clearIdentity();
        // return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
    }

    function getPasswordForgotForm() {
        if (!$this->passwordForgotForm) {
            $this->setPasswordForgotForm($this->getServiceLocator()->get('zfcuser_password_forgot_form'));
        }
        return $this->passwordForgotForm;
    }

    function setPasswordForgotForm($passwordForgotForm) {
        $this->passwordForgotForm = $passwordForgotForm;
    }

    public function getGuestService() {
        if (!$this->guestService) {
            $this->guestService = $this->getServiceLocator()->get('extuser_guest_service');
        }
        return $this->guestService;
    }

    public function setGuestService(UserService $guestService) {
        $this->guestService = $guestService;
        return $this;
    }

    /**
     * set options
     *
     * @param GuetControllerOptionsInterface $options
     * @return GuestController
     */
    public function setOptions(UserControllerOptionsInterface $options) {
        $this->options = $options;
        return $this;
    }

    /**
     * get options
     *
     * @return UserControllerOptionsInterface
     */
    public function getOptions() {
        if (!$this->options instanceof UserControllerOptionsInterface) {
            $this->setOptions($this->getServiceLocator()->get('zfcuser_module_options'));
        }
        return $this->options;
    }

}
