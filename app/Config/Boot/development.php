<?php

/*
 *---------------------------------------------------------------
 * ERROR DISPLAY
 *---------------------------------------------------------------
 * In development, we want to show as many errors as possible to
 * help make sure they don't make it to production.
 */
error_reporting(-1);
ini_set('display_errors', '1');

/*
 * ---------------------------------------------------------------
 * DEBUG MODE
 * ---------------------------------------------------------------
 */
defined('CI_DEBUG') || define('CI_DEBUG', true);

/*
 * ---------------------------------------------------------------
 * KINT'S VISIBILITY
 * ---------------------------------------------------------------
 */
if (class_exists(\Kint\Kint::class)) {
    \Kint\Kint::$enabled_mode = \Kint\Kint::MODE_RICH;
}
