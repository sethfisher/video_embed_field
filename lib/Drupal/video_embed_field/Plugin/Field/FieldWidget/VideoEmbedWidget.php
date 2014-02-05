<?php

/**
 * @file
 * Contains \Drupal\video_embed_field\Plugin\Field\FieldWidget\VideoEmbedWidget.
 */

namespace Drupal\video_embed_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Plugin implementation of the 'video_embed' widget.
 *
 * @FieldWidget(
 *   id = "video_embed",
 *   label = @Translation("Video"),
 *   field_types = {
 *     "video_embed"
 *   },
 *   settings = {
 *     "size" = "60",
 *     "placeholder" = ""
 *   }
 * )
 */
class VideoEmbedWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, array &$form_state) {
    $element['size'] = array(
      '#type' => 'number',
      '#title' => t('Size of textfield'),
      '#default_value' => $this->getSetting('size'),
      '#required' => TRUE,
      '#min' => 1,
    );
    $element['placeholder'] = array(
      '#type' => 'textfield',
      '#title' => t('Placeholder'),
      '#default_value' => $this->getSetting('placeholder'),
      '#description' => t('Text that will be shown inside the field until a value is entered. This hint is usually a sample value or a brief description of the expected format.'),
    );
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();

    $summary[] = t('Textfield size: !size', array('!size' => $this->getSetting('size')));
    $placeholder = $this->getSetting('placeholder');
    if (!empty($placeholder)) {
      $summary[] = t('Placeholder: @placeholder', array('@placeholder' => $placeholder));
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, array &$form_state) {
    $main_widget = $element + array(
        '#type' => 'textfield',
        '#default_value' => isset($items[$delta]->video_url) ? $items[$delta]->video_url : NULL,
        '#size' => $this->getSetting('size'),
        '#placeholder' => $this->getSetting('placeholder'),
        '#maxlength' => $this->getFieldSetting('max_length'),
        '#attributes' => array('class' => array('text-full')),
      );

    /*if ($this->getFieldSetting('text_processing')) {
      $element = $main_widget;
      $element['#type'] = 'text_format';
      $element['#format'] = isset($items[$delta]->format) ? $items[$delta]->format : NULL;
      $element['#base_type'] = $main_widget['#type'];
    }
    else {
      $element['value'] = $main_widget;
    }*/
    $element['video_url'] = $main_widget;
    if ($this->getFieldSetting('description_enabled')) {
      $element['description'] = array(
        '#type' => 'textfield',
        '#title' => t('Video description'),
        '#default_value' => isset($items[$delta]->description) ? $items[$delta]->description : NULL,
        '#maxlength' => $this->getFieldSetting('max_length'),
      );
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function errorElement(array $element, ConstraintViolationInterface $violation, array $form, array &$form_state) {
    if ($violation->arrayPropertyPath == array('format') && isset($element['format']['#access']) && !$element['format']['#access']) {
      // Ignore validation errors for formats if formats may not be changed,
      // i.e. when existing formats become invalid. See filter_process_format().
      return FALSE;
    }
    return $element;
  }

}
