<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
ini_set('display_errors', '0');

defined('CI_DEBUG') || define('CI_DEBUG', false);

\Kint\Kint::$enabled_mode = false;
