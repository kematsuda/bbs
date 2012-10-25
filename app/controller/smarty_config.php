<?php

require_once '/usr/local/lib/Smarty-3.1.12/libs/Smarty.class.php';
class SmartyConfig extends Smarty
{
    public function setup()
    {
        $smarty->template_dir = dirname(__FILE__) . '/../templates/';
        $smarty->compile_dir  = dirname(__FILE__) . '/../../var/smarty/templates_c/';
        $smarty->config_dir   = dirname(__FILE__) . '/../../var/smarty/configs/';
        $smarty->cache_dir   = dirname(__FILE__) . '/../../var/smarty/cache/';
    }
}