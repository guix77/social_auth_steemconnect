<?php

namespace Drupal\social_auth_steemconnect\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Path\PathValidatorInterface;
use Drupal\Core\Routing\RequestContext;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\social_auth\Form\SocialAuthSettingsForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Settings form for Social Auth Steemconnect.
 */
class SteemconnectAuthSettingsForm extends SocialAuthSettingsForm {

  /**
   * The request context.
   *
   * @var \Drupal\Core\Routing\RequestContext
   */
  protected $requestContext;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\Core\Routing\RouteProviderInterface $route_provider
   *   Used to check if route exists.
   * @param \Drupal\Core\Path\PathValidatorInterface $path_validator
   *   Used to check if path is valid and exists.
   * @param \Drupal\Core\Routing\RequestContext $request_context
   *   Holds information about the current request.
   */
  public function __construct(ConfigFactoryInterface $config_factory, RouteProviderInterface $route_provider, PathValidatorInterface $path_validator, RequestContext $request_context) {
    parent::__construct($config_factory, $route_provider, $path_validator);
    $this->requestContext = $request_context;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this class.
    return new static(
    // Load the services required to construct this class.
      $container->get('config.factory'),
      $container->get('router.route_provider'),
      $container->get('path.validator'),
      $container->get('router.request_context')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'social_auth_steemconnect_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return array_merge(
      parent::getEditableConfigNames(),
      ['social_auth_steemconnect.settings']
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('social_auth_steemconnect.settings');

    $form['steemconnect_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Steemconnect Client settings'),
      '#open' => TRUE,
      '#description' => $this->t('You need to first create a Steemconnect App at <a href="@steemconnect-dashboard">@steemconnect-dashboard</a>', ['@steemconnect-dashboard' => 'https://v2.steemconnect.com/dashboard']),
    ];

    $form['steemconnect_settings']['client_id'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Steemconnect App name'),
      '#default_value' => $config->get('client_id'),
      '#description' => $this->t('Copy the Steemconnect App name here.'),
    ];

    $form['steemconnect_settings']['client_secret'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Client Secret'),
      '#default_value' => $config->get('client_secret'),
      '#description' => $this->t('Copy the Client Secret here.'),
    ];

    $form['steemconnect_settings']['authorized_redirect_url'] = [
      '#type' => 'textfield',
      '#disabled' => TRUE,
      '#title' => $this->t('Authorized redirect URIs'),
      '#description' => $this->t('Copy this value to <em>Authorized redirect URIs</em> field of your Steemconnect App settings.'),
      '#default_value' => $GLOBALS['base_url'] . '/user/login/steemconnect/callback',
    ];

    $form['steemconnect_settings']['scopes'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Scopes for API call'),
      '#default_value' => $config->get('scopes'),
      '#description' => $this->t('Define the requested scopes to make API calls.'),
    ];

    $form['steemconnect_settings']['api_calls'] = [
      '#type' => 'textarea',
      '#title' => $this->t('API calls to be made to collect data'),
      '#default_value' => $config->get('api_calls'),
      '#description' => $this->t('Define the API calls which will retrieve data from provider.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('social_auth_steemconnect.settings')
      ->set('client_id', $values['client_id'])
      ->set('client_secret', $values['client_secret'])
      ->set('scopes', $values['scopes'])
      ->set('api_calls', $values['api_calls'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
