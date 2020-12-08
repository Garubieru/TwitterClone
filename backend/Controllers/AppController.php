<?php

namespace backend\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{
  public function home()
  {
    $this->isValidUser();
    $tweet = Container::getModel('Tweet');

    $tweet->__set('user_id', $_SESSION['id']);
    $this->view->tweets =  $tweet->recoverTweets();
    $this->render('home');
  }

  public function tweet()
  {
    $this->isValidUser();

    $tweet = Container::getModel('Tweet');
    $tweet->__set('user_id', $_SESSION['id']);
    $tweet->__set('tweet', $_POST['tweet']);

    $tweet->insertTweet();

    header('Location: /home');
  }

  public function explore()
  {
    $this->isValidUser();

    $searchFor = isset($_GET['search']) ? $_GET['search'] : '';

    $user = Container::getModel('User');
    $user->__set('id', $_SESSION['id']);

    if (!empty($searchFor)) {
      $user->__set('username', $_GET['search']);
      $searchedUser = $user->recoverUser();

      $this->view->searchedUser = $searchedUser;
    } else {
      $searchedUser = $user->recoverAllUsers();

      $this->view->searchedUser = $searchedUser;
    }
    $this->render('explore');
  }

  public function action()
  {
    $this->isValidUser();

    $action = isset($_GET['action']) ? $_GET['action'] : '';
    $follow_id = isset($_GET['id']) ? $_GET['id'] : '';

    $user = Container::getModel('User');
    $user->__set('id', $_SESSION['id']);

    if ($action == 'follow') {
      $user->followUser($follow_id);
    } else if ($action == 'unfollow') {
      $user->unfollowUser($follow_id);
    }

    header('Location: /explore');
  }

  public function logout()
  {
    session_start();
    session_destroy();

    header('Location: /');
  }

  public function isValidUser()
  {
    session_start();
    if (!isset($_SESSION['username']) && !isset($_SESSION['id']) && !isset($_SESSION['name'])) {
      header("Location: {$_SESSION['route']}?login=error");
      session_destroy();
    }
  }
}
