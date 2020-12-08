<?php

namespace backend;

use MF\Init\Bootstrap;

class Route extends Bootstrap
{
  protected function initRoutes()
  {
    $routes['index'] = array(
      'route' => '/',
      'controller' => 'IndexController',
      'action' => 'index'
    );

    $routes['signup'] = array(
      'route' => '/signup',
      'controller' => 'IndexController',
      'action' => 'signup'
    );

    $routes['register'] = array(
      'route' => '/register',
      'controller' => 'IndexController',
      'action' => 'register'
    );

    $routes['login'] = array(
      'route' => '/login',
      'controller' => 'IndexController',
      'action' => 'login'
    );

    $routes['auth'] = array(
      'route' => '/auth',
      'controller' => 'AuthController',
      'action' => 'authenticate'
    );

    $routes['home'] = array(
      'route' => '/home',
      'controller' => 'AppController',
      'action' => 'home'
    );

    $routes['tweet'] = array(
      'route' => '/tweet',
      'controller' => 'AppController',
      'action' => 'tweet'
    );

    $routes['explore'] = array(
      'route' => '/explore',
      'controller' => 'AppController',
      'action' => 'explore'
    );

    $routes['logout'] = array(
      'route' => '/logout',
      'controller' => 'AppController',
      'action' => 'logout'
    );

    $routes['action'] = array(
      'route' => '/action',
      'controller' => 'AppController',
      'action' => 'action'
    );

    $this->setRoutes($routes);
  }
}
