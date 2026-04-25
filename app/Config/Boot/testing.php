<?php

error_reporting(-1);
ini_set('display_errors', '1');

defined('CI_DEBUG') || define('CI_DEBUG', true);

\Kint\Kint::$enabled_mode = \Kint\Kint::MODE_RICH;
