<?php

require ( dirname(__FILE__).'/../lib/Config.php' );

$config = Config::getConfig('frontend','prod');
FrontController::instance($config)->dispatch();
