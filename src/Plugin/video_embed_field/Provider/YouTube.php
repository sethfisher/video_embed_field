<?php

/**
 * @file
 * Contains \Drupal\video_embed_field\Plugin\video_embed_field\Provider\Youtube.
 */

namespace Drupal\video_embed_field\Plugin\video_embed_field\Provider;

use Drupal\video_embed_field\ProviderPluginBase;

/**
 * @VideoEmbedProvider(
 *   id = "youtube",
 *   title = @Translation("YouTube")
 * )
 */
class YouTube extends ProviderPluginBase {

  /**
   * {@inheritdoc}
   */
  public function renderEmbedCode($width, $height, $autoplay) {
    return [
      '#type' => 'html_tag',
      '#tag' => 'iframe',
      '#attributes' => [
        'width' => $width,
        'height' => $height,
        'frameborder' => '0',
        'allowfullscreen' => 'allowfullscreen',
        'src' => sprintf('https://www.youtube.com/embed/%s?autoplay=%d', $this->getId(), $autoplay),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getRemoteThumbnailUrl() {
    return sprintf('http://img.youtube.com/vi/%s/hqdefault.jpg', $this->getId());
  }

  /**
   * {@inheritdoc}
   */
  public static function getIdFromInput($input) {
    $url_components = parse_url($input);
    if (!isset($url_components['query']) || ($url_components['host'] !== 'www.youtube.com' && $url_components['host'] !== 'youtube.com')) {
      return FALSE;
    }
    parse_str($url_components['query'], $query_params);
    return isset($query_params['v']) ? $query_params['v'] : FALSE;
  }

}
