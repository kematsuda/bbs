<?php
ini_set( 'display_errors', 1 );
define( 'SMARTY_DIR', '/usr/local/lib/Smarty-2.6.18/libs/' );
require_once( SMARTY_DIR .'Smarty.class.php' );
$smarty = new Smarty();

$smarty->template_dir = dirname(__FILE__) . '/../templates/';
$smarty->compile_dir  = dirname(__FILE__) . '/../../var/smarty/templates_c/';
$smarty->config_dir   = dirname(__FILE__) . '/../../var/smarty/configs/';
$smarty->cache_dir   = dirname(__FILE__) . '/../../var/smarty/cache/';
