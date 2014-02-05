<?php

/**
 * @file
 * Contains \Drupal\video_embed_field\Plugin\field\formatter\TextDefaultFormatter.
 */

namespace Drupal\video_embed_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'video_embed' formatter.
 *
 * @FieldFormatter(
 *   id = "video_embed",
 *   label = @Translation("Default"),
 *   field_types = {
 *     "video_embed",
 *   },
 * )
 */
class VideoEmbedFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items) {
    $elements = array();

    foreach ($items as $delta => $item) {
      $markup = $item->video_url;
      if ($this->getFieldSetting('description_enabled')) {
        $markup .= ' - ' . $item->description;
      }
      $elements[$delta] = array('#markup' => $markup);
    }

    return $elements;
  }

}
