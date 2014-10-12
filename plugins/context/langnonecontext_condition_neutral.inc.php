<?php
/**
 * Add whether the language is neutral or not as a Context condition.
 *
 * cf.:
 *  - https://ohthehugemanatee.org/blog/2013/12/02/custom-context-conditions/
 *  - http://www.phase2technology.com/blog/the-joy-of-extending-context/
 *  - See http://dtek.net/blog/extending-drupals-context-module-custom-condition-based-field-value
 */
class langnonecontext_condition_neutral extends context_condition {

  /**
   * Set the values, as you see (for the Radio-buttons) in the UI of Context.
   *
   * Though Radio-buttons is set as the type (see below), the returned variable
   * is the same as for Checkboxes, that is, each key in the returned array
   * is either defined or not, technically independent of what the other is,
   * though a user can not set both to be true (or false) via the UI.
   */
  function condition_values() {
    return array(
      'kTRUE'  => t('Node is Language-Neutral'),
      'kFALSE' => t('Node has a defined language'),
    );
  }

  /**
   * Set the form in the UI of Context.
   */
  function condition_form($context) {
    $form = parent::condition_form($context);
    // http://api.drupal.psu.edu/api/drupal/modules!contrib!context!plugins!context_condition.inc/function/context_condition%3A%3Acondition_form/cis7

    $form['#type'] = 'radios';
	// Modifies from the default 'checkboxes'.

    if(empty($form['#default_value'])){
      $form['#default_value'] = 'kTRUE';
      // In default, 'Node is Language-Neutral' is ticked in the UI.
    }
    else{
      $form['#default_value'] = current($form['#default_value']);
      // Makes it the scalar.
    }

    return $form;
  }

  /**
   * Condition form submit handler.
   *
   * Storing values in an array since that's what Context prefers.
   */
  function condition_form_submit($values) {
    return array_filter(array($values => $values));
    // Returns an empty array if $values is FALSE.
  }


  /**
   * Core function for the context.
   *
   * A user can set either "Language-Neutral" or not to satisfy the conditions.
   * Here returns true if the status of the node for the language matches
   * the user's request.
   * If not, nothing is done, as the Context module only checks a TRUE value.
   *
   * Note in Drupal 7 (i18n module):
   *  - if the language is neutral, ($node->tnid == 0),
   *  - if it is the source node for the language, ($node->tnid == $node->nid),
   *  - if it is one of translated nodes, $node->tnid is nId of its source.
   */
  function execute($node) {

    foreach ($this->get_contexts() as $context) {
      $values= $this->fetch_from_context($context, 'values');

      if (isset($node->language)) {
        // This condition isset() is necessary - otherwise it would cause
        // an error when called during previewing a node to be created.

        if     ($node->language == 'und' && !empty($values['kTRUE'])) {
          // Node is Language-Neutral, and the condition is set by the user
          // to match Language-Neutral.
          $this->condition_met($context);
        }
        elseif ($node->language != 'und' && !empty($values['kFALSE'])) {
          // Node is not Language-Neutral, and the condition is set by the user
          // to match non Language-Neutral, aka the node in a certain language.
          $this->condition_met($context);
        }
      }
      elseif (isset($node->tnid)) {
        // When a node is created without the translation, even if the language
        // of the node is set, tnid can still be zero.

        if     ($node->tnid == 0 && !empty($values['kTRUE'])) {
          // Node is Language-Neutral, and the condition is set by the user
          // to match Language-Neutral.
          $this->condition_met($context);
        }
        elseif ($node->tnid != 0 && !empty($values['kFALSE'])) {
          // Node is not Language-Neutral, and the condition is set by the user
          // to match non Language-Neutral, aka the node in a certain language.
          $this->condition_met($context);
        }
      }
    }	// foreach ($this->get_contexts() as $context) {
  }	// function execute($node) {
}
