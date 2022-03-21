<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\PackageSource\Composer;

use Psr\Http\Message\ResponseInterface;

/**
 * Enum to store packagist security advisotry logic.
 */
enum PackagistSecurityAdvisoryEnum implements PackagistEnumInterface {

  case SECURITY_ADVISORY;

  /**
   * Decodes the response data.
   *
   * @param \Psr\Http\Message\ResponseInterface $response
   *   The response.
   *
   * @return array
   *   The decoded data.
   */
  public function getResponse(ResponseInterface $response) : array {
    $data = json_decode($response->getBody()->getContents(), TRUE);

    return match ($this) {
      self::SECURITY_ADVISORY => isset($data['advisories']) ? reset($data['advisories']) : [],
    };
  }

  /**
   * {@inheritdoc}
   */
  public function url(array $package) : string {
    return match ($this) {
      self::SECURITY_ADVISORY => sprintf('https://packagist.org/api/security-advisories/?packages[]=%s', $package['name']),
    };
  }

}
