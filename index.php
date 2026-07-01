<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/autoload.php';

use Dotenv\Dotenv;

// --- DOTENV ---
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// --- TEMPLATES ---
$templates = new Templates();
$page = $templates->getPageURL();
$data = $templates->data($page);

// --- TREE BUILDER ---
if ($page === '/skilltree') {
  $treeBuilder = new TreeBuilder();
  $data['tree'] = $treeBuilder->getTreeView();
}

// --- CONTACT FORM SUBMIT ---
$contactMail = new ContactMail();
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if ($requestPath === '/contact/send') {
  if ($requestMethod !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
  }
}

if ($requestMethod === 'POST' && $requestPath === '/contact/send') {
  $contactMail::send();
  exit;
}

echo $templates->render($page, $data);
