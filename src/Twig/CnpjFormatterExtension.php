<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CnpjFormatterExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('format_cnpj', [$this, 'formatCnpj']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('format_cnpj', [$this, 'formatCnpj']),
        ];
    }

    public function formatCnpj($value)
    {
        return preg_replace('/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/', '$1.$2.$3/$4-$5', $value);
    }
}
