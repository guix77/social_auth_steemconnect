<?php

namespace Drupal\social_auth_steemconnect\Settings;

/**
 * Defines an interface for Social Auth Steemconnect settings.
 */
interface SteemconnectAuthSettingsInterface {

  /**
   * Gets the client ID.
   *
   * @return string
   *   The client ID.
   */
  public function getClientId();

  /**
   * Gets the client secret.
   *
   * @return string
   *   The client secret.
   */
  public function getClientSecret();

}
