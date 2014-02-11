<?php

namespace Drupal\video_embed_field\Plugin\VideoEmbed\Provider;

abstract class VideoEmbedProviderBase implements VideoEmbedProviderInterface {

  public static function validateElement(array $element, array &$form_state) { }

  public static function getSettingsStr($settings = array()) {
    $values = array();

    foreach ($settings as $name => $value) {
      if (!isset($value)) {
        $values[] = $name;
      }
      else {
        $values[] = $name . '=' . $value;
      }
    }

    return implode('&amp;', $values);
  }
}