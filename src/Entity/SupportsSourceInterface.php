<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\Entity;

/**
 * Interface to indicate package supports custom sources.
 */
interface SupportsSourceInterface {

  /**
   * Checks if package has custom source.
   *
   * @return bool
   *   TRUE if package has a source.
   */
  public function hasSource() : bool;

  /**
   * Gets the source.
   *
   * @return string
   *   The source.
   */
  public function getSource() : string;

}
