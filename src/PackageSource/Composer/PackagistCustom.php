<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\PackageSource\Composer;

use Drupal\pkg_status\Entity\ComposerPackage;
use Drupal\pkg_status\Entity\PackageInterface;

/**
 * The custom packagist package source.
 */
final class PackagistCustom extends PackagistBase {

  /**
   * {@inheritdoc}
   */
  public function id(): string {
    return 'packagist-custom';
  }

  /**
   * {@inheritdoc}
   */
  public function applies(PackageInterface $package): bool {
    if (!$package instanceof ComposerPackage) {
      return FALSE;
    }
    if ($package->hasSource()) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  protected function getUrls(PackageInterface $package): array {
    /** @var \Drupal\pkg_status\Entity\ComposerPackage $package */
    return [
      sprintf('%s/p2/%s.json', $package->getSource(), $package->getName()),
      sprintf('%s/p2/%s~dev.json', $package->getSource(), $package->getName()),
    ];
  }

}
