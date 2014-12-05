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
class Guest {

    public function sendForgotPasswordSmtp($param) {

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
    }

}
