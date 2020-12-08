<?php

namespace backend\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action
{
  public function authenticate()
  {
    $user = Container::getModel('User');
    $user_login = $_POST['user'];

    $user->__set('username', $user_login);
    $user->__set('email', $user_login);
    $user->__set('password', $_POST['password']);

    $loggedUser = $user->authUser();
    session_start();
    if ($loggedUser) {
      $_SESSION['id'] = $loggedUser->id;
      $_SESSION['name'] = $loggedUser->name;
      $_SESSION['username'] = $loggedUser->username;

      header('Location: /home');
    } else {
      header("Location: {$_SESSION['route']}?login=error");

      session_destroy();
    }
  }
}
