<?php

/**
 * @file
 * Contains Drupal\video_embed_field\Tests\MockHttpClient.
 */

namespace Drupal\video_embed_field\Tests;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;

/**
 * A mock HTTP client design to not download anything.
 */
class MockHttpClient implements ClientInterface {

  /**
   * {@inheritdoc}
   */
  public function send(RequestInterface $request, array $options = []) {
    throw new \Exception("The HTTP mock can't do anything.");
  }

  /**
   * {@inheritdoc}
   */
  public function sendAsync(RequestInterface $request, array $options = []) {
    throw new \Exception("The HTTP mock can't do anything.");
  }

  /**
   * {@inheritdoc}
   */
  public function request($method, $uri, array $options = []) {
    throw new \Exception("The HTTP mock can't do anything.");
  }

  /**
   * {@inheritdoc}
   */
  public function requestAsync($method, $uri, array $options = []) {
    throw new \Exception("The HTTP mock can't do anything.");
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig($option = NULL) {
    throw new \Exception("The HTTP mock can't do anything.");
  }
}
