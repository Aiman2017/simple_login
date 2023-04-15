<?php
session_start();
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);
function show($stuff) {
    echo "<pre>";
    var_dump($stuff);
    exit();
}

function redirect($path) {
    header("Location: ${path}.php");
}

function isLoggedIn() {
    if (isset($_SESSION['USER'])) {
        return true;
    }
    return false;
}

function logout()
{
    if (isset($_SESSION['USER'])) {
        unset($_SESSION['USER']);
        redirect('login');
    }
}

function auth($auth) {
    $_SESSION['USER'] = $auth;
}

