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
    public $person = '';
    public $someAttribute = '';
    public $source = '';
    public $dropdownOptions = '';
    public $columnType = 'text';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('directory-contact', 'Contactperson');
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
/*
    public function normalizeValue($value, ElementInterface $element = null)
    {
      if(is_string($value)) {
        $value = JsonHelper::decodeIfJson($value);
      }
    }*/


    /**
     * @inheritdoc

    public function normalizeValue($value, ElementInterface $element = null)
    {
    /*  $view = Craft::$app->getView();
  		$templateMode = $view->getTemplateMode();
  		$view->setTemplateMode($view::TEMPLATE_MODE_SITE);

  		$variables['element'] = $element;
  		$variables['this'] = $this;

  		$options = json_decode('[' . $view->renderString($this->person, $variables) . ']', true);

  		$view->setTemplateMode($templateMode);

  		if ($this->isFresh($element) ) :
  			foreach ($options as $key => $option) :
  				if (!empty($option['default'])) :
  					$value = $option['value'];
  				endif;
  			endforeach;
  		endif;

  		return (is_null($value) ? '' : $value);
    }
    */

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

    /**
     * @inheritdoc
     *
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        // Register our asset bundle
        Craft::$app->getView()->registerAssetBundle(ContactpersonFieldAsset::class);

        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        // Variables to pass down to our field JavaScript to let it namespace properly
        $jsonVars = [
            'id' => $id,
            'name' => $this->handle,
            'namespace' => $namespacedId,
            'prefix' => Craft::$app->getView()->namespaceInputId(''),
            ];
        $jsonVars = Json::encode($jsonVars);
        Craft::$app->getView()->registerJs("$('#{$namespacedId}-field').DirectoryContactContactperson(" . $jsonVars . ");");

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'directory-contact/_components/fields/Contactperson_input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
                'id' => $id,
                'namespacedId' => $namespacedId,
            ]
        );
    }*/

    public function getInputHtml($value, ElementInterface $element = null): string
    {
      $view = Craft::$app->getView();

      $id = $view->formatInputId($this->handle);
      $namespaceId = $view->namespaceInputId($id);

      $jsVars = JsonHelper::encode([
        'id' => $namespaceId,
        'name' => $this->handle
      ]);

      return $view->renderTemplate(
        'directory-contact/_components/fields/Contactperson_input',
        [
            'id' => $id,
            'name' => $this->handle,
            'label' => "abc",
            'field' => $this,
            'source' => Craft::$app->sections->getSectionByHandle($this->source),
            'element' => [Craft::$app->getEntries()->getEntryById((int) $value[0])],
            'currentPerson' => $value
        ]
      );
    }
}
