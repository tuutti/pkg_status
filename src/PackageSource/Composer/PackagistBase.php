<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\PackageSource\Composer;

use Composer\Semver\Semver;
use Composer\Semver\VersionParser;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\pkg_status\DTO\Version;
use Drupal\pkg_status\Entity\Package\ComposerPackage;
use Drupal\pkg_status\Entity\Package\PackageInterface;
use Drupal\pkg_status\Entity\Package\Status;
use Drupal\pkg_status\Exception\InvalidPackageException;
use Drupal\pkg_status\PackageSource\SupportsSecurityAdvisoriesInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * The packagist package source base.
 */
abstract class PackagistBase implements ComposerPackageInterface {

  /**
   * Constructs a new instance.
   *
   * @param \GuzzleHttp\ClientInterface $client
   *   The HTTP client service.
   */
  public function __construct(private ClientInterface $client) {
  }

  /**
   * The package source label.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   The label.
   */
  abstract public function label() : TranslatableMarkup;

  /**
   * Gets the package urls.
   *
   * This should return two URLs, one for stable releases and one for
   * dev releases.
   *
   * @param \Drupal\pkg_status\Entity\Package\PackageInterface $package
   *   The package data.
   *
   * @return array
   *   The URLs.
   */
  abstract protected function getUrls(PackageInterface $package) : array;

  /**
   * Sends an HTTP request for given url and formats the response.
   *
   * @param string $url
   *   The url.
   * @param callable|null $callback
   *   The callback to format the data or null.
   *
   * @return array
   *   The formatted data if given callback or raw data if not.
   */
  protected function getHttpResponse(string $url, callable $callback = NULL) : array {
    try {
      $response = $this->client->request('GET', $url);
      $data = json_decode($response->getBody()->getContents(), TRUE);

      if (!$callback) {
        return $data;
      }
      return $callback($data);
    }
    catch (GuzzleException) {
    }
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function applies(PackageInterface $package): bool {
    if (
      !$package instanceof ComposerPackage
    ) {
      return FALSE;
    }
    return $package->hasSource() && $package->getSource() === $this->id();
  }

  /**
   * Gets the version keys.
   *
   * @param \Drupal\pkg_status\Entity\Package\PackageInterface $package
   *   The package.
   *
   * @return array
   *   The version keys.
   */
  protected function getVersionKeys(PackageInterface $package) : array {
    $versionKeys = [];

    foreach ($this->getUrls($package) as $url) {
      $items = $this->getHttpResponse($url, function (array $data) {
        return !empty($data['packages']) ? reset($data['packages']) : [];
      });

      foreach ($items as $item) {
        $versionKeys[] = $item['version'];
      }
    }
    return $versionKeys;
  }

  /**
   * Gets the package versions.
   *
   * @param \Drupal\pkg_status\Entity\Package\PackageInterface $package
   *   The package name.
   *
   * @return \Drupal\pkg_status\DTO\Version[]
   *   The versions.
   *
   * @throws \Drupal\pkg_status\Exception\InvalidPackageException
   */
  public function get(PackageInterface $package) : array {
    $versionKeys = $this->getVersionKeys($package);

    if (empty($versionKeys)) {
      throw new InvalidPackageException('No version data found.');
    }
    // Sort versions by newest first.
    $versionKeys = Semver::rsort($versionKeys);

    $versions = [];

    // Collect latest version of each stability and mark them as latest.
    // We'll re-check these later against the latest stable.
    $latestByType = [
      'dev' => FALSE,
      'stable' => FALSE,
      'RC' => FALSE,
      'beta' => FALSE,
      'alpha' => FALSE,
    ];
    foreach ($versionKeys as $versionNumber) {
      $version = new Version($versionNumber, Status::OUT_OF_DATE);

      foreach ($latestByType as $stability => $value) {
        if ($value !== FALSE) {
          continue;
        }
        if (VersionParser::parseStability($versionNumber) === $stability) {
          $latestByType[$stability] = $versionNumber;
          $version->status = Status::LATEST;
        }
      }

      $versions[$versionNumber] = $version;
    }

    // Mark RC, beta and alpha releases as out of date if a stable release
    // of same version exists.
    if ($latestByType['stable'] !== FALSE) {
      $versionParser = new VersionParser();
      // Create a constraint from the latest stable version, like >=6.0.3 and
      // use it to check if we should mark other latest versions as out-of-date.
      // For example: 6.0.3 stable is higher than 6.0.0-RC1 and should
      // get marked as out-of-date, but 6.1.0-alpha1 is higher than the stable
      // and should stay as the latest version.
      $constraint = sprintf('>=%s', $versionParser->normalize($latestByType['stable']));

      foreach ($latestByType as $stability => $versionNumber) {
        if (!$versionNumber || $stability === 'dev' || $stability === 'stable') {
          continue;
        }

        if (!Semver::satisfies($versionNumber, $constraint)) {
          $versions[$versionNumber]->status = Status::OUT_OF_DATE;
        }
      }
    }

    // Mark packages as insecure if possible.
    if ($this instanceof SupportsSecurityAdvisoriesInterface) {
      $versions = $this->checkSecurityAdvisories($package, $versions);
    }

    return $versions;
  }

}
