<?php

/**
 * @file
 * Hooks provided by video_embed_field.
 */

/**
 * Preprocess video iframes.
 *
 * For video providers that use the "video_embed_iframe" element, you can
 * preprocess the element to access the individual components which make up the
 * iframe including:
 *  - url: The URL of the iframe, excluding the query parameters.
 *  - query: Individually manipulatable query string parameters.
 *  - attributes: The attributes on the iframe HTML element.
 *  - provider: The provider which has rendered the iframe, available for
 *    conditional logic only, should not be changed.
 */
function hook_preprocess_video_embed_iframe(&$variables) {
  // Add a class to all iframes that point to vimeo.
  if ($variables['provider'] == 'vimeo') {
    $variables['attributes']['class'][] = 'vimeo-embed';
  }
}

/**
 * Preprocess iframes in the format of preprocess_video_embed_iframe__PROVIDER.
 *
 * Allows you to preprocess video embed iframes but only for specific providers.
 * This allows you to, for instance control things specific to each provider.
 * For example, if you wanted to enable a specific youtube feature by altering
 * the query string, you could do so as demonstrated.
 */
function hook_preprocess_video_embed_iframe__youtube(&$variables) {
  // Remove the YouTube logo from youtube embeds.
  $variables['query']['modestbranding'] = '1';
}
