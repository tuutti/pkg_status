<?php

declare(strict_types=1);

namespace Drupal\pkg_status\Entity\Site;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\user\EntityOwnerInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the pkg_status_site entity class.
 *
 * @ContentEntityType(
 *   id = "pkg_status_site",
 *   label = @Translation("Site"),
 *   label_collection = @Translation("Site"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\pkg_status\Entity\Site\ListBuilder",
 *     "access" = "Drupal\entity\EntityAccessControlHandler",
 *     "permission_provider" = "Drupal\entity\EntityPermissionProvider",
 *     "form" = {
 *       "default" = "Drupal\pkg_status\Entity\Site\ContentEntityForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *     "local_action_provider" = {
 *       "collection" = "\Drupal\entity\Menu\EntityCollectionLocalActionProvider",
 *     },
 *     "local_task_provider" = {
 *       "default" = "\Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *   },
 *   base_table = "pkg_status_site",
 *   data_table = "pkg_status_site_field_data",
 *   admin_permission = "administer pkg_status_site",
 *   entity_keys = {
 *     "id" = "id",
 *     "langcode" = "langcode",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/site/{pkg_status_site}",
 *     "edit-form" = "/site/{pkg_status_site}/edit",
 *     "delete-form" = "/site/{pkg_status_site}/delete",
 *     "collection" = "/sites",
 *   },
 *   field_ui_base_route = "entity.pkg_status_package.edit_form"
 * )
 */
final class Site extends ContentEntityBase implements EntityOwnerInterface {

  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) : array {
    $fields = parent::baseFieldDefinitions($entity_type);
    $fields += self::ownerBaseFieldDefinitions($entity_type);

    $fields['package_endpoint'] = BaseFieldDefinition::create('link')
      ->setCardinality(1)
      ->setLabel(new TranslatableMarkup('Package endpoint'))
      ->setDescription(new TranslatableMarkup('The API endpoint of your package data'))
      ->setSettings([
        'max_length' => 512,
      ]);

    $fields['packages'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(new TranslatableMarkup('Packages'))
      ->setSettings([
        'target_type' => 'pkg_status_package',
      ])
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['status'] = BaseFieldDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Site status'))
      ->setRequired(TRUE);

    return $fields;
  }

}
