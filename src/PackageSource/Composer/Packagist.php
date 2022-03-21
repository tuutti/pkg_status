<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\PackageSource\Composer;

use Composer\Semver\Semver;
use Drupal\pkg_status\Entity\ComposerPackage;
use Drupal\pkg_status\Entity\PackageInterface;
use Drupal\pkg_status\Entity\Status;
use Drupal\pkg_status\PackageSource\SupportsSecurityAdvisoriesInterface;

/**
 * The packagist package source.
 */
final class Packagist extends PackagistBase implements SupportsSecurityAdvisoriesInterface {

  /**
   * {@inheritdoc}
   */
  public function id() : string {
    return 'packagist';
  }

  /**
   * {@inheritdoc}
   */
  public function applies(PackageInterface $package): bool {
    if (!$package instanceof ComposerPackage) {
      return FALSE;
    }
    if (str_starts_with($package->getNotificationUrl(), 'https://packagist.org')) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  protected function getUrls(PackageInterface $package): array {
    return [
      sprintf('https://repo.packagist.org/p2/%s.json', $package['name']),
      sprintf('https://repo.packagist.org/p2/%s~dev.json', $package['name']),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function checkSecurityAdvisories(
    PackageInterface $package,
    array $versions
  ): array {
    $advisories = $this
      ->getHttpResponse(
        sprintf('https://packagist.org/api/security-advisories/?packages[]=%s', $package['name']),
        function (array $data) {
          return isset($data['advisories']) ? reset($data['advisories']) : [];
        });

    foreach ($versions as $version) {
      foreach ($advisories as $advisory) {
        // Check if package is insecure.
        if (Semver::satisfies($version->version, $advisory['affectedVersions'])) {
          $version->status = Status::INSECURE;
          break;
        }
      }
    }
    return $versions;
  }

}
