<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\DTO;

use Drupal\pkg_status\Entity\Status;

/**
 * A data transfer object for version data.
 */
final class Version {

  /**
   * Constructs a new instance.
   *
   * @param string $version
   *   The version.
   * @param \Drupal\pkg_status\Entity\Status $status
   *   The status.
   */
  public function __construct(
    public string $version,
    public Status $status
  ) {
  }

}
