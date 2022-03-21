<?php

namespace Drupal\pkg_status;

use Drupal\entity\BundlePlugin\BundlePluginInterface;

/**
 * Interface for pkg_package_type plugins.
 */
interface PackageTypeInterface extends BundlePluginInterface {

  /**
   * Returns the translated plugin label.
   *
   * @return string
   *   The translated title.
   */
  public function label();

}
