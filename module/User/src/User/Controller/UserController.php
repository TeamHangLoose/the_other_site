<?php

namespace User\Controller;


class UserController extends \ZfcUser\Controller\UserController {

    const ROUTE_CHANGEADRESS = 'change-adress';
    const ROUTE_CHANGEAVATAR = 'change-avatar';

    protected $changeAdressForm;
    protected $changeAvatarForm;

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

        if (!$this->getUserService()->changeAdress($form->getData())) {
            return array(
                'status' => false,
                'changeAdressForm' => $form,
            );
        }

        $this->flashMessenger()->setNamespace('change-adress')->addMessage(true);
        return $this->redirect()->toRoute(static::ROUTE_CHANGEADRESS);
    }

    public function changeavatarAction() {
        // if the user isn't logged in, we can't change Adress
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
      
        $r = "";
        foreach ($_REQUEST as $key => $value){
         $r = $r.$key.'  --> '.$value.'<br>';   
        }
        echo $r;

        $form = $this->getChangeAvatarForm();
        $prg = $this->prg(static::ROUTE_CHANGEAVATAR);

        $fm = $this->flashMessenger()->setNamespace('change-avatar')->getMessages();
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
                'changeAvatarForm' => $form,
            );
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            return array(
                'status' => false,
                'changeAvatarForm' => $form,
            );
        }

        if (!$this->getUserService()->changeAvatar($form->getData())) {
            return array(
                'status' => false,
                'changeAvatarForm' => $form,
            );
        }

        $this->flashMessenger()->setNamespace('change-adress')->addMessage(true);
        return $this->redirect()->toRoute(static::ROUTE_CHANGEAVATAR);
    }

    function setChangeAdressForm($changeAdressForm) {
        $this->changeAdressForm = $changeAdressForm;
    }

    public function getChangeAdressForm() {
        if (!$this->changeAdressForm) {
            $this->setChangeAdressForm($this->getServiceLocator()->get('zfcuser_change_adress_form'));
        }
        return $this->changeAdressForm;
    }

    function getChangeAvatarForm() {
        if (!$this->changeAvatarForm) {
            $this->setChangeAvatarForm($this->getServiceLocator()->get('zfcuser_change_avatar_form'));
        }
        return $this->changeAvatarForm;
    }

    function setChangeAvatarForm($changeAvatarForm) {
        $this->changeAvatarForm = $changeAvatarForm;
    }

}
