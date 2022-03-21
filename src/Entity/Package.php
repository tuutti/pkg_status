<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\pkg_status\DTO\Version;
use Drupal\pkg_status\Plugin\Field\FieldType\VersionItem;

/**
 * Defines the package entity class.
 *
 * @ContentEntityType(
 *   id = "pkg_status_package",
 *   label = @Translation("Package status"),
 *   label_collection = @Translation("Package statuses"),
 *   bundle_label = @Translation("Package status type"),
 *   handlers = {
 *     "access" = "Drupal\entity\EntityAccessControlHandler",
 *     "permission_provider" = "Drupal\entity\EntityPermissionProvider",
 *     "storage" = "Drupal\pkg_status\Entity\PackageStorage",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   bundle_plugin_type = "pkg_status_package_type",
 *   base_table = "pkg_status_package",
 *   admin_permission = "administer pkg_status_package",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "bundle" = "bundle",
 *   },
 *   links = {},
 *   field_ui_base_route = "entity.pkg_status_package.edit_form"
 * )
 */
class Package extends ContentEntityBase implements EntityChangedInterface {

  use EntityChangedTrait;

  /**
   * Gets the package name.
   *
   * @return string
   *   The package name.
   */
  public function getName() : string {
    return $this->get('name')->value;
  }

  /**
   * Sets the title.
   *
   * @param string $name
   *   The title.
   *
   * @return $this
   *   The self.
   */
  public function setName(string $name) : self {
    $this->set('name', $name);
    return $this;
  }

  public function setVersions(array $versions) : self {
    $this->set('versions', $versions);
    return $this;
  }

  public function addVersion(Version $version) : self {
    if (!$this->hasVersion($version)) {
      $this->get('version')->appendItem($version);
    }
    return $this;
  }

  public function removeVersion(Version $version) : self {
    $index = $this->getVersionIndex($version);
    if ($index !== FALSE) {
      $this->get('version')->offsetUnset($index);
    }
    return $this;
  }

  public function hasVersion(Version $version) : bool {
    return $this->getVersionIndex($version) !== FALSE;
  }

  private function getVersionIndex(Version $version) {
    $values = $this->get('version')->getValue();
    $ids = array_map(function ($value) {
      return $value['value'];
    }, $values);

    return array_search($version->version, $ids);
  }

  /**
   * Gets the version.
   *
   * @return \Drupal\pkg_status\DTO\Version[]
   *   The version.
   */
  public function getVersions() : array {
    return array_map(function (VersionItem $item) {
      return new Version($item->value, Status::from($item->status));
    }, $this->get('versions'));
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['version'] = BaseFieldDefinition::create('pkg_status_version')
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
      ->setLabel(new TranslatableMarkup('Version'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(new TranslatableMarkup('Changed'));

    return $fields;
  }

}
