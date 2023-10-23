<?php

namespace Drupal\external_reset_password\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

/**
 * Configuration form definition for the salutation message.
 */
class ErpSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['external_reset_password.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'external_reset_password_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('external_reset_password.settings');

    $form['url'] = [
      '#type' => 'url',
      '#title' => $this->t('External URL'),
      '#description' => $this->t('Please, provide the external path for the destination of the user reset page.'),
      '#placeholder' => 'https://',
      '#default_value' => $config->get('url'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('external_reset_password.settings')
      ->set('url', $form_state->getValue('url'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
