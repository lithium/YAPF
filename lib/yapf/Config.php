<?php


function try_require($path)
{
  if (file_exists($path)) {
    require $path;
    return true;
  }
  return false;
}

function __autoload($class_name) {
  $base_path = realpath(dirname(__FILE__).'/../../');

  if (try_require($base_path.'/lib/'.$class_name.'.php')) return;
  if (try_require($base_path.'/lib/yapf/'.$class_name.'.php')) return;
  if (try_require($base_path.'/entities/'.$class_name.'.php')) return;
}

class Config {
  public static function getConfig($app_name, $realm)
  {
    $base_path = realpath(dirname(__FILE__).'/../../');
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
