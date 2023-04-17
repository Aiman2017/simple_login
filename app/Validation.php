<?php

namespace validation;

use Database\Database;

defined('ROOTPATH') or exit('Access Denied!');

class Validation
{
    public $errors = [];

    public function validate($POST)
    {
        $this->validateName($POST);
        $this->validateEmail($POST);
        $this->validatePhone($POST);
        $this->validatePassword($POST);
        unset($POST['confirmPassword']);
        if (empty($this->errors)) {
            $POST['password'] = hash('sha1', $POST['password']);
            \Database\Database::insert('login_system', $POST);
            redirect('login');
        } else {
            return $this->errors;
        }
        return false;
    }

    public function validateName($POST)
    {
        //validate name
        if (trim($POST['name']) == '') {
            $this->errors['errorName'] = 'The name should be insert';
        } else {
            if (!preg_match('/^[a-zA-Z ]+$/', $POST['name'])) {
                $this->errors['errorName'] = 'User name should not contain any character';
            }
        }
        return $this->errors;

    }

    public function validateEmail($POST)
    {
        // validate email
        if (trim($POST['email']) === '') {
            $this->errors['errorEmail'] = 'The email should be insert';
        } else {
            if (!filter_var($POST['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors['errorEmail'] = 'The email is not validate';
            } else {
                //check if the email is already in used
                $data['email'] = $POST['email'];
                $result = Database::read('login_system', $data, 'email');
                if ($result[0]['count']) {
                    $this->errors['checked'] = 'This email is already in our database';
                }
            }
        }
        return $this->errors;
    }

    public function validatePhone($POST)
    {

        if (trim($POST['phone']) === '') {
            $this->errors['errorPhone'] = 'The number should be insert';
        } else {
            if (!is_numeric($POST['phone'])) {
                $this->errors['errorPhone'] = 'The phone should be a numbers';
            } else {
                //check if the phone is already in used
                $data['phone'] = $POST['phone'];
                $result = Database::read('login_system', $data, 'phone');
                if ($result[0]['count'] && $POST !== '') {
                    $this->errors['checked'] = 'This phone is already in our database';
                }
            }
        }
        return $this->errors;

    }

    public function validatePassword($POST)
    {

        if (trim($POST['password']) === '') {
            $this->errors['errorPassword'] = 'The password should be insert';
        } else {
            if (strlen($POST['password']) < 4) {
                $this->errors['errorPassword'] = 'The password should be more or equal 4 character';
            } elseif ($POST['password'] !== $POST['confirmPassword']) {
                $this->errors['confirmPassword'] = 'The password is not match';
            }
        }
        return $this->errors;
    }

    public function login($POST)
    {
        $POST['password'] = hash('sha1', $POST['password']);
        $result = Database::loggedIn('login_system', $POST);
        $res = \Database\Database::readAll('login_system');
        $row = '';
        foreach ($res as $re => $v) {
            $row = $v;
        }
        $id = $row['id'];
        if (is_array($result)) {
            if ($result[0]['count']) {
                $secret = '6Lcqn5IlAAAAANMQNVZAGfMoPIuYyMgFtCjT4B9y';
                $response = $POST['g-recaptcha-response'];
                $remoteip = $_SERVER['REMOTE_ADDR'];
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=${secret}&response=${response}&remoteip=${remoteip}";
                $data = file_get_contents($url);
                $dataConvert = json_decode($data, true);
                if ($dataConvert['success']) {
                    auth('USER');
                    header("Location: index.php?editprofile&id=$id");
                } else {
                    $this->errors['errors'] = 'please confirm that your not a robot';
                }

            } else {
                $this->errors['errors'] = 'Wrong email or password';
            }
        }
        return $this->errors;
    }
}