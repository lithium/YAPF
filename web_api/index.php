<?php

require ( dirname(__FILE__).'/../lib/yapf/Config.php' );

$config = Config::getConfig('api','dev');
FrontController::instance($config)->dispatch();
