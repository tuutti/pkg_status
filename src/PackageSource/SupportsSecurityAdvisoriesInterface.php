<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\PackageSource;

use Drupal\pkg_status\Entity\Package\PackageInterface;

/**
 * Interface to scan security advisories.
 */
interface SupportsSecurityAdvisoriesInterface {

  /**
   * Checks security advisories.
   *
   * @param \Drupal\pkg_status\Entity\Package\PackageInterface $package
   *   The package data.
   * @param \Drupal\pkg_status\DTO\Version[] $versions
   *   The versions.
   *
   * @return \Drupal\pkg_status\DTO\Version[]
   *   The modified versions.
   */
  public function checkSecurityAdvisories(PackageInterface $package, array $versions) : array;

}
