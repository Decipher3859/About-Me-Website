<?php

class TreeBuilder
{

  private $nodes = [];

  public function __construct()
  {
    $jsonPath = __DIR__ . '/../data/skills.json';
    if (!is_readable($jsonPath)) {
      return;
    }

    $nodes = json_decode(file_get_contents($jsonPath), true);
    if (is_array($nodes)) {
      $this->nodes = $nodes;
    }
  }

  public function getTreeView()
  {
    if (empty($this->nodes['children']) || !is_array($this->nodes['children'])) {
      return [];
    }

    return $this->compileTreeView($this->nodes['children']);
  }

  /**
   * Fügt Felder für Mustache-Template hinzu.
   * @param array $nodes
   * @return array
   */
  public function compileTreeView(array $nodes)
  {
    foreach ($nodes as &$node) {
      $node['is-heading'] = !empty($node['is-heading']);
      $node['has-icon'] = !empty($node['icon']);
      $node['has-content'] = !empty($node['content']);
      $node['has-comment'] = !empty($node['comment']);
      $node['has-repo'] = !empty($node['repo']);
      $node['has-children'] = !empty($node['children']);

      if ($node['has-children']) {
        $node['children'] = $this->compileTreeView($node['children']);
      }
    }

    unset($node);

    return $nodes;
  }
}
