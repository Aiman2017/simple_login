<?php

namespace validation;

use Database\Database;

defined('ROOTPATH') OR exit('Access Denied!');
class Validation
{
    public $errors = [];
    public static $allowedColumns = [
        'name',
        'email',
        'phone',
        'password',
    ];

    public function validate($POST)
    {
        $this->validateName($POST);
        $this->validateEmail($POST);
        $this->validatePhone($POST);
        $this->validatePassword($POST);

        //check if the column is exits
        if (static::$allowedColumns) {
            foreach ($POST as $k => $v) {
                if (!in_array($k, Validation::$allowedColumns)) {
                    unset($POST[$k]);
                }
            }
        }
        if (empty($this->errors)) {
            $POST['password'] = hash('sha1', $POST['password']);
            \Database\Database::insert('login_system', $POST);
            redirect('login');
        }

        return $this->errors;

    }

    private function validateName($POST)
    {
        //validate name
        if (trim($POST['name']) == '') {
            $this->errors['errorName'] = 'The name should be insert';
        } else {
            if (!preg_match('/^[a-zA-Z ]+$/', $POST['name'])) {
                $this->errors['errorName'] = 'User name should not contain any character';

            }
        }
    }

    private function validateEmail($POST)
    {

        //check if the email is already in used
        $data['email'] = $POST['email'];
        $result = Database::read('login_system', $data, 'email');
        if ($result[0]['count']) {
            $this->errors['checked'] = 'This email is already in our database';
        }
        // validate email
        if (trim($POST['email']) === '') {
            $this->errors['errorEmail'] = 'The email should be insert';
        } else {
            if (!filter_var($POST['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors['errorEmail'] = 'The email is not validate';
            }
        }
    }

    private function validatePhone($POST)
    {
        //check if the phone is already in used
        $data['phone'] = $POST['phone'];
        $result = Database::read('login_system', $data, 'phone');
        if ($result[0]['count']) {
            $this->errors['checked'] = 'This phone is already in our database';
        }
        if (trim($POST['phone']) === '') {
            $this->errors['errorPhone'] = 'The number should be insert';
        } else {
            if (!is_numeric($POST['phone'])) {
                $this->errors['errorPhone'] = 'The phone should be a numbers';
            }
        }
    }

    private function validatePassword($POST)
    {
        if (trim($POST['password']) === '') {
            $this->errors['errorPassword'] = 'The password should be insert';
        } else {
            if (strlen($POST['password']) < 4) {
                $this->errors['errorPassword'] = 'The password should be more or equal 4 character';
            }elseif($POST['password'] !== $POST['confirmPassword']) {
                $this->errors['confirmPassword'] = 'The password is not match';
            }
        }
    }

    public function login($POST)
    {
        $POST['password'] = hash('sha1', $POST['password']);
        $result = Database::loggedIn('login_system', $POST);
        if (is_array($result) ) {
            if ( $result[0]['count']) {
                auth('USER');
                redirect('index');
            }else {
                $this->errors['errors'] = 'Wrong email or password';
            }
        }
        return $this->errors;
    }
}