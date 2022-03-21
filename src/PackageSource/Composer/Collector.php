<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\PackageSource\Composer;

use Drupal\pkg_status\Entity\Package\PackageInterface;

/**
 * The composer version source collector.
 */
final class Collector {

  /**
   * The composer version source collectors.
   *
   * @var \Drupal\pkg_status\PackageSource\Composer\ComposerPackageInterface[]
   */
  private array $sources = [];

  public function add(ComposerPackageInterface $source) : self {
    $this->sources[] = $source;
    return $this;
  }

  public function get(PackageInterface $package) : array {
    foreach ($this->sources as $source) {
      if (!$source->applies($package)) {
        continue;
      }
      return $source->get($package);
    }
    return [];
  }

}
