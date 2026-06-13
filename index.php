<?php
require_once 'vendor/autoload.php';
require_once 'autoload.php';

$templates = new Templates();
$page = $templates->getPageURL();
$data = $templates->data($page);

echo $templates->render($page, $data);
