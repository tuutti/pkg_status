<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\PackageSource\Composer;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\pkg_status\Entity\Package\PackageInterface;

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
  public function label() : TranslatableMarkup {
    return new TranslatableMarkup('Packagist (custom)');
  }

  /**
   * {@inheritdoc}
   */
  protected function getUrls(PackageInterface $package): array {
    /** @var \Drupal\pkg_status\Entity\Package\ComposerPackage $package */
    return [
      sprintf('%s/p2/%s.json', $package->getSource(), $package->getName()),
      sprintf('%s/p2/%s~dev.json', $package->getSource(), $package->getName()),
    ];
  }

}
