<?php

class FrontController 
{
  private static $instance = null;
  public $base_path;
  private $config;
  private $module_name;
  private $action_name;
  private $request;
  private $response;

  public static function instance($config=null)
  {
    if (is_null(self::$instance))
      self::$instance = new FrontController($config);
    return self::$instance;
  }
  private function __construct($config) { 
    $this->base_path = realpath(dirname(__FILE__).'/../../');
    $this->config = $config;
    $this->app_name = $config['app'];
    $this->realm_name = $config['realm'];
    $this->request = new Request();
    $this->response = new Response();

    Database::instance(
      $this->config['database_name'], $this->config['database_host'], $this->config['database_user'], $this->config['database_password']);
  }

  public function dispatch()
  {
    $request_uri = $this->request->getRequestUri();
    if (($idx = strpos($request_uri, '?')) !== FALSE)
      $request_uri = substr($request_uri,0,$idx);

    $request_paths = explode('/',substr($request_uri,1));
    $this->module_name = $request_paths[0];
    if (!$this->module_name) {
      $this->module_name = $this->config['default_module'];
    }

    if (count($request_paths) > 1)
      $this->action_name = $request_paths[1];
    else
      $this->action_name = 'index';

    // call module actions first if exists
    $ret = $this->execute_action(ucfirst($this->module_name).'Actions');
    if ($ret === FALSE) return;

    // try standalone action file 
    $ret = $this->execute_action(ucfirst($this->action_name).'Action');
    if ($ret === FALSE) return;


    $template_output = '';

    // parse the action template
    $path = $this->base_path.'/apps/'.$this->app_name.'/'.$this->module_name.'/templates/'.$this->action_name.'.php';
    if (file_exists($path)) {
      $template_output = self::scoped_include($path,$this->request->getAttributes());
    }

    // check for layout template
    $path = $this->base_path.'/apps/'.$this->app_name.'/templates/layout.php';
    if (file_exists($path)) {
      $template_output = self::scoped_include($path, array(
        'yapf_content' => $template_output,
      ));
    }

    $this->response->write($template_output);
    $this->response->flush();
  }

  private function execute_action($class_name)
  {
    $path = $this->base_path.'/apps/'.$this->app_name.'/'.$this->module_name.'/actions/'.$class_name.'.php';
    if (!file_exists($path)) return null; 
    require $path;
    $action = new $class_name($this->module_name, $this->action_name, $this->request, $this->response);
    return $action->execute($this->request,$this->response);
  }
  private static function scoped_include($__TEMPLATE__, $vars=array())
  {
    if (!file_exists($__TEMPLATE__)) return false;
    if (is_array($vars)) 
      foreach($vars as $k => $v) {
        $$k = $v;
      }
    unset($k,$v,$vars);
    ob_start();
    include($__TEMPLATE__);
    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;
  }
}

