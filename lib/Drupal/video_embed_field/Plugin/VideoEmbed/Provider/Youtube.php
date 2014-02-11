<?php

namespace Drupal\video_embed_field\Plugin\VideoEmbed\Provider;

/**
 * Plugin implementation of the 'video_embed' field type.
 *
 * @Plugin(
 *   id = "youtube",
 *   label = @Translation("Youtube plugin"),
 *   domains = {
 *     "youtube.com",
 *     "youtu.be"
 *   },
 *   settings = {
 *     "width" = "640px",
 *     "height" = "480px",
 *     "theme" = "dark",
 *     "autoplay" = TRUE,
 *     "vq" = "large",
 *     "rel" = FALSE,
 *     "showinfo" = FALSE,
 *     "modestbranding" = FALSE,
 *     "iv_load_policy" = 1,
 *     "autohide" = 1
 *   }
 * )
 */
class Youtube extends VideoEmbedProviderBase {

  public static function formElement(array $element, array &$form_state) {
    $form['width'] = array(
      '#type' => 'textfield',
      '#size' => '5',
      '#title' => t('Player Width'),
      '#description' => t('The width of the youtube player.'),
      '#default_value' => $element['width'],
    );
    $form['height'] = array(
      '#type' => 'textfield',
      '#size' => '5',
      '#title' => t('Player Height'),
      '#description' => t('The height of the youtube player.'),
      '#default_value' => $element['height'],
    );
    $form['theme'] = array(
      '#type' => 'select',
      '#options' => array(
        'dark' => t('Dark'),
        'light' => t('Light'),
      ),
      '#title' => t('Player theme'),
      '#default_value' => $element['theme'],
    );
    $form['autoplay'] = array(
      '#type' => 'checkbox',
      '#title' => t('Autoplay'),
      '#description' => t('Play the video immediately.'),
      '#default_value' => $element['autoplay'],
    );
    $form['vq'] = array(
      '#type' => 'select',
      '#title' => t('Video quality'),
      '#options' => array(
        'small' => t('Small (240p)'),
        'medium' => t('Medium (360p)'),
        'large' => t('Large (480p)'),
        'hd720' => t('HD 720p'),
        'hd1080' => t('HD 10800p'),
      ),
      '#default_value' => $element['vq'],
      '#description' => t('Attempt to play the video in certain quality if available.'),
    );
    $form['rel'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show related videos'),
      '#description' => t('Show related videos after the video is finished playing.'),
      '#default_value' => $element['rel'],
    );
    $form['showinfo'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show info'),
      '#description' => t('Display information like the video title and rating before the video starts playing.'),
      '#default_value' => $element['showinfo'],
    );
    $form['modestbranding'] = array(
      '#type' => 'checkbox',
      '#title' => t('Hide Youtube logo'),
      '#description' => t('Hide the Youtube logo button on the player'),
      '#default_value' => $element['modestbranding'],
    );
    $form['iv_load_policy'] = array(
      '#type' => 'radios',
      '#options' => array(
        1 => t('Show video annotations.'),
        3 => t('Hide video annotations.'),
      ),
      '#title' => t('Display annotations'),
      '#description' => t('Controls the display of annotations over the video content. Only works when using the flash player.'),
      '#default_value' => $element['iv_load_policy'],
    );
    $form['autohide'] = array(
      '#type' => 'radios',
      '#options' => array(
        0 => t('The video progress bar and player controls will be visible throughout the video.'),
        1 => t('Automatically slide the video progress bar and the player controls out of view a couple of seconds after the video starts playing. They will only reappear if the user moves her mouse over the video player or presses a keyboard key.'),
        2 => t('The video progress bar will fade out but the player controls (play button, volume control, etc.) remain visible.'),
      ),
      '#title' => t('Autohide progress bar and the player controls'),
      '#description' => t('Controls the autohide behavior of the youtube player controls.'),
      '#default_value' => $element['autohide'],
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
    $output = array();

    //Grab the minutes and seconds, and just convert it down to seconds
    preg_match('/#t=((?P<min>\d+)m)?((?P<sec>\d+)s)?/', $url, $matches);

    //Give it some default data in case there is no #t=...
    $matches += array(
      "min" => 0,
      "sec" => 0,
    );
    $time = ($matches["min"] * 60) + $matches["sec"];
    $settings['start'] = $time;

    $id = static::getVideoId($url);
    if (!$id) {
      // We can't decode the URL - just return the URL as a link
      $output['#markup'] = l($url, $url);
      return $output;
    }
    // Construct the embed code
    $settings['wmode'] = 'opaque';
    $settings_str = static::getSettingsStr($settings);

    $output['#markup'] = '<iframe width="' . $settings['width'] . '" height="' . $settings['height'] . '" src="//www.youtube.com/embed/' . $id . '?' . $settings_str . '" frameborder="0" allowfullscreen></iframe>';

    return $output;
  }

/**
 * Helper function to get the youtube video's id
 * Returns false if it doesn't parse for wahtever reason
 */
  protected static function getVideoId($url) {
    // Find the ID of the video they want to play from the url
    if (stristr($url, 'http://')) {
      $url = substr($url, 7);
    }
    elseif (stristr($url, 'https://')) {
      $url = substr($url, 8);
    }

    if (stristr($url, 'playlist')) {
      //Playlists need the appended ampersand to take the options properly.
      $url = $url . '&';
      $pos = strripos($url, '?list=');
      if ($pos !== FALSE) {
        $pos2 = stripos($url, '&');
        $pos2++;
      }
      else {
        return FALSE;
      }
    }
    //Alternate playlist link
    elseif (stristr($url, 'view_play_list')) {
      $url = $url . '&';
      //All playlist ID's are prepended with PL. view_play_list links allow you to not have that, though.
      if (!stristr($url, '?p=PL')) {
        $url = substr_replace($url, 'PL', strpos($url, '?p=') + 3, 0);
      }
      //Replace the links format with the embed format
      $url = str_ireplace('play_list?p=', 'videoseries?list=', $url);
      $pos = strripos($url, 'videoseries?list=');
      if ($pos !== FALSE) {
        $pos2 = stripos($url, '&');
        $pos2++;
      }
      else {
        return FALSE;
      }
    }
    else {
      $pos = strripos($url, 'v=');
      if ($pos !== FALSE) {
        $pos += 2;
        $pos2 = stripos($url, '&', $pos);
        $pos_hash = stripos($url, '#', $pos);

        $pos2 = static::getMinIndex($pos2, $pos_hash);
      }
      else {
        $pos = strripos($url, '/');
        if ($pos !== FALSE) {
          $pos++;
          $pos2 = stripos($url, '?', $pos);
          $pos_hash = stripos($url, '#', $pos);

          $pos2 = static::getMinIndex($pos2, $pos_hash);
        }
      }
    }
    if ($pos === FALSE) {
      return FALSE;
    }
    else {
      if ($pos2 > 0) {
        $id = substr($url, $pos, $pos2 - $pos);
      }
      else {
        $id = substr($url, $pos);
      }
    }
    return $id;
  }

  /**
   * Calculate the min index for use in finding the id of a youtube video
   */
  protected static function getMinIndex($pos1, $pos2) {
    if (!$pos1) {
      return $pos2;
    }
    elseif (!$pos2) {
      return $pos1;
    }
    else {
      return min($pos1, $pos2);
    }
  }

}