<?php

declare(strict_types = 1);

namespace Drupal\pkg_status;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Base class for plugins.
 */
abstract class PackageTypePluginBase extends PluginBase implements PackageTypeInterface {

  /**
   * {@inheritdoc}
   */
  public function label() : TranslatableMarkup {
    return $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() : array {
    return [];
  }

}
