<?php

/**
 * DirectoryContact plugin for Craft CMS 3.x
 *
 * Displays specific contact information.
 *
 * @link      https://niklassonnenschein.de
 * @copyright Copyright (c) 2019 Niklas Sonnenschein
 */

namespace hfg\directorycontact\fields;

use hfg\directorycontact\DirectoryContact;
use hfg\directorycontact\assetbundles\contactpersonfield\ContactpersonFieldAsset;
use hfg\directorycontact\models\ContactModel;

use Craft;
use craft\base\Element;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use craft\helpers\Json;
use craft\elements\Entry;
use yii\db\Schema;
use Exception;

/**
 * @author    Niklas Sonnenschein
 * @package   DirectoryContact
 * @since     1.0.0
 */
class Contactperson extends Field
{
  // Public Properties
  // =========================================================================

  /**
   * @var string
   */
  public $person = '';
  public $someAttribute = '';
  public $source = '';
  public $dropdownOptions = '';
  public $telephone = '';
  public $email = '';
  public $columnType = 'text';

  // Static Methods
  // =========================================================================

  /**
   * @inheritdoc
   */
  public static function displayName(): string
  {
    return Craft::t('directory-contact', 'Contact person');
  }

  // Public Methods
  // =========================================================================
  /**
   * @inheritdoc
   */
  public function getContentColumnType(): string
  {
    return Schema::TYPE_STRING;
  }

  public function rules()
  {
    $rules = parent::rules();
    return $rules;
  }

  /**
   * @inheritdoc
   */
  public function normalizeValue($value, ElementInterface $element = null)
  {
    if ($value instanceof ContactModel) {
      return $value;
    }

    $attr = [];

    if (is_string($value)) {
      try {
        $decodedValue = Json::decode($value, true);
        if (is_array($decodedValue)) {
          $attr += $decodedValue;
        }
      } catch (Exception $e) {}
    } else if (is_array($value)) {
      $attr += $value;
    }

    if (!is_array($value)) {
      $value = json_decode($value, true);
    }

    return new ContactModel($attr);
  }

  /**
   * @inheritdoc
   */
  public function getSettingsHtml()
  {
    // Render the settings template
    return Craft::$app->getView()->renderTemplate(
      'directory-contact/_components/fields/Contactperson_settings',
      [
        'field' => $this,
      ]
    );
  }

  public function getInputHtml($value, ElementInterface $element = null): string
  {
    $view = Craft::$app->getView();

    $id = $view->formatInputId($this->handle);
    $namespaceId = $view->namespaceInputId($id);
    $namespaceName = $view->namespaceInputName($this->handle);

    $jsVars = Json::encode([
      'id' => $namespaceId,
      'name' => $namespaceName
    ]);

    // Asset bundle
    $view->registerAssetBundle(ContactpersonFieldAsset::class);

    // Initiate field
    $view->registerJs("new ContactPerson.Field('" . $jsVars . "')");

    $contact = null;
    $contactID = null;
    $element = [];

    if (isset($value["person"]) && $value["person"] != "") {
      $siteHandle = Craft::$app->request->getParam("site") ?? Craft::$app->request->getSegment(4) ?? "default";
      $siteId = Craft::$app->getSites()->getSiteByHandle($siteHandle)->id;

      $element[] = Craft::$app->getEntries()->getEntryById((int) $value["person"][0], (int) $siteId);
      $contact = $view->renderTemplate('directory-contact/_elements/contactDetail', [
        'contact' => $element[0],
        'id' => $id,
        'name' => $this->handle,
        'email' => (isset($value["email"]) ? $value["email"] : ""),
        'telephone' => (isset($value["telephone"]) ? $value["telephone"] : "")
      ]);

      $contactID = $value->getID();
    }

    return $view->renderTemplate(
      'directory-contact/_components/fields/Contactperson_input',
      [
        'id' => $id,
        'name' => $this->handle,
        'field' => $this,
        'source' => Craft::$app->sections->getSectionByHandle($this->source),
        'element' => $element,
        'currentPerson' => $value,
        'contact' => $contact,
        'contactID' => $contactID
      ]
    );
  }

  public static function supportedTranslationMethods(): array
  {
    return [
      self::TRANSLATION_METHOD_NONE,
      self::TRANSLATION_METHOD_SITE,
      self::TRANSLATION_METHOD_SITE_GROUP,
      self::TRANSLATION_METHOD_LANGUAGE,
      self::TRANSLATION_METHOD_CUSTOM,
    ];
  }
}
