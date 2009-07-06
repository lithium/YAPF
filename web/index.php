<?php

require ( dirname(__FILE__).'/../lib/Config.php' );

$config = Config::getConfig('api','dev');
FrontController::instance($config)->dispatch();
