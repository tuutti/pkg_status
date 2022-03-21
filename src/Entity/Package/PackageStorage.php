<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\Entity\Package;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * The package storage class.
 */
final class PackageStorage extends SqlContentEntityStorage {

  /**
   * {@inheritdoc}
   */
  public function getEntityClass(?string $bundle = NULL): string {
    return match ($bundle) {
      'composer' => ComposerPackage::class,
      default => parent::getEntityClass($bundle),
    };
  }

}
