<?php

/**
 * @file
 * Contains Drupal\Tests\video_embed_field\Unit\HttpQueryStringTest.
 */

namespace Drupal\Tests\video_embed_field\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\video_embed_field\Element\HttpQueryString;

/**
 * Test the HTTP query string object.
 *
 * @group video_embed_field
 */
class HttpQueryStringTest extends UnitTestCase {

  /**
   * Data provider for testHttpQueryObject.
   */
  public function httpQueryObjectTestCases() {
    return [
      'Normal query string' => [
        [
          'cat' => 'house',
          'fish' => 'bowl',
        ],
        '?cat=house&fish=bowl',
      ],
      'Boolean value' => [
        [
          'autoplay' => TRUE,
        ],
        '?autoplay=1',
      ],
      'Empty initial input' => [
        [],
        '',
      ],
    ];
  }

  /**
   * @dataProvider httpQueryObjectTestCases
   */
  public function testHttpQueryObject($initial, $expected) {
    $this->assertEquals((string) new HttpQueryString($initial), $expected);
  }
}
