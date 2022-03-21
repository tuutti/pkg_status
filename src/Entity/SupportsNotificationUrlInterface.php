<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\Entity;

/**
 * Interface to indicate package supports notifications.
 */
interface SupportsNotificationUrlInterface {

  /**
   * Checks if package has a notification URL.
   *
   * @return bool
   *   TRUE if package has a notification URL.
   */
  public function hasNotificationUrl() : bool;

  /**
   * Gets the notification URL.
   *
   * @return string
   *   The notification URL.
   */
  public function getNotificationUrl() : string;

}
