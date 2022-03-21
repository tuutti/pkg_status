<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\Plugin\PackageType;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\entity\BundleFieldDefinition;
use Drupal\pkg_status\Entity\PackageInterface;
use Drupal\pkg_status\PackageSource\Composer\Collector;
use Drupal\pkg_status\PackageTypePluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the pkg_package_type.
 *
 * @PackageType(
 *   id = "composer",
 *   label = @Translation("Composer"),
 * )
 */
class Composer extends PackageTypePluginBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs a new instance.
   *
   * @param array $configuration
   *   The configuration.
   * @param string $plugin_id
   *   The plugin id.
   * @param array $plugin_definition
   *   The plugin definition.
   * @param \Drupal\pkg_status\PackageSource\Composer\Collector $collector
   *   The composer collector.
   */
  public function __construct(
    array $configuration,
    string $plugin_id,
    array $plugin_definition,
    private Collector $collector
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) : static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('pkg_status.package_source.composer.collector')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() : array {
    $fields['source'] = BundleFieldDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Source'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 512,
      ]);
    $fields['notification_url'] = BundleFieldDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Notification url'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 512,
      ]);
    return $fields;
  }

  public function getVersions(PackageInterface $package) : array {
    return $this->collector->get($package);
  }

}
