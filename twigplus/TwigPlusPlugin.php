<?php
namespace Craft;

class TwigPlusPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Twig Plus');
    }

    public function getDescription()
    {
        return Craft::t('Adds helpful stuff not included with twig');
    }

    public function getDocumentationUrl()
    {
        return 'https://github.com/selvinortiz/craft.twigplus';
    }

    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/selvinortiz/craft.twigplus/master/releases.json';
    }

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'Selvin Ortiz';
    }

    public function getDeveloperUrl()
    {
        return 'https://selvinortiz.com';
    }

    public function addTwigExtension()
    {
        // Lazy
        Craft::import('plugins.twigplus.extensions.TwigPlus_SetNode');
        Craft::import('plugins.twigplus.extensions.TwigPlus_SetTokenParser');

        // Eager
        require_once(__DIR__.'/extensions/TwigPlusTwigExtension.php');

        return new TwigPlusTwigExtension();
    }
}
