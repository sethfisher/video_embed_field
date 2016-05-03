<?php

namespace Drupal\Tests\video_embed_wysiwyg\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\video_embed_field\Functional\AdminUserTrait;
use Drupal\Tests\video_embed_field\Functional\AssertionsTrait;

/**
 * Test the format configuration form.
 *
 * @group video_embed_wysiwyg
 */
class TextFormatConfigurationTest extends BrowserTestBase {

  use AdminUserTrait;
  use AssertionsTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'video_embed_field',
    'video_embed_wysiwyg',
    'editor',
    'ckeditor',
    'field_ui',
    'node',
    'image',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->drupalLogin($this->createAdminUser());
    $this->drupalGet('admin/config/content/formats/manage/plain_text');

    // Setup the filter to have an editor.
    $this->setFieldValue('editor[editor]', 'ckeditor');
    $this->getSession()->getPage()->find('css', 'input[name="editor_configure"]')->click();
    $this->submitForm([], t('Save configuration'));
  }

  /**
   * Test both the input filter and button need to be enabled together.
   */
  public function testFormatConfiguration() {
    // Save the settings with the filter enabled, but with no button.
    $this->drupalGet('admin/config/content/formats/manage/plain_text');
    $this->submitForm([
      'filters[video_embed_wysiwyg][status]' => TRUE,
      'editor[settings][toolbar][button_groups]' => '[]',
    ], t('Save configuration'));
    $this->assertText('To embed videos, make sure you have enabled the "Video Embed WYSIWYG" filter and dragged the video icon into the WYSIWYG toolbar.');

    $this->drupalGet('admin/config/content/formats/manage/plain_text');
    $this->submitForm([
      'filters[video_embed_wysiwyg][status]' => FALSE,
      'editor[settings][toolbar][button_groups]' => '[[{"name":"Group","items":["video_embed"]}]]',
    ], t('Save configuration'));
    $this->assertText('To embed videos, make sure you have enabled the "Video Embed WYSIWYG" filter and dragged the video icon into the WYSIWYG toolbar.');

    $this->drupalGet('admin/config/content/formats/manage/plain_text');
    $this->submitForm([
      'filters[video_embed_wysiwyg][status]' => TRUE,
      'editor[settings][toolbar][button_groups]' => '[[{"name":"Group","items":["video_embed"]}]]',
    ], t('Save configuration'));
    $this->assertText('The text format Plain text has been updated.');
  }

  /**
   * Test the dialog defaults can be set and work correctly.
   */
  public function testDialogDefaultValues() {
    $this->drupalGet('admin/config/content/formats/manage/plain_text');

    // Assert all the form fields that appear on the modal, appear as
    // configurable defaults.
    $this->assertText('Autoplay');
    $this->assertText('Responsive Video');
    $this->assertText('Width');
    $this->assertText('Height');

    $this->submitForm([
      'filters[video_embed_wysiwyg][status]' => TRUE,
      'editor[settings][toolbar][button_groups]' => '[[{"name":"Group","items":["video_embed"]}]]',
      'editor[settings][plugins][video_embed][defaults][children][width]' => '123',
      'editor[settings][plugins][video_embed][defaults][children][height]' => '456',
      'editor[settings][plugins][video_embed][defaults][children][responsive]' => FALSE,
      'editor[settings][plugins][video_embed][defaults][children][autoplay]' => FALSE,
    ], t('Save configuration'));

    // Ensure the configured defaults show up on the modal window.
    $this->drupalGet('video-embed-wysiwyg/dialog/plain_text');
    $this->assertFieldByXpath('//input[@name="width"]', '123');
    $this->assertFieldByXpath('//input[@name="height"]', '456');
    $this->assertFieldByXpath('//input[@name="autoplay"]', FALSE);
    $this->assertFieldByXpath('//input[@name="responsive"]', FALSE);
  }

  /**
   * Set a field value.
   *
   * @param string $field
   *   The name of the field to set.
   * @param string $value
   *   The value to set.
   */
  protected function setFieldValue($field, $value) {
    $this->getSession()->getPage()->find('css', '[name="' . $field . '"]')->setValue($value);
  }

}
