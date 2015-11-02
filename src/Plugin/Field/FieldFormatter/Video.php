<?php

/**
 * @file
 * Contains \Drupal\video_embed_field\Plugin\Field\FieldFormatter\Thumbnail.
 */

namespace Drupal\video_embed_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\video_embed_field\ProviderPluginInterface;

/**
 * Plugin implementation of the video field formatter.
 *
 * @FieldFormatter(
 *   id = "video_embed_field_video",
 *   label = @Translation("Video"),
 *   field_types = {
 *     "video_embed_field"
 *   }
 * )
 */
class Video extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    $provider_manager = \Drupal::service('video_embed_field.provider_manager');
    $settings = $this->getSettings();
    foreach ($items as $delta => $item) {
      $provider = $provider_manager->loadProviderFromInput($item->value);
      $element[$delta] = $provider->renderEmbedCode($settings['width'], $settings['height'], $settings['autoplay']);
    }
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'width' => '854',
      'height' => '480',
      'autoplay' => TRUE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form['autoplay'] = [
      '#title' => t('Autoplay'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('autoplay'),
    ];
    $form['width'] = [
      '#title' => t('Width'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('width'),
      '#required' => TRUE,
    ];
    $form['height'] = [
      '#title' => t('Height'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('height'),
      '#required' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary[] = t('Embedded Video (@widthx@height@autoplay).', [
      '@width' => $this->getSetting('width'),
      '@height' => $this->getSetting('height'),
      '@autoplay' => $this->getSetting('autoplay') ? t(', autoplaying') : '' ,
    ]);
    return $summary;
  }

}
