<?php
class Templates
{
  private $m;
  private $nav;

  public function __construct()
  {
    $this->m = (new \Mustache\Engine(
      [
        'partials_loader' => new \Mustache\Loader\FilesystemLoader(__DIR__ . '/../views/partials'),
      ]
    ));
    $this->nav = new Nav();
  }

  public function render($template, $data)
  {
    $template = @file_get_contents(__DIR__ . '/../views' . $template . '.html');
    if ($template === false) {
      $template = file_get_contents(__DIR__ . '/../views/404.html');
    }
    return $this->m->render($template, $data);
  }

  /**
   * Erzeugt URLs der Unterseiten
   * @return string
   */
  public function getPageURL()
  {
    $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    $url = explode('?', $requestUri);
    return ($url[0] == '/' ? '/home' : $url[0]);
  }

  public function data($page)
  {
    $data['nav']['header'] = $this->nav->header();
    $data['nav']['footer'] = $this->nav->footer();
    $data['asset_version'] = filemtime(__DIR__ . '/../scss/style.css');
    switch ($page) {
      case '/home':
        $data['content'] = [
          'title' => 'Home',
          'heading' => 'Diese Seite befindet sich im Aufbau!',
        ];
        break;
      case '/skilltree':
        $data['content'] = [
          'title' => 'Mein Skilltree',
        ];
        break;
      case '/projects':
        $data['content'] = [
          'title' => 'Meine Projekte',
        ];
      break;
      case '/next-steps':
        $data['content'] = [
          'title' => 'Ausbildung',
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
