<?php

namespace backend\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action
{
  public function index()
  {
    session_start();
    $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';

    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['name'])) {
      header('Location: /home');
    } else {
      $this->render('index');
    }
  }

  public function signup()
  {
    $this->view->user_info = array(
      'name' => '',
      'username' => '',
      'email' => '',
      'password' => ''
    );

    $this->view->error_info = array(
      'invalidRegisters' => False,
      'invalidEmail' => False,
      'invalidUsername' => False
    );

    $this->render('signup');
  }

  public function login()
  {
    session_start();
    $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
    $this->render('login');
  }

  public function register()
  {
    if (!empty($_POST)) {
      $user = Container::getModel('User');
      $user->__set('name', $_POST['name']);
      $user->__set('username', $_POST['username']);
      $user->__set('email', $_POST['email']);
      $user->__set('password', $_POST['password']);

      $validRegisters = $user->validRegisters();
      $validEmail = $user->validEmail();
      $validUsername = $user->validUsername();

      if ($validRegisters && $validEmail && $validUsername) {
        $this->render('success');
        $user->registerUser();
      } else {
        $error_info = array(
          'invalidRegisters' => !$validRegisters,
          'invalidEmail' => !$validEmail,
          'invalidUsername' => !$validUsername
        );

        $user_info = array(
          'name' => $_POST['name'],
          'username' => $_POST['username'],
          'email' => $_POST['email'],
          'password' => $_POST['password']
        );

        $this->view->error_info = $error_info;
        $this->view->user_info = $user_info;
        $this->render('signup');
      }
    } else {
      $this->signup();
    }
  }
}
