<?php
class Templates
{
  private $m;
  private $nav;

  public function __construct()
  {
    $this->m = (new \Mustache\Engine(
      [
        'partials_loader' => new \Mustache\Loader\FilesystemLoader('views/partials'),
      ]
    ));
    $this->nav = new Nav();
  }

  public function render($template, $data)
  {
    $template = @file_get_contents('views' .
      $template . '.html');
    if ($template === false) {
      $template = file_get_contents('views/404.html');
    }
    return $this->m->render($template, $data);
  }

  public function getPageURL()
  {
    $url = explode('?', $_SERVER['REQUEST_URI']);
    return ($url[0] == '/' ? '/home' : $url[0]);
  }

  public function data($page)
  {
    $data['nav']['header'] = $this->nav->header();
    $data['nav']['footer'] = $this->nav->footer();
    switch ($page) {
      case '/home':
        $data['content'] = [
          'title' => 'Home',
          'heading' => 'Diese Seite befindet sich im Aufbau!',
        ];
        break;
      case '/skills':
        $data['content'] = [
          'title' => 'Skills',
        ];
        break;
      case '/devlog':
        $data['content'] = [
          'title' => 'Meine Arbeitsweise',
        ];
        break;
      case '/contact':
        $data['content'] = [
          'title' => 'Kontakt',
        ];
        break;
      case '/impressum':
        $data['content'] = [
          'title' => 'Impressum',
        ];
        break;
      case '/privacy':
        $data['content'] = [
          'title' => 'Datenschutz',
        ];
        break;
      default:
        $data['content'] = [
          'title' => '404',
          'heading' => 'Oops! Page Not Found',
          'content' => 'This page cannot be found, please try again.'
        ];
    }
    return $data;
  }
}
