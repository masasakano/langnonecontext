<?php
/**
 * Impelements hook_context_plugins().
 *
 * to declare our new condition plugin to Context.
 */
function langnonecontext_context_plugins() {
  $plugins = array(
    'langnonecontext_condition_neutral' => array(
      'handler' => array(
        'path' => drupal_get_path('module', 'langnonecontext') . '/plugins/context',
        'file' => 'langnonecontext_condition_neutral.inc',
        'class' => 'langnonecontext_condition_neutral',
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
      'langnonecontext_condition_neutral' => array(
        'title' => t('Language Neutral Node'),
        'description' => t('Set this context based on whether or not the node is Language-Neutral (LANGUAGE_NONE), ie., (tnid == 0).'),
        'plugin' => 'langnonecontext_condition_neutral',
      ),
    ),
  );
  return $registry;
}


/**
 * Implements hook_node_view($node, $view_mode, $langcode)
 * 
 * Hook when viewing nodes.
 * See http://dtek.net/blog/extending-drupals-context-module-custom-condition-based-field-value
 *
 * https://api.drupal.org/api/drupal/modules!node!node.api.php/function/hook_node_view/7
 * possible $view_mode contents include 'full' and 'teaser' amongst others
 *
 * A different hook may be more suitable, depending on the purpose.
 * Such as, hook_context_page_condition() if needs an earlier timing.
 * See http://www.phase2technology.com/blog/the-joy-of-extending-context/
 *
 * Or,
 * Implements hook_context_page_reaction().
 * Executes our Language Neutral Node Context Condition. 
 * Gotta run on context_page_reaction, so Views etc have a chance to
 * set/modify Group context. 
 * https://ohthehugemanatee.org/blog/2013/12/02/custom-context-conditions/
 */
function langnonecontext_node_view($node) {
  // $group = og_context();
  // // Only execute the group node context condition if there is a group node
  // // in context.
  // if ($group) {
    $plugin = context_get_plugin('condition', 'langnonecontext_condition_neutral');
    if ($plugin) {
      $plugin->execute($node);
      // $plugin->execute($group);
    }
  // }
}

