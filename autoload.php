<?php
function autoload($class)
{
  $paths = [
    __DIR__ . '/controllers/' . $class . '.php',
    __DIR__ . '/services/' . $class . '.php',
  ];

  foreach ($paths as $path) {
    if (is_file($path)) {
      include $path;
      return;
    }
  }
}

spl_autoload_register('autoload');
