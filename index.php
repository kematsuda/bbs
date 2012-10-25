<?php

require dirname(__FILE__) . '/app/controller/BBSController.php';
require dirname(__FILE__) . '/app/model/ThreadInfo.php';

$c = new BBSController;
$c->show();

