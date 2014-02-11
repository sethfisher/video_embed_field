<?php


namespace Drupal\video_embed_field\Plugin\VideoEmbed\Provider;

/**
 * Plugin implementation of the 'video_embed' field type.
 *
 * @Plugin(
 *   id = "vimeo",
 *   label = @Translation("Vimeo plugin"),
 *   domains = {
 *     "vimeo.com"
 *   },
 *   settings = {
 *     "width" = "640px",
 *     "height" = "360px",
 *     "color" = "00adef",
 *     "portrait" = 1,
 *     "title" = 1,
 *     "byline" = 1,
 *    "autoplay" = 0,
 *    "loop" = 0,
 *   }
 * )
 */
class Vimeo extends VideoEmbedProviderBase {

  public static function formElement( array $element, array &$form_state) {
    $form['width'] = array(
      '#type' => 'textfield',
      '#size' => '5',
      '#title' => t('Player Width'),
      '#description' => t('The width of the vimeo player.'),
      '#default_value' => $element['width'],
    );
    $form['height'] = array(
      '#type' => 'textfield',
      '#size' => '5',
      '#title' => t('Player Height'),
      '#description' => t('The height of the vimeo player.'),
      '#default_value' => $element['height'],
    );
    $form['color'] = array(
      '#type' => 'select',
      '#options' => array(
        '00adef' => t('Blue'),
        'ff9933' => t('Orange'),
        'c9ff23' => t('Lime'),
        'ff0179' => t('Fuschia'),
        'ffffff' => t('White'),
      ),
      '#title' => t('Player Color'),
      '#description' => t('The color to use on the vimeo player.'),
      '#default_value' => $element['color'],
    );
    $form['portrait'] = array(
      '#type' => 'checkbox',
      '#title' => t('Overlay Author Thumbnail'),
      '#description' => t('Overlay the author\'s thumbnail before the video is played.'),
      '#default_value' => $element['portrait'],
    );
    $form['title'] = array(
      '#type' => 'checkbox',
      '#title' => t('Overlay Video\'s Title'),
      '#description' => t('Overlay the video\'s title before the video is played.'),
      '#default_value' => $element['title'],
    );
    $form['byline'] = array(
      '#type' => 'checkbox',
      '#title' => t('Overlay Video\'s Byline'),
      '#description' => t('Overlay the video\'s description before the video is played.'),
      '#default_value' => $element['byline'],
    );
    $form['overridable'] = array(
      '#prefix' => '<p class="note"><strong>' . t('Note') . ': </strong><em>',
      '#markup' => t('Color, portrait, title and byline can be restricted by Vimeo Plus videos.
      Such videos will ignore these settings.'),
      '#suffix' => '</em></p>',
    );
    $form['autoplay'] = array(
      '#type' => 'checkbox',
      '#title' => t('Autoplay'),
      '#description' => t('Play the video immediately.'),
      '#default_value' => $element['autoplay'],
    );
    $form['loop'] = array(
      '#type' => 'checkbox',
      '#title' => t('Loop'),
      '#description' => t('Loop the video\'s playback'),
      '#default_value' => $element['loop'],
    );
    return $form;
  }

  public static function validateElement(array $element, array &$form_state) {
    $values = array('width', 'height');
    foreach ($values as $value) {
      if (!preg_match('/(\d*)(px|%)/', $element[$value]['#value'])) {
        \Drupal::formBuilder()->setError($element[$value], $form_state, t(' You should use a valid CSS value for @value, like 640px or 80%', array('@value' => $value)));
      }
    }
  }

  public static function formatVideo($url, $settings) {
    // Get ID of video from URL
    $id = static::getVideoId($url);
    if (!$id) {
      return array(
        '#markup' => l($url, $url),
      );
    }

    // Construct the embed code
    $settings['portrait'] = 0;
    $settings_str = static::getSettingsStr($settings);

    return array(
      '#markup' => '<iframe width="' . $settings['width'] . '" height="' . $settings['height'] . '" src="//player.vimeo.com/video/' . $id .
        '?' . $settings_str . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe>',
    );
  }

  /**
   * Helper function to get the Vimeo video's ID
   *
   * @param string $url
   *   A Vimeo video URL to get the ID of
   *
   * @return integer|false $id
   *   The video ID, or FALSE if unable to get the video ID
   */
  protected static function getVideoId($url) {
    $pos = strripos($url, '/');
    if ($pos != FALSE) {
      $pos += 1;
      return (int) substr($url, $pos);
    }
    return FALSE;
  }

}
