<?php
/**
 * Expose Web Area Contact Form as a Context condition.
 *
 * cf.:
 *  - https://ohthehugemanatee.org/blog/2013/12/02/custom-context-conditions/
 *  - http://www.phase2technology.com/blog/the-joy-of-extending-context/
 */
class langnonecontext_condition_neutral extends context_condition {
  function condition_values() {
    return array(
      'TRUE' => t('Node is Language-Neutral'),
      'FALSE' => t('Node has a defined language'),
    );
  }

  function condition_form($context) {
    $form = parent::condition_form($context);
    // http://api.drupal.psu.edu/api/drupal/modules!contrib!context!plugins!context_condition.inc/function/context_condition%3A%3Acondition_form/cis7

    $form['#type'] = 'radios';	// 'checkboxes' in default.
    if(empty($form['#default_value'])){
      $form['#default_value'] = 'TRUE';
    }
    else{
      $form['#default_value'] = current($form['#default_value']);
      // Makes it the scalar.
    }

    // unset($form['#options']);
    // // http://www.phase2technology.com/blog/the-joy-of-extending-context/
    // // sets this for whatever reason...  For '#options' see:
    // // http://api.drupal.psu.edu/api/drupal/modules!contrib!context!plugins!context_condition.inc/function/context_condition%3A%3Acondition_form/cis7

    return $form;
  }

  /**
   * Condition form submit handler.
   *
   * Storing values in an array since that's what Context prefers
   */
  function condition_form_submit($values) {
    return array_filter(array($values => $values));
    // Returns an empty array if $values is FALSE.
  }


  function execute($node) {
  // function execute($value = NULL) {
    // // If nothing is passed then use now
    // if (empty($value)) {
    //   $value = time();
    // }
  // function execute($group) {
    // if (!empty($group) && $group['group_type'] == 'node') {
    //   $group_node = entity_load_single($group['group_type'], $group['gid']);
    //   $group_wrapper = entity_metadata_wrapper('node', $group_node);
    //   $special_nid = $group_wrapper->field_special_node->value();

    foreach ($this->get_contexts() as $context) {
      $values= $this->fetch_from_context($context, 'values');

      if ($node->tnid == 0 && !empty($values['TRUE'])) {
        // Node is Language-Neutral, and the condition is set to match
        // Language-Neutral.
        $this->condition_met($context);
      }
      if ($node->tnid != 0 && !empty($values['FALSE'])) {
        // Node is not Language-Neutral, and the condition is set to match
        // non Language-Neutral, aka the node in a certain language.
        $this->condition_met($context);
      }

      // Drupal 7 function arg():
      //  https://api.drupal.org/api/drupal/includes!bootstrap.inc/function/arg/7
      // When the current path is /admin/structure/types
      //   arg(0) => 'admin'
      //   arg(1) => 'structure'
      //   arg(2) => 'types'
    }
    // }
  }
}
