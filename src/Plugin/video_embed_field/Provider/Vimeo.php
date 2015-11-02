<?php

/**
 * @file
 * Contains \Drupal\video_embed_field\Plugin\video_embed_field\Provider\Vimeo.
 */

namespace Drupal\video_embed_field\Plugin\video_embed_field\Provider;

use Drupal\video_embed_field\ProviderPluginBase;

/**
 * @VideoEmbedProvider(
 *   id = "vimeo",
 *   title = @Translation("Vimeo")
 * )
 */
class Vimeo extends ProviderPluginBase {

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
        'src' => sprintf('https://player.vimeo.com/video/%s?autoplay=%d', $this->getId(), $autoplay),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getRemoteThumbnailUrl() {
    $video_data = json_decode(file_get_contents('http://vimeo.com/api/v2/video/' . $this->getId() . '.json'));
    return $video_data[0]->thumbnail_large;
  }

  /**
   * {@inheritdoc}
   */
  public static function getIdFromInput($input) {
    $url_components = parse_url($input);
    if (isset($url_components['host']) && $url_components['host'] !== 'vimeo.com' && $url_components['host'] !== 'www.vimeo.com') {
      return FALSE;
    }
    $video_id = ltrim($url_components['path'], '/');
    return is_numeric($video_id) ? $video_id : FALSE;
  }

}
