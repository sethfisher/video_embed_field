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
 *   settings = {
 *     "video_embed_style" = "default"
 *   }
 * )
 */
class VideoEmbedFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, array &$form_state) {
    $video_embed_styles = $this->getStyleOptions();
    $form['video_embed_style'] = array(
      '#type' => 'select',
      '#options' => $video_embed_styles,
      '#default_value' => $this->getSetting('video_embed_style')
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();

    $video_embed_styles = $this->getStyleOptions();
    // Unset possible 'No defined styles' option.
    unset($video_embed_styles['']);
    // Styles could be lost because of enabled/disabled modules that defines
    // their styles in code.
    $video_embed_style_setting = $this->getSetting('video_embed_style');
    if (isset($video_embed_styles[$video_embed_style_setting])) {
      $summary[] = t('Video embed style: @style', array('@style' => $video_embed_styles[$video_embed_style_setting]));
    }
    else {
      $summary[] = t('Original image');
    }

    return $summary;
  }


  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items) {
    $elements = array();
    $video_embed_style = entity_load('video_embed_style', $this->getSetting('video_embed_style'));

    foreach ($items as $delta => $item) {
      if ($provider = $this->getProvider($item->video_url)) {
        $provider_class = $provider;
        $class = $provider_class['class'];
        if (isset($video_embed_style->providers[$provider['id']])) {
          $settings = $video_embed_style->providers[$provider['id']];
        }
        else {
          $settings = $provider['settings'];
        }

        $elements[$delta] = $class::formatVideo($item->video_url, $settings);
      }
    }

    return $elements;
  }

  protected function getStyleOptions() {
    $styles = entity_load_multiple('video_embed_style');
    $options = array();
    foreach ($styles as $name => $style) {
      $options[$name] = $style->label();
    }

    if (empty($options)) {
      $options[''] = t('No defined styles');
    }
    return $options;
  }

  protected  function getProvider($url) {
    // Process video URL
    if (!stristr($url, 'http://') && !stristr($url, 'https://')) {
      $url = 'http://' . $url;
    }
    $parts = parse_url($url);
    if (!isset($parts['host'])) {
      return FALSE;
    }

    $host = $parts['host'];
    if (stripos($host, 'www.') > -1) {
      $host = substr($host, 4);
    }

    $domains = $this->getAvailableDomains();
    if (isset($domains[$host])) {
      $provider = $domains[$host];
      return \Drupal::service('plugin.manager.video_embed_field.provider')->getDefinition($provider);
    }
    else {
      return FALSE;
    }
  }

  protected function getAvailableDomains() {
    $domains = array();

    $providers = \Drupal::service('plugin.manager.video_embed_field.provider')->getDefinitions();
    foreach ($providers as $name => $handler) {
      foreach ($handler['domains'] as $domain) {
        $domains[$domain] = $name;
      }
    }

    return $domains;
  }

}
