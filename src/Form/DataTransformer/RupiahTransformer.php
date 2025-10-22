<?php

// src/Form/DataTransformer/RupiahTransformer.php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class RupiahTransformer implements DataTransformerInterface
{
    public function transform($value): mixed
    {
        return $value ? number_format($value, 0, ',', '.') : '';
    }

    public function reverseTransform($value): mixed
    {
        return (int) str_replace(['.', ','], '', $value);
    }
}
