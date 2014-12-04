<?php

namespace User\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use \User\Form\ChangeAdress;
/**
 * Description of ExtUserController
 *
 * @author win7
 */
class UserController extends \ZfcUser\Controller\UserController {

    const ROUTE_CHANGEADRESS = 'change-adress';
    protected $changeAdressForm;
        /**
     * @var UserService
     */
    protected $userService;

    public function changeadressAction() {

        // if the user isn't logged in, we can't change Adress
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $form = $this->getChangeAdressForm();
        $prg = $this->prg(static::ROUTE_CHANGEADRESS);

        $fm = $this->flashMessenger()->setNamespace('change-adress')->getMessages();
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
                'changeAdressForm' => $form,
            );
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            return array(
                'status' => false,
                'changeAdressForm' => $form,
            );
        }

        if (!$this->getUserService()->changeA($form->getData())) {
            return array(
                'status' => false,
                'changeAdressForm' => $form,
            );
        }

        $this->flashMessenger()->setNamespace('change-adress')->addMessage(true);
        return $this->redirect()->toRoute(static::ROUTE_CHANGEADRESS);
    }

    function setChangeAdressForm($changeAdressForm) {
        $this->changeAdressForm = $changeAdressForm;
    }

    
        public function getChangeAdressForm()
    {
        if (!$this->changeAdressForm) {
            $this->setChangeAdressForm($this->getServiceLocator()->get('zfcuser_change_adress_form'));
        }
        return $this->changeAdressForm;
    }
    

     public function getUserService()
    {
        if (!$this->userService) {
            $this->userService = $this->getServiceLocator()->get('zfcuser_user_service');
        }
        return $this->userService;
    }

    function setUserService(UserService $userService) {
        $this->userService = $userService;
    }


   
    
    
        }
