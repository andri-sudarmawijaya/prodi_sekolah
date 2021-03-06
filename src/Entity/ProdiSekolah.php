<?php

namespace Drupal\prodi_sekolah\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Prodi sekolah entity.
 *
 * @ingroup prodi_sekolah
 *
 * @ContentEntityType(
 *   id = "prodi_sekolah",
 *   label = @Translation("Prodi sekolah"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\prodi_sekolah\ProdiSekolahListBuilder",
 *     "views_data" = "Drupal\prodi_sekolah\Entity\ProdiSekolahViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\prodi_sekolah\Form\ProdiSekolahForm",
 *       "add" = "Drupal\prodi_sekolah\Form\ProdiSekolahForm",
 *       "edit" = "Drupal\prodi_sekolah\Form\ProdiSekolahForm",
 *       "delete" = "Drupal\prodi_sekolah\Form\ProdiSekolahDeleteForm",
 *     },
 *     "access" = "Drupal\prodi_sekolah\ProdiSekolahAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\prodi_sekolah\ProdiSekolahHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "prodi_sekolah",
 *   admin_permission = "administer prodi sekolah entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/prodi_sekolah/{prodi_sekolah}",
 *     "add-form" = "/admin/content/prodi_sekolah/add",
 *     "edit-form" = "/admin/content/prodi_sekolah/{prodi_sekolah}/edit",
 *     "delete-form" = "/admin/content/prodi_sekolah/{prodi_sekolah}/delete",
 *     "collection" = "/admin/content/prodi_sekolah",
 *   },
 *   field_ui_base_route = "prodi_sekolah.settings"
 * )
 */
class ProdiSekolah extends ContentEntityBase implements ProdiSekolahInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Prodi sekolah entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'hidden',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['pilihan_sekolah_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Sekolah'))
      ->setDescription(t('The pilihan_sekolah ID of the Prodi sekolah entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'pilihan_sekolah')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'entity_reference_label',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => -5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['kompetensi_keahlian_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Kompetensi keahlian / Prodi'))
      ->setDescription(t('The kompetensi_keahlian ID of the Prodi sekolah entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'kompetensi_keahlian')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'entity_reference_label',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Program studi'))
      ->setDescription(t('The name of the Prodi sekolah entity.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Prodi sekolah is published.'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['kuota'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Kuota'))
      ->setDescription(t('The kuota of the ProdiSekolah entity'))
      ->setDisplayOptions('view', array(
          'label' => 'above',
          'type' => 'integer',
          'weight' => -2,
      ))
      ->setDisplayOptions('form', array(
          'type' => 'number',
          'weight' => -2,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
