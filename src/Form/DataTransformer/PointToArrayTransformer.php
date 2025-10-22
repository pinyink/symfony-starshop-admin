<?php

namespace App\Form\DataTransformer;

use LongitudeOne\Spatial\PHP\Types\Geometry\Point;
use Symfony\Component\Form\DataTransformerInterface;

class PointToArrayTransformer implements DataTransformerInterface
{
    public function transform($point): mixed
    {
        if (!$point instanceof Point) {
            return ['latitude' => null, 'longitude' => null];
        }

        return [
            'latitude' => $point->getLatitude(),
            'longitude' => $point->getLongitude(),
        ];
    }

    public function reverseTransform($array): mixed
    {
        if (empty($array['latitude']) || empty($array['longitude'])) {
            return null;
        }

        return new Point($array['longitude'], $array['latitude']);
    }
}