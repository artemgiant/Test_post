<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{

        public function getFilters()
    {
        return [
            new TwigFilter('unescape', function ($value) {
                return html_entity_decode($value);
            }),
        ];
    }
}
?>