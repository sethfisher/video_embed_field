<?php

namespace Drupal\video_embed_field\Form;

use Drupal\Core\Entity\EntityFormController;
use Drupal\Core\Entity\EntityStorageControllerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class VideoEmbedStyleController extends EntityFormController {

  /**
   * The image style entity storage controller.
   *
   * @var \Drupal\Core\Entity\EntityStorageControllerInterface
   */
  protected $videoEmbedStyleStorage;

  /**
   * Constructs a base class for image style add and edit forms.
   *
   * @param \Drupal\Core\Entity\EntityStorageControllerInterface $video_embed_style_storage
   *   The image style entity storage controller.
   */
  public function __construct(EntityStorageControllerInterface $video_embed_style_storage) {
    $this->videoEmbedStyleStorage = $video_embed_style_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager')->getStorageController('video_embed_style')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, array &$form_state) {
    $entity = $this->entity;

    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => t('Label'),
      '#maxlength' => 100,
      '#default_value' => $entity->label(),
    );

    $form['name'] = array(
      '#type' => 'machine_name',
      '#description' => t('A unique machine-readable name. Can only contain lowercase letters, numbers, and underscores.'),
      '#disabled' => !$entity->isNew(),
      '#default_value' => $entity->id(),
      '#machine_name' => array(
        'exists' => array($this->videoEmbedStyleStorage, 'load'),
        'replace_pattern' => '[^a-z0-9_.]+',
      ),
    );

    $form['advanced'] = array(
      '#type' => 'vertical_tabs',
      '#weight' => 99,
    );

    $form['providers'] = array(
      '#type' => 'vertical_tabs',
      '#weight' => 99,
      '#tree' => TRUE,
    );

    $providers = \Drupal::service('plugin.manager.video_embed_field.provider')->getDefinitions();

    foreach ($providers as $name => $definition) {
      $element = isset($entity->providers[$name]) ? $entity->providers[$name] : $definition['settings'];
      $form['providers'][$name] = array(
        '#type' => 'details',
        '#title' => $definition['label'],
        '#group' => 'advanced',
      );
      $class = $definition['class'];
      $form['providers'][$name] += $class::formElement($element, $form_state);
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validate(array $form, array &$form_state) {
    parent::validate($form, $form_state);
    $providers = \Drupal::service('plugin.manager.video_embed_field.provider')->getDefinitions();
    foreach ($providers as $name => $definition) {
      $element = isset($this->entity->providers[$name]) ? $this->entity->providers[$name] : $definition['settings'];
      $class = $definition['class'];
      $class::validateElement($form['providers'][$name], $form_state);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, array &$form_state) {
    $this->entity->save();
    $form_state['redirect_route'] = array(
      'route_name' =>'video_embed.style_list'
    );
  }
}