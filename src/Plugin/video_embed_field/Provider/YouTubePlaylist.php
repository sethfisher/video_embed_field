<?php

namespace Drupal\video_embed_field\Plugin\video_embed_field\Provider;

use Drupal\video_embed_field\ProviderPluginBase;

/**
 * A YouTube playlist video provider.
 *
 * @VideoEmbedProvider(
 *   id = "youtube_playlist",
 *   title = @Translation("YouTube Playlist")
 * )
 */
class YouTubePlaylist extends ProviderPluginBase {

  /**
   * {@inheritdoc}
   */
  public function renderEmbedCode($width, $height, $autoplay) {
    return [
      '#type' => 'video_embed_iframe',
      '#provider' => 'youtube_playlist',
      '#url' => 'https://www.youtube.com/embed/videoseries',
      '#query' => [
        'list' => $this->getVideoId(),
        'autoplay' => $autoplay,
        'start' => $this->getTimeIndex(),
        'index' => $this->getVideoPositionIndex(),
      ],
      '#attributes' => [
        'width' => $width,
        'height' => $height,
        'frameborder' => '0',
        'allowfullscreen' => 'allowfullscreen',
      ],
    ];
  }
  
  /**
   * Get the time index for when the given playlist video starts.
   *
   * @return int
   *   The time index where the playlist video should start based on the URL.
   */
  protected function getTimeIndex() {
    preg_match('/[&\?]t=(?<timeindex>\d+)/', $this->getInput(), $matches);
    return isset($matches['timeindex']) ? $matches['timeindex'] : 0;
  }
  
  /**
   * Get the position index of the video with which the given playlist should
   * start.
   *
   * @return int
   *   The position index of the video with which the playlist should start
   *   based on the URL.
   */
  protected function getVideoPositionIndex() {
    preg_match('/[&\?]index=(?<position_index>\d+)/', $this->getInput(), $matches);
    return isset($matches['position_index']) ? $matches['position_index'] : 0;
  }

  /**
   * {@inheritdoc}
   */
  public function getRemoteThumbnailUrl() {
    return sprintf('http://img.youtube.com/vi/%s/hqdefault.jpg', static::getUrlComponent($this->getInput(), 'video_id'));
  }

  /**
   * {@inheritdoc}
   */
  public static function getIdFromInput($input) {
    return static::getUrlComponent($input, 'id');
  }

  /**
   * Get a component from the URL.
   *
   * @param string $input
   *   The input URL.
   * @param string $component
   *   The component from the regex to get.
   *
   * @return string
   *   The value of the match in the regex.
   */
  protected static function getUrlComponent($input, $component) {
    preg_match('/^https?:\/\/(?:www\.)?youtube\.com\/watch\?(?=.*v=(?<video_id>[0-9A-Za-z_-]*))(?=.*list=(?<id>[A-Za-z0-9_-]*))/', $input, $matches);
    return isset($matches[$component]) ? $matches[$component] : FALSE;
  }

}
