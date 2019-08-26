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

use Craft;
use craft\base\Element;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use craft\elements\Entry;
use yii\db\Schema;
use craft\helpers\Json as JsonHelper;

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
    public $columnType = 'mixed';

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
        return $this->columnType;
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
       if(is_string($value))
        {
            $value = JsonHelper::decodeIfJson($value);
        }
        return $value;
     }
     /**
      * Serializes the given subfield value before being stored.
      *
      * It's gonna be called in the "outer" field's `serializeValue()` method.
      *
      * @param $value
      * @param \craft\base\ElementInterface|null $element
      *
      * @return string|mixed
      *
      * @see \Vierbeuter\Craft\Field\ModuleField::serializeValue()
      * @see \craft\base\FieldInterface::serializeValue()
      */
     public function serializeValue($value, ElementInterface $element = null)
     {
        return parent::serializeValue($value, $element);
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

      $jsVars = JsonHelper::encode([
        'id' => $namespaceId,
        'name' => "fields[".$this->handle."]"
      ]);

      // Asset bundle
      $view->registerAssetBundle(ContactpersonFieldAsset::class);

      // Initiate field
      $view->registerJs("new ContactPerson.Field('".$jsVars."')");

      $contact = null;
      $element = [];

      if (array_key_exists("person", $value)) {
        $element[] = Craft::$app->getEntries()->getEntryById((int) $value["person"][0]);
        $contact = $view->renderTemplate('directory-contact/_elements/contactDetail', [
          'contact' => $element[0],
          'id' => $id,
          'name' => $this->handle,
          'email' => (isset($value["email"]) ? $value["email"]: ""),
          'telephone' => (isset($value["telephone"]) ? $value["telephone"]: "")
        ]);
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
            'contact' => $contact
        ]
      );
    }
}
