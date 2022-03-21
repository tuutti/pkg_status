<?php

declare(strict_types = 1);

namespace Drupal\Tests\pkg_version\Kernel\PackageSource\Composer;

use Drupal\KernelTests\KernelTestBase;
use Drupal\pkg_status\Entity\Package\Package;
use Drupal\pkg_status\PackageSource\Composer\PackagistBase;

/**
 * @group pkg_status
 */
class PackagistTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'pkg_status',
    'entity',
  ];

  private ?PackagistBase $sut;

  protected function setUp() : void {
    parent::setUp();

    $this->installEntitySchema('pkg_status_package');
    $this->sut = $this->container->get('pkg_status.package_source.composer.packagist');
  }

  public function testGetPackages() : void {
    $package = Package::create([
      'bundle' => 'composer',
      'name' => 'tuutti/php-klarna-payments',
    ]);
    $packages = $this->sut->get($package);
    $x = 1;
  }

}
