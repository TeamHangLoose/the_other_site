<?php

namespace User\Service;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author win7
 */
class User extends \ZfcUser\Service\User {

    public function changeAdress(array $data) {

        $currentUser = $this->getAuthService()->getIdentity();

        $bcrypt = new \Zend\Crypt\Password\Bcrypt();
        $bcrypt->setCost($this->getOptions()->getPasswordCost());


        if (!$bcrypt->verify($data['credential'], $currentUser->getPassword())) {
            return false;
        }

        $currentUser->setStreet($data['newStreet']);
        $currentUser->setPlz($data['newPlz']);
        $currentUser->setVillage($data['newVillage']);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $currentUser));



        return true;
    }

    public function changeAvatar(array $data) {

        $currentUser = $this->getAuthService()->getIdentity();

        $bcrypt = new \Zend\Crypt\Password\Bcrypt();
        $bcrypt->setCost($this->getOptions()->getPasswordCost());


        if (!$bcrypt->verify($data['credential'], $currentUser->getPassword())) {
            return false;
        }

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
// Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
// Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
// Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            return false;
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
                return true;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

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
