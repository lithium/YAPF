<?php

class Actions extends Action
{
  public function execute($request,$response)
  {
    $method = "execute".ucfirst($this->action);
    if (method_exists($this, $method)) 
      return $this->$method($request,$response);
    return null;
  }
}
