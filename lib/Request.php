<?php

class Request
{
   const UNKNOWN=-1;
   const GET=1;
   const POST=2;

  private $attributes=array();
  private $headers=null;

  public function __construct() {
    $this->content = file_get_contents("php://input");
    foreach ($_SERVER as $h => $v) {
      if (substr($h,0,5) == 'HTTP_')
        $this->headers[substr($h,5)] = $v;
    }
  }

  public function getAttribute($name, $default=null) {
    if (array_key_exists($name, $this->attributes)) 
      return $this->attributes[$name];
    return $default;
  }
  public function getAttributes() {
    return $this->attributes;
  }
  public function getContent() {
    return $this->content;
  }
  public function getContentLength() { 
    return strlen($this->content);
  }
  public function getCookie($name,$default=null) { 
    if (array_key_exists($name, $_COOKIE))
      return $_COOKIE[$name]; 
    return $default;
  }
  public function getCookies() { 
    return $_COOKIE; 
  }
  public function getHeader($name,$default=null) {
    if (array_key_exists($name,$this->headers)) 
      return $this->headers[$name];
    return $default;
  }
  public function getHeaders() {
    return $this->headers;
  }
  public function getMethod() {
    switch(strtoupper($_SERVER['REQUEST_METHOD'])) {
      case "GET": return Request::GET;
      case "POST": return Request::POST;
      default: return Request::UNKNOWN;
    }
  } 
  public function getQueryString() { 
    return $_SERVER['QUERY_STRING']; 
  }
  public function getRemoteAddr() { 
    return $_SERVER['SERVER_ADDR']; 
  }
  public function getRemoteHost() { 
    return $_SERVER['HTTP_HOST']; 
  }
  public function getRequestUri() { 
    return $_SERVER['REQUEST_URI']; 
  }
  public function getServerPort() { 
    return $_SERVER['SERVER_PORT']; 
  }
  public function removeAttribute($name) {
    if (array_key_exists($name,$this->headers))
      unset($this->headers[$name]);
  }
  public function setAttribute($name,$value) {
    $this->attributes[$name] = $value;
  }  

  public function getParameter($name,$default=null) {
    if (array_key_exists($name, $_REQUEST))
      return $_REQUEST[$name];
    return $default;
  }
  
  public function getParameters() {
    $ret = array();
    for ($i=0; $i < func_num_args(); $i++) {
      $param = func_get_arg($i);
      if (is_array($param)) {
        foreach($param as $p) {
          $v = $this->getParameter($p);
          if ($v) $ret[$p] = $v;
        }
      } else {
        $v = $this->getParameter($param);
        if ($v) $ret[$param] = $v;
      }
    }
    return $ret;
  }
}
