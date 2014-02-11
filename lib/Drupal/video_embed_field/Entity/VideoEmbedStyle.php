<?php

/**
 * @file
 * Contains \Drupal\video_embed_field\Entity\ImageStyle.
 */

namespace Drupal\video_embed_field\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityStorageControllerInterface;
use Drupal\image\ImageEffectBag;
use Drupal\image\ImageEffectInterface;
use Drupal\image\ImageStyleInterface;
use Drupal\Component\Utility\Crypt;
use Drupal\Component\Utility\Url;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Defines an image style configuration entity.
 *
 * @EntityType(
 *   id = "video_embed_style",
 *   label = @Translation("Video embed style"),
 *   controllers = {
 *     "form" = {
 *       "add" = "Drupal\video_embed_field\Form\VideoEmbedStyleController",
 *       "edit" = "Drupal\video_embed_field\Form\VideoEmbedStyleController",
 *       "delete" = "Drupal\image\Form\ImageStyleDeleteForm",
 *       "flush" = "Drupal\image\Form\ImageStyleFlushForm"
 *     },
 *     "storage" = "Drupal\Core\Config\Entity\ConfigStorageController",
 *     "list" = "Drupal\video_embed_field\VideoEmbedStyleListController",
 *   },
 *   admin_permission = "administer image styles",
 *   config_prefix = "video_embed.style",
 *   entity_keys = {
 *     "id" = "name",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "edit-form" = "video_embed.style_edit"
 *   }
 * )
 */
class VideoEmbedStyle extends ConfigEntityBase {

  /**
   * The name of the image style.
   *
   * @var string
   */
  public $name;

  /**
   * The image style label.
   *
   * @var string
   */
  public $label;

  /**
   * The UUID for this entity.
   *
   * @var string
   */
  public $uuid;

  public $providers = array();

  /**
   * {@inheritdoc}
   */
  public function id() {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name');
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

}
