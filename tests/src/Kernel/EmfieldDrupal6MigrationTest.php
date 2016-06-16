<?php

namespace Drupal\Tests\video_embed_field\Kernel;

use Drupal\node\Entity\Node;
use Drupal\Tests\migrate_drupal\Kernel\d6\MigrateDrupal6TestBase;

/**
 * Test the Drupal 6 emfield migration.
 *
 * @group video_embed_field
 */
class EmfieldDrupal6MigrationTest extends MigrateDrupal6TestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'video_embed_field',
  ];

  /**
   * {@inheritdoc}
   */
  protected function getFixtureFilePath() {
    return __DIR__ . '/../../fixtures/drupal6-emfield-2-x.php.gz';
  }

  /**
   * Test the emfield migration.
   */
  public function testEmfieldMigration() {
    $this->migrateContent();
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
