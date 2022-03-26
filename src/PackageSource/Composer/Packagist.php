<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\PackageSource\Composer;

use Composer\Semver\Semver;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\pkg_status\Entity\Package\PackageInterface;
use Drupal\pkg_status\Entity\Package\Status;
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
  public function label() : TranslatableMarkup {
    return new TranslatableMarkup('Packagist');
  }

  /**
   * {@inheritdoc}
   */
  protected function getUrls(PackageInterface $package): array {
    return [
      sprintf('https://repo.packagist.org/p2/%s.json', $package->getName()),
      sprintf('https://repo.packagist.org/p2/%s~dev.json', $package->getName()),
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
        sprintf('https://packagist.org/api/security-advisories/?packages[]=%s', $package->getName()),
        function (array $data) {
          return !empty($data['advisories']) ? reset($data['advisories']) : [];
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
