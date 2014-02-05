<?php

/**
 * @file
 * Contains \Drupal\video_ebed_field\Plugin\Field\FieldType\VideoEmbedItem.
 */

namespace Drupal\video_embed_field\Plugin\Field\FieldType;

use Drupal\Core\Field\ConfigFieldItemBase;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'video_embed' field type.
 *
 * @FieldType(
 *   id = "video_embed",
 *   label = @Translation("Video embed"),
 *   description = @Translation("Expose a field type for embedding videos from youtube or vimeo.."),
 *   settings = {
 *     "max_length" = "255"
 *   },
 *   instance_settings = {
 *     "description_enabled" = "1"
 *   },
 *   default_widget = "video_embed",
 *   default_formatter = "video_embed"
 * )
 */
class VideoEmbedItem extends ConfigFieldItemBase {

  /**
   * Definitions of the contained properties.
   *
   * @var array
   */
  static $propertyDefinitions;

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions() {
    if (!isset(static::$propertyDefinitions)) {
      static::$propertyDefinitions['video_url'] = DataDefinition::create('string')
        ->setLabel(t('Video URL'));
      static::$propertyDefinitions['thumbnail_url'] = DataDefinition::create('string')
        ->setLabel(t('Thumbnail URL'));
      static::$propertyDefinitions['description'] = DataDefinition::create('string')
        ->setLabel(t('Description'));
    }
    return static::$propertyDefinitions;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldDefinitionInterface $field_definition) {
    return array(
      'columns' => array(
        'video_url' => array(
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
        ),
        'thumbnail_url' => array(
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
        ),
        'description' => array(
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function instanceSettingsForm(array $form, array &$form_state) {
    $element = array();

    $element['description_enabled'] = array(
      '#type' => 'checkbox',
      '#title' => t('Enable the description field'),
      '#default_value' => $this->getFieldSetting('description_enabled'),
      '#description' => t('Show a description field.'),
    );

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    if (empty($this->video_url)) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave() {
    // Retrieve the thumbnail.
  }

  /**
   * {@inheritdoc}
   */
  public function validate() {
    // Validate it here.
  }

}
