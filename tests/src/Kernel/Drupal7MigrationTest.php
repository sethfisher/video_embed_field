<?php

namespace Drupal\Tests\video_embed_field\Kernel;

use Drupal\node\Entity\Node;
use Drupal\Tests\migrate_drupal\Kernel\d7\MigrateDrupal7TestBase;

/**
 * Test the Drupal 7 to 8 video_embed_field migration.
 *
 * @group video_embed_field
 */
class Drupal7MigrationTest extends MigrateDrupal7TestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'video_embed_field',
    'comment',
    'datetime',
    'filter',
    'image',
    'link',
    'node',
    'taxonomy',
    'telephone',
    'text',
  ];

  /**
   * {@inheritdoc}
   */
  protected function getFixtureFilePath() {
    return __DIR__ . '/../../fixtures/drupal7-vef-2-x.php.gz';
  }

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installEntitySchema('node');
    $this->installEntitySchema('comment');
    $this->installConfig(static::$modules);

    $this->executeMigrations([
      'd7_user_role',
      'd7_user',
      'd7_node_type',
      'd7_comment_type',
      'd7_field',
      'd7_field_instance',
      'd7_node:page',
    ]);
  }

  /**
   * Test the emfield migration.
   */
  public function testMigration() {
    $migrated_vimeo = $this->loadNodeByTitle('Vimeo Example');
    $migrated_youtube = $this->loadNodeByTitle('YouTube Example');
    $this->assertEquals('https://vimeo.com/21681203', $migrated_vimeo->field_video->value);
    $this->assertEquals('https://www.youtube.com/watch?v=XgYu7-DQjDQ', $migrated_youtube->field_video->value);
  }

  /**
   * Load a node by it's title.
   *
   * @param string $title
   *   The title to load.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   A loaded node.
   */
  protected function loadNodeByTitle($title) {
    $nodes = \Drupal::entityQuery('node')->condition('title', $title, '=')->execute();
    return Node::load(array_shift($nodes));
  }

}
