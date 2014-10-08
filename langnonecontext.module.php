<?php

define('LANGNONECONTEXT_CLASS_NEUTRAL', 'langnonecontext_condition_neutral');

/**
 * Impelements hook_context_plugins().
 *
 * to declare the new condition plugin to the Context module.
 */
function langnonecontext_context_plugins() {
  $plugins = array(
    LANGNONECONTEXT_CLASS_NEUTRAL => array(
      'handler' => array(
        'path' => drupal_get_path('module', 'langnonecontext') . '/plugins/context',
        'file' => LANGNONECONTEXT_CLASS_NEUTRAL . '.inc',
        'class' => LANGNONECONTEXT_CLASS_NEUTRAL,
        'parent' => 'context_condition',
      ),
    ),
  );
  return $plugins;
}


/**
 * Implements hook_context_registry().
 *
 * to declare our plugin to the UI (of Context).
 */
function langnonecontext_context_registry() {
  $registry = array(
    'conditions' => array(
      LANGNONECONTEXT_CLASS_NEUTRAL => array(
        'title' => t('Language Neutral Node'),
        'description' => t('Set this context based on whether or not the node is Language-Neutral (LANGUAGE_NONE), ie., (tnid == 0).'),
        'plugin' => LANGNONECONTEXT_CLASS_NEUTRAL,
      ),
    ),
  );
  return $registry;
}


/**
 * Implements hook_node_view($node, $view_mode, $langcode)
 * 
 * Hook when viewing nodes.
 * https://api.drupal.org/api/drupal/modules!node!node.api.php/function/hook_node_view/7
 * possible $view_mode contents include 'full' and 'teaser' amongst others.
 * See http://dtek.net/blog/extending-drupals-context-module-custom-condition-based-field-value
 *
 * Note a different hook may be more suitable for different cases.
 * Such as, hook_context_page_condition() if needs an earlier timing.
 * See http://www.phase2technology.com/blog/the-joy-of-extending-context/
 * Or, hook_context_page_reaction() for a late timing.
 * https://ohthehugemanatee.org/blog/2013/12/02/custom-context-conditions/
 */
function langnonecontext_node_view($node) {
  $plugin = context_get_plugin('condition', LANGNONECONTEXT_CLASS_NEUTRAL);
  if ($plugin) {
    $plugin->execute($node);
  }
}

