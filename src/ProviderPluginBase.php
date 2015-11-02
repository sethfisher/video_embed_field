<?php

/**
 * @file
 * Contains Drupal\video_embed_field\ProviderPluginBase
 */

namespace Drupal\video_embed_field;
use Drupal\image\Entity\ImageStyle;

/**
 * A base for the provider plugins.
 */
abstract class ProviderPluginBase implements ProviderPluginInterface {

  /**
   * The directory where thumbnails are stored.
   *
   * @var string
   */
  protected $thumbsDirectory = 'public://video_thumbnails';

  /**
   * The ID of the video.
   *
   * @var string
   */
  protected $videoId;

  /**
   * Create a plugin with the given input.
   *
   * @param string $configuration
   *   The configuration of the plugin.
   *
   * @throws \Exception
   */
  public function __construct($configuration) {
    if (!static::isApplicable($configuration['input'])) {
      throw new \Exception('Tried to create a video provider plugin with invalid input.');
    }
    $this->videoId = $this->getIdFromInput($configuration['input']);
  }

  /**
   * Get the ID of the video.
   *
   * @return string
   *   The video ID.
   */
  protected function getId() {
    return $this->videoId;
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable($input) {
    $id = static::getIdFromInput($input);
    return !empty($id);
  }

  /**
   * {@inheritdoc}
   */
  public function renderThumbnail($image_style) {
    $local_uri = $this->getLocalThumbnailUri();
    if (!file_exists($local_uri)) {
      file_prepare_directory($this->thumbsDirectory, FILE_CREATE_DIRECTORY);
      copy($this->getRemoteThumbnailUrl(), $local_uri);
    }
    return [
      '#theme' => 'image',
      '#uri' => !empty($image_style) ? ImageStyle::load($image_style)->buildUrl($local_uri) : $local_uri,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getLocalThumbnailUri() {
    return $this->thumbsDirectory . '/' . $this->getId() . '.jpg';
  }

}
