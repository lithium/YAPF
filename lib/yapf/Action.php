<?php

class Action
{
  public $module;
  public $action;
  private $request;
  private $response;

  public function __construct($module, $action, &$request, &$response)
  {
    $this->module = $module;
    $this->action = $action;
    $this->request = $request;
    $this->response = $response;
  }

  public function __get($name) {
    return $this->request->getAttribute($name);
  }
  public function __set($name, $value) {
    return $this->request->setAttribute($name, $value);
  }
}
