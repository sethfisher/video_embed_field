<?php

namespace Drupal\video_embed_field\Plugin\VideoEmbed\Provider;

interface VideoEmbedProviderInterface {
  public static function formElement(array $element, array &$form_state);

  public static function validateElement(array $element, array &$form_state);

  public static function formatVideo($url, $settings);

}
