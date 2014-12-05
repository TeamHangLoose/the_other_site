<?php

namespace User\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;
use ZfcUser\Service\User as UserService;
use ZfcUser\Options\UserControllerOptionsInterface;
/**
 * Description of GuestController
 *
 * @author sommer
 */
class GuestController extends AbstractActionController {

    const ROUTE_PASSWORDFORGOT = 'password-forgot';
    const ROUTE_LOGIN        = 'zfcuser/login';

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
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
         

        $form = $this->getPasswordForgotForm();
        $prg = $this->prg(static::ROUTE_PASSWORDFORGOT);

        $fm = $this->flashMessenger()->setNamespace('password-forgot')->getMessages();
        
        if (isset($fm[0])) {
            $status = $fm[0];
             
        } else {
            $status = null;
           
        }

        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'status' => $status,
                'passwordForgotForm' => $form,
            );
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            return array(
                'status' => false,
                'passwordForgotForm' => $form,
            );
        }
        
       
        if (!$this->getGuestService()->sendForgotPasswordSmtp($form->getData())) {
            return array(
                'status' => false,
                'passwordForgotForm' => $form,
            );
        }

        $this->flashMessenger()->setNamespace('password-forgot')->addMessage(true);
        return $this->redirect()->toRoute(static::ROUTE_PASSWORDFORGOT);
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

    
        public function getGuestService()
    {
        if (!$this->guestService) {
            $this->guestService = $this->getServiceLocator()->get('extuser_guest_service');
        }
        return $this->guestService;
    }

    public function setGuestService(UserService $guestService)
    {
        $this->guestService = $guestService;
        return $this;
    }
 
    /**
     * set options
     *
     * @param GuetControllerOptionsInterface $options
     * @return GuestController
     */
    public function setOptions(UserControllerOptionsInterface $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * get options
     *
     * @return UserControllerOptionsInterface
     */
    public function getOptions()
    {
        if (!$this->options instanceof UserControllerOptionsInterface) {
            $this->setOptions($this->getServiceLocator()->get('zfcuser_module_options'));
        }
        return $this->options;
    }

}
