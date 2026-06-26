<?php
function autoload($class)
{
  include __DIR__ . '/controllers/' . $class . '.php';
}

spl_autoload_register('autoload');
