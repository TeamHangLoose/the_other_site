<?php

namespace User;

class Module {

    public function getConfig() {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap($e) {
        //adding costum fields to register form
        $eventManager = $e->getApplication()->getEventManager();
        $em = $eventManager->getSharedManager();

//        $em->attach(
//            'ZfcUser\Form\Register',
//            'init',
//            function($e)
//            {
//                /* @var $form \ZfcUser\Form\Register */
//                $form = $e->getTarget();
//                $form->add(
//                    array(
//                        'name' => 'userrole',
//                        'type' => 'hidden',
//                        'options' => array(
//                            'value' => 'Username',
//                        ),
//                    )
//                );
//            }
//        );


        $em->attach(
                'ZfcUser\Form\Register', 'init', function($e) {
            /* @var $form \ZfcUser\Form\Register */
            $form = $e->getTarget();



            $form->add(
                    array(
                        'name' => 'street',
                        'options' => array(
                            'label' => 'Street',
                        ),
                        'attributes' => array(
                            'type' => 'text',
                        ),
                    )
            );
            $form = $e->getTarget();
            $form->add(
                    array(
                        'name' => 'plz',
                        'options' => array(
                            'label' => 'Plz',
                        ),
                        'attributes' => array(
                            'type' => 'text',
                        ),
                    )
            );
            $form->add(
                    array(
                        'name' => 'village',
                        'options' => array(
                            'label' => 'Village',
                        ),
                        'attributes' => array(
                            'type' => 'text',
                        ),
                    )
            );
        }
        );



        $zfcServiceEvents = $e->getApplication()->getServiceManager()->get('zfcuser_user_service')->getEventManager();

        $orm = $e->getApplication()->getServiceManager()->get('Doctrine\ORM\EntityManager');

        $zfcServiceEvents->attach('register', function($e) use ($orm) {
            //standard Role for all new Users -> user
            $userRole = $orm->getRepository('User\Entity\Role')->find(2);
            $user = $e->getParam('user');
            /* @var $user \User\Entity\User */
            $user->getRoles()->add($userRole);
        });

        // you can even do stuff after it stores
//        $zfcServiceEvents->attach('register.post', function($e) {
//            /*$user = $e->getParam('user');*/
//        });
    }

    public function getServiceConfig() {
        return array(
            'invokables' => array(
                //'ZfcUser\Authentication\Adapter\Db' => 'ZfcUser\Authentication\Adapter\Db',
                //'ZfcUser\Authentication\Storage\Db' => 'ZfcUser\Authentication\Storage\Db',
                //'ZfcUser\Form\Login'                => 'ZfcUser\Form\Login',
                'zfcuser_user_service' => 'User\Service\User',
            //'zfcuser_register_form_hydrator'    => 'Zend\Stdlib\Hydrator\ClassMethods',
            ),
            'factories' => array(
                'zfcuser_change_adress_form' => function ($sm) {
                    $options = $sm->get('zfcuser_module_options');
                    $form = new Form\ChangeAdress(null, $sm->get('zfcuser_module_options'));
                    $form->setInputFilter(new Form\ChangeAdressFilter($options));
                    return $form;
                },
            
              'zfcuser_change_avatar_form' => function ($sm) {
              $options = $sm->get('zfcuser_module_options');
              $form = new Form\ChangeAvatar(null, $sm->get('zfcuser_module_options'));
              $form->setInputFilter(new Form\ChangeAvatarFilter($options));
              return $form;

             
              },
             
            ),
        );
    }

}
