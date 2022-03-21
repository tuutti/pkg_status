<?php

declare(strict_types = 1);

namespace Drupal\pkg_status\Entity\Site;

use Drupal\Core\Entity\ContentEntityForm as CoreContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form handler for the Site forms.
 */
class ContentEntityForm extends CoreContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $form = parent::form($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::save($form, $form_state);

    $entityTypeId = $this->entity->getEntityTypeId();
    $this->messenger()->addStatus($this->t('%title saved.', [
      '%title' => $this->entity->label(),
    ]));

    $form_state->setRedirect('entity.' . $entityTypeId . '.canonical', [
      $entityTypeId => $this->entity->id(),
    ]);
  }

}
