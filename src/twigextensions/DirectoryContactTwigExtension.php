<?php

namespace hfg\directorycontact\twigextensions;

use hfg\directorycontact\DirectoryContact;

use Craft;

class DirectoryContactTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    public function getName()
    {
        return 'Hfgvideo';
    }
    /**
     * Returns an array of Twig functions, used in Twig templates via:
     *
     *      {% set this = someFunction('something') %}
     *
    * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getPerson', [$this, 'getPerson']),
        ];
    }

    /**
     * Our function called via Twig; it can do anything you want
     *
     * @param null $text
     *
     * @return string
     */
    public function getPerson($id = null)
    {
        return DirectoryContact::$plugin->getEntry($id);
    }
}
