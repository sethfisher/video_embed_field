<?php

/**
 * @file
 * Contains \Drupal\video_embed_wysiwyg\Tests\TextFormatConfigurationTest.
 */

namespace Drupal\video_embed_wysiwyg\Tests;

use Drupal\video_embed_field\Tests\WebTestBase;

/**
 * Test the format configuration form.
 *
 * @group video_embed_wysiwyg
 */
class TextFormatConfigurationTest extends WebTestBase {

  /**
   * Modules to install.
   *
   * @var array
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
   * Test the format configuration.
   */
  public function testFormatConfiguration() {
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('admin/config/content/formats/manage/plain_text');

    // Setup the filter to have an editor.
    $this->drupalPostForm(NULL, [
      'editor[editor]' => 'ckeditor',
    ], t('Save configuration'));
    $this->drupalPostAjaxForm(NULL, [], 'editor_configure');
    $this->drupalPostForm(NULL, [], t('Save configuration'));

    // Save the settings with the filter enabled, but with no button.
    $this->drupalPostForm('admin/config/content/formats/manage/plain_text', [
      'filters[video_embed_wysiwyg][status]' => TRUE,
      'editor[settings][toolbar][button_groups]' => '[]',
    ], 'Save configuration');
    $this->assertText('To embed videos, make sure you have enabled the "Video Embed WYSIWYG" filter and dragged the video icon into the WYSIWYG toolbar.');

    $this->drupalPostForm('admin/config/content/formats/manage/plain_text', [
      'filters[video_embed_wysiwyg][status]' => FALSE,
      'editor[settings][toolbar][button_groups]' => '[[{"name":"Group","items":["video_embed"]}]]',
    ], 'Save configuration');
    $this->assertText('To embed videos, make sure you have enabled the "Video Embed WYSIWYG" filter and dragged the video icon into the WYSIWYG toolbar.');

    $this->drupalPostForm('admin/config/content/formats/manage/plain_text', [
      'filters[video_embed_wysiwyg][status]' => TRUE,
      'editor[settings][toolbar][button_groups]' => '[[{"name":"Group","items":["video_embed"]}]]',
    ], 'Save configuration');
    $this->assertText('The text format Plain text has been updated.');
  }

}
