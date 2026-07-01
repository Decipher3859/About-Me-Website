<?php

class Nav
{

  public function header()
  {
    $header = [
      'links' => [
        ['url' => '/', 'name' => 'Home'],
        ['url' => '/skilltree', 'name' => 'Skilltree'],
        ['url' => '/projects', 'name' => 'Projekte'],
        ['url' => '/next-steps', 'name' => 'Ausbildung'],       
        ['url' => '/contact', 'name' => 'Kontakt'],       
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
