<?php

class Nav
{

  public function header()
  {
    $header = [
      'links' => [
        ['url' => '/', 'name' => 'Home'],
        ['url' => '/about', 'name' => 'About'],
      ]
    ];
    return $header;
  }

  public function footer()
  {
    $footer = [
      'links' => [
        ['url' => '/contact', 'name' => 'Kontakt'],
        ['url' => '/impressum', 'name' => 'Impressum'],
        ['url' => '/privacy', 'name' => 'Datenschutz'],
      ]
    ];
    return $footer;
  }
}
