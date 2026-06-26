<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/autoload.php';

$templates = new Templates();
$page = $templates->getPageURL();
$data = $templates->data($page);

if ($page === '/skills') {
  $treeBuilder = new TreeBuilder();
  $data['tree'] = $treeBuilder->getTreeView();
}

echo $templates->render($page, $data);
