<?php

namespace Drupal\Tests\video_embed_field\Kernel;

/**
 * Test helpers for loading entities for tests.
 */
trait EntityLoadTrait {

  /**
   * Load an entity by it's label.
   *
   * @param string $label
   *   The label of the entity to load.
   * @param string $entity_type
   *   The entity type to load.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   A loaded entity.
   */
  protected function loadEntityByLabel($label, $entity_type = 'node') {
    $label_key = \Drupal::entityTypeManager()->getDefinition($entity_type)->getKey('label');
    $entities = \Drupal::entityQuery($entity_type)->condition($label_key, $label, '=')->execute();
    return \Drupal::entityTypeManager()->getStorage($entity_type)->load(array_shift($entities));
  }

}
