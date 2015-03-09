<?php

/**
 * @file
 * Contains \CleverReachBlockUIController.
 */

/**
 * UI controller - may be put in any include file and loaded via the code registry.
 */
class CleverReachBlockUIController extends EntityDefaultUIController {

  /**
   * {@inheritdoc}
   */
  public function hook_menu() {
    $items = parent::hook_menu();
    $items[$this->path]['title'] = 'CleverReach Newsletter';
    $items[$this->path]['description'] = 'Manage CleverReach settings and forms';

    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function overviewForm($form, &$form_state) {

    // The form provided by the parent holds the table and pager of the blocks.
    // We show them in
    $blocks = parent::overviewForm($form, $form_state);
    $form['blocks'] = array(
      '#type' => 'fieldset',
      '#title' => t('Blocks'),
      'table' => $blocks['table'],
      'pager' => $blocks['pager'],
    );

    // Attaches the group overview in a separate fieldset.
    module_load_include('inc', 'cleverreach', 'cleverreach.admin');
    $form['groups'] = array(
      '#type' => 'fieldset',
      '#title' => t('Groups'),
      'table' => cleverreach_groups_overview(),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function overviewTableHeaders($conditions, $rows, $additional_header = array()) {
    $additional_header[] = t('Group');
    $additional_header[] = t('Attributes');
    $additional_header[] = t('Active');

    return parent::overviewTableHeaders($conditions, $rows, $additional_header);
  }

  /**
   * {@inheritdoc}
   */
  protected function overviewTableRow($conditions, $id, $entity, $additional_cols = array()) {

    $additional_cols[] = check_plain($entity->getGroup()->name);
    $additional_cols[] = check_plain(implode(', ', array_keys($entity->fields)));
    $additional_cols[] = ($entity->active) ? t('Yes') : t('No');

    return parent::overviewTableRow($conditions, $id, $entity, $additional_cols);
  }
}
