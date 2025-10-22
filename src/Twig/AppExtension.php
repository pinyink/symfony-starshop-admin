<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('rupiah', [$this, 'formatRupiah']),
        ];
    }

    public function formatRupiah($value, bool $withPrefix = true): string
    {
        if ($value === null) return '-';
        $formatter = new \NumberFormatter('id_ID', \NumberFormatter::CURRENCY);
        $formatted = $formatter->formatCurrency($value, 'IDR');
        return $withPrefix ? $formatted : $formatted;
    }
}
