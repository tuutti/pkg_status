<?php

declare(strict_types = 1);

namespace Drupal\pkg_status;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * PackageType plugin manager.
 */
final class PackageTypePluginManager extends DefaultPluginManager {

  /**
   * Constructs PkgPackageTypePluginManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/PackageType',
      $namespaces,
      $module_handler,
      'Drupal\pkg_status\PackageTypeInterface',
      'Drupal\pkg_status\Annotation\PackageType'
    );
    $this->alterInfo('pkg_status_package_type_info');
    $this->setCacheBackend($cache_backend, 'pkg_status_package_type_plugins');
  }

}
