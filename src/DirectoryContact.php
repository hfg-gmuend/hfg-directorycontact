<?php
/**
 * DirectoryContact plugin for Craft CMS 3.x
 *
 * Displays specific contact information.
 *
 * @link      https://niklassonnenschein.de
 * @copyright Copyright (c) 2019 Niklas Sonnenschein
 */

namespace hfg\directorycontact;

use hfg\directorycontact\fields\Contactperson as ContactpersonField;
use hfg\directorycontact\models\ContactModel;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use crat\helpers\UrlHelper;
use craft\web\UrlManager;

use yii\base\Event;

/**
 * Class DirectoryContact
 *
 * @author    Niklas Sonnenschein
 * @package   DirectoryContact
 * @since     1.0.0
 *
 */
class DirectoryContact extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var DirectoryContact
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->_registerFieldTypes();
        $this->_registerCpRoutes();
        $this->_registerSiteRoutes();

        Craft::info(
            Craft::t(
                'directory-contact',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    private function _registerFieldTypes()
    {
      Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function (RegisterComponentTypesEvent $event) {
        $event->types[] = ContactpersonField::class;
      });
    }

    private function _registerCpRoutes()
    {
      Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function (RegisterUrlRulesEvent $event) {
        $rules = [
          'directory-contact/explorer' => 'directory-contact/explorer/get-contact'
        ];
        $event->rules = array_merge($event->rules, $rules);
      });
    }

    private function _registerSiteRoutes()
    {
      Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_SITE_URL_RULES, function (RegisterUrlRulesEvent $event) {
        $rules = [
          'directory-contact/explorer' => 'directory-contact/explorer'
        ];
        $event->rules = array_merge($event->rules, $rules);
      });
    }

    // Protected Methods
    // =========================================================================

    protected function createContactModel()
    {
      return new Contact();
    }

}
