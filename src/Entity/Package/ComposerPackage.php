<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\Entity\Package;

use Drupal\pkg_status\Entity\SupportsNotificationUrlInterface;
use Drupal\pkg_status\Entity\SupportsSourceInterface;

/**
 * The composer package bundle class.
 */
final class ComposerPackage extends Package implements SupportsSourceInterface, SupportsNotificationUrlInterface {

  /**
   * {@inheritdoc}
   */
  public function hasSource() : bool {
    return !$this->get('source')->isEmpty();
  }

  /**
   * {@inheritdoc}
   */
  public function getSource() : string {
    return $this->get('source')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getNotificationUrl() : string {
    return $this->get('notification_url')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function hasNotificationUrl(): bool {
    return !$this->get('notification_url')->isEmpty();
  }

}
