<?php

class TreeBuilder
{

  private $nodes = [];

  public function __construct()
  {
    $jsonPath = __DIR__ . '/../data/skilltree.json';
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
      $projectGroups = [];
      $treeChildren = [];

      foreach (($node['children'] ?? []) as $child) {
        if (($child['class'] ?? '') === 'project-group') {
          $projectGroups[] = $child;
          continue;
        }

        $treeChildren[] = $child;
      }

      $node['children'] = $treeChildren;
      $node['project-groups'] = $projectGroups;

      $node['is-heading'] = !empty($node['is-heading']);
      $node['has-icon'] = !empty($node['icon']);
      $node['has-content'] = !empty($node['content']);
      $node['has-comment'] = !empty($node['comment']);
      $node['has-repo'] = !empty($node['repo']);
      $node['is-open'] = !empty($node['open']);
      $node['has-tree-children'] = !empty($node['children']);
      $node['has-project-groups'] = !empty($node['project-groups']);
      $node['has-list-children'] = $node['has-tree-children'] || $node['has-project-groups'];
      $node['has-children'] = $node['has-list-children'];

      if ($node['has-tree-children']) {
        $node['children'] = $this->compileTreeView($node['children']);
      }

      if ($node['has-project-groups']) {
        $node['project-groups'] = $this->compileTreeView($node['project-groups']);
      }
    }

    unset($node);

    return $nodes;
  }
}
