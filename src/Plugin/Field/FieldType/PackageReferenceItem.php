<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'pkg_status_version' field type.
 *
 * @FieldType(
 *   id = "pkg_status_package_reference",
 *   label = @Translation("Package reference"),
 *   category = @Translation("Package status"),
 *   default_widget = "string_textfield",
 *   default_formatter = "string"
 * )
 */
final class PackageReferenceItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public function isEmpty() : bool {
    return !$this->get('package_id')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) : array {
    $properties['id'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Package id'))
      ->setRequired(TRUE);
    $properties['version'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Status'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) : array {
    return [
      'columns' => [
        'id' => [
          'type' => 'int',
          'size' => 'big',
        ],
        'version' => [
          'type' => 'varchar',
          'not null' => TRUE,
          'length' => 255,
        ],
      ],
      'indexes' => [
        'value' => ['id', 'version'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) : array {
    return [
      'id' => random_int(1, 500),
      'version' => '1.0.0',
    ];
  }

}
