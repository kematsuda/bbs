<?php

require_once '/usr/local/lib/Smarty-3.1.12/libs/Smarty.class.php';
class SmartyConfig
{
    public static function setup()
    {
        ini_set( 'display_errors', 1 );
        $smarty = new Smarty();
        $smarty->template_dir = dirname(__FILE__) . '/../templates/';
        $smarty->compile_dir  = dirname(__FILE__) . '/../../var/smarty/templates_c/';
        $smarty->config_dir   = dirname(__FILE__) . '/../../var/smarty/configs/';
        $smarty->cache_dir   = dirname(__FILE__) . '/../../var/smarty/cache/';
        return $smarty;
    }
}