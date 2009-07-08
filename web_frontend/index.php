<?php

require ( dirname(__FILE__).'/../lib/yapf/Config.php' );

$config = Config::getConfig('frontend','dev');
FrontController::instance($config)->dispatch();
