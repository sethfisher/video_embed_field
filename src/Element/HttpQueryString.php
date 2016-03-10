<?php

/**
 * @file
 * Contains Drupal\video_embed_field\Element\HttpQueryString.
 */

namespace Drupal\video_embed_field\Element;

/**
 * A class that represents a HTTP query string.
 */
class HttpQueryString implements \ArrayAccess {

  /**
   * The internal array representation of the query string.
   *
   * @var array
   */
  protected $query;

  /**
   * HttpQueryString constructor.
   *
   * @param array $initial
   *   The initial values of the query string.
   */
  public function __construct(array $initial) {
    $this->query = $initial;
  }

  /**
   * Cast the query params to a string.
   *
   * @return string
   *   A http query string.
   */
  function __toString() {
    return ($this->query ? '?' : '') . http_build_query($this->query);
  }

  /**
   * {@inheritdoc}
   */
  public function offsetExists($offset) {
    return isset($this->query[$offset]);
  }

  /**
   * {@inheritdoc}
   */
  public function offsetGet($offset) {
    return $this->query[$offset];
  }

  /**
   * {@inheritdoc}
   */
  public function offsetSet($offset, $value) {
    $this->query[$offset] = $value;
  }

  /**
   * {@inheritdoc}
   */
  public function offsetUnset($offset) {
    unset($this->query[$offset]);
  }

}
