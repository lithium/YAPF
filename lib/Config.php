<?php

function __autoload($class_name) {
  $base_path = realpath(dirname(__FILE__).'/../');
  $path = $base_path.'/lib/'.$class_name.'.php';
  if (file_exists($path))
    require $path;
}

class Config {
  public static function getConfig($app_name, $realm)
  {
    $base_path = realpath(dirname(__FILE__).'/../');
    $path = $base_path."/apps/{$app_name}/config.ini";
    $ini_config = parse_ini_file($path,true);
    $config = $ini_config['default'];
    if (array_key_exists($realm, $ini_config)) {
      $config = array_merge($config, $ini_config[$realm]);
    }
    $config['app'] = $app_name;
    $config['realm'] = $realm;
    return $config;
  }
}
