<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\Entity\Package;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Package interface.
 */
interface PackageInterface extends ContentEntityInterface {

  /**
   * Gets the package name.
   *
   * @return string
   *   The package name.
   */
  public function getName() : string;

}
