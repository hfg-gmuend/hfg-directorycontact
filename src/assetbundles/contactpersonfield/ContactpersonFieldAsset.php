<?php
/**
 * DirectoryContact plugin for Craft CMS 3.x
 *
 * Displays specific contact information.
 *
 * @link      https://niklassonnenschein.de
 * @copyright Copyright (c) 2019 Niklas Sonnenschein
 */

namespace hfg\directorycontact\assetbundles\contactpersonfield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Niklas Sonnenschein
 * @package   DirectoryContact
 * @since     1.0.0
 */
class ContactpersonFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@hfg/directorycontact/assetbundles/contactpersonfield/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/Contactperson.js',
        ];

        $this->css = [
            'css/Contactperson.css',
        ];

        parent::init();
    }
}
