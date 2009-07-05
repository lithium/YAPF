<?php
class Response
{
  private $buffer='';
  private $headers=array();
  private $cookies=array();
  private $status=200;
  private $content_type='text/html';

  public function __construct() {}

  public function flush() {
    header('Status: '.$this->status);
    header('Content-Type: '.$this->content_type);
    foreach($this->headers as $name => $value) {
      header($name.': '.$value);
    }
    foreach($this->cookies as $name => $values) {
      setcookie($name, $values['value'], $values['expire'], $values['path'], $values['domain']);
    }
    echo $this->buffer;
    $this->buffer = '';

  }
  public function getContentLength() {
    return strlen($this->buffer);
  }
  public function sendRedirect($location,$response_code=302) {
    header("Location: $location",true,$response_code);
  }
  public function setContentType($type) {
    $this->content_type = $type;
  }
  public function setCookie($name, $value,$expire=0,$path='/',$domain='') {
    $this->cookies[$name] = array(
      'value' => $value,
      'expire' => $expire,
      'path' => $path,
      'domain' => $domain,
    );
  }
  public function setHeader($name,$value) {
    $this->headers[$name] = $value;
  }
  public function setStatus($code) {
    $this->status = $code;
  }
  public function write($data) {
    $this->buffer .= $data;
  }

}
