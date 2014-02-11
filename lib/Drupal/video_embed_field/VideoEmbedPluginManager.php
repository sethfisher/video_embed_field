<?php

namespace Drupal\video_embed_field;

use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Plugin type manager for video embed provider.
 */
class VideoEmbedPluginManager extends DefaultPluginManager {

  /**
   * Constructs a FormatterPluginManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Language\LanguageManager $language_manager
   *   The language manager.
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $field_type_manager
   *   The 'field type' plugin manager.
   */
  public function __construct(\Traversable $namespaces) {

    parent::__construct('Plugin/VideoEmbed/Provider', $namespaces);

  }

}