<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\PackageSource\Composer;

use Drupal\pkg_status\Entity\Package\PackageInterface;

/**
 * Defines the composer package interface.
 */
interface ComposerPackageInterface {

  /**
   * Checks whether this collector is applicable.
   *
   * @param \Drupal\pkg_status\Entity\Package\PackageInterface $package
   *   The package to check.
   *
   * @return bool
   *   TRUE if this package source should be used.
   */
  public function applies(PackageInterface $package) : bool;

  /**
   * Gets the source id.
   *
   * @return string
   *   The id.
   */
  public function id() : string;

  /**
   * Gets the version data.
   *
   * @param \Drupal\pkg_status\Entity\Package\PackageInterface $package
   *   The package.
   *
   * @throws \Drupal\pkg_status\Exception\InvalidPackageException
   */
  public function get(PackageInterface $package) : array;

}
