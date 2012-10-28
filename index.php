<?php

require_once dirname(__FILE__) . '/app/controller/BBSController.php';
require_once dirname(__FILE__) . '/app/model/ThreadInfo.php';
require_once dirname(__FILE__) . '/../../core/Request.php';

$request = new Request();
$request_uri = preg_replace('/favicon.ico', '', $request->getRequestUri());
$c = new BBSController;

if($request_uri === '/' || $request_uri === '/index.php') {
    $c->show();
}
elseif($request_uri === '/create/') {
    $c->create();
}
elseif(preg_match('/^\/thread/(\d+)\/$/', $request_uri, $matches) === 1) {
    $id = $matches[1];
    $c->showThread($id);
}
else {
    header("Location: ./index.php");
}
