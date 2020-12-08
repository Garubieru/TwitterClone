<?php

namespace MF\Controller;

abstract class Action
{
  protected $view;

  public function __construct()
  {
    $this->view = new \stdClass();
  }

  protected function render($view, $layout = 'layout')
  {
    $this->view->page = $view;
    if (file_exists("../backend/Views/$layout.phtml")) {
      require_once "../backend/Views/$layout.phtml";
    } else {
      $this->content();
    }
  }

  protected function content()
  {
    $class = str_replace('backend\\Controllers\\', '', get_class($this));
    $class = strtolower(str_replace('Controller', '', $class));

    require_once "../backend/Views/$class/{$this->view->page}.phtml";
  }
}
