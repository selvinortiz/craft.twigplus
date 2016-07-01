<?php
namespace Craft;

class TwigPlusTwigExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'Twig Plus Extension';
    }

    public function getTokenParsers()
    {
        return [
            new TwigPlus_SetTokenParser(),
        ];
    }
}
