<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\Entity\Site;

use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Enum to define the site status.
 */
enum Status : string {

  case ACTIVE = 'active';
  case DISABLED = 'disabled';
  case MISSING_REPOSITORY_MAPPING = 'missing_repository_mapping';
  case AWAITING_SETUP = 'awaiting_setup';

  /**
   * Gets the label.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   The label.
   */
  public function label() : TranslatableMarkup {
    return match($this) {
      self::ACTIVE => new TranslatableMarkup('Active'),
      self::DISABLED => new TranslatableMarkup('Disabled'),
      self::AWAITING_SETUP => new TranslatableMarkup('Awaiting setup'),
      self::MISSING_REPOSITORY_MAPPING => new TranslatableMarkup('Package missing repository'),
    };
  }

}
