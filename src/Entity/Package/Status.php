<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\Entity\Package;

use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Enum to define package statuses.
 */
enum Status : int {

  case LATEST = 0;
  case OUT_OF_DATE = 2;
  case UNSUPPORTED = 3;
  case INSECURE = 4;

  /**
   * Gets the label.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   The label.
   */
  public function label() : TranslatableMarkup {
    return match($this) {
      self::LATEST => new TranslatableMarkup('Latest'),
      self::OUT_OF_DATE => new TranslatableMarkup('Out of date'),
      self::INSECURE => new TranslatableMarkup('Insecure'),
      self::UNSUPPORTED => new TranslatableMarkup('Unsupported'),
    };
  }

}
