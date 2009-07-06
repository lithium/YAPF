<?php

require ( dirname(__FILE__).'/../lib/Config.php' );

$config = Config::getConfig('api','prod');
FrontController::instance($config)->dispatch();
