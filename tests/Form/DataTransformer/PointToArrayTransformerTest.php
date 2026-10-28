<?php

declare(strict_types=1);

namespace App\Tests\Form\DataTransformer;

use App\Form\DataTransformer\PointToArrayTransformer;
use LongitudeOne\Spatial\PHP\Types\Geometry\Point;
use PHPUnit\Framework\TestCase;

class PointToArrayTransformerTest extends TestCase
{
    private PointToArrayTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new PointToArrayTransformer();
    }

    public function testTransformWithPoint(): void
    {
        $point = new Point(106.8456, -6.2088); // Longitude, Latitude
        $result = $this->transformer->transform($point);

        $this->assertSame([
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ], $result);
    }

    public function testTransformWithNull(): void
    {
        $result = $this->transformer->transform(null);

        $this->assertSame([
            'latitude' => null,
            'longitude' => null,
        ], $result);
    }

    public function testReverseTransformWithValidArray(): void
    {
        $array = [
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ];

        /** @var Point $point */
        $point = $this->transformer->reverseTransform($array);

        $this->assertInstanceOf(Point::class, $point);
        $this->assertSame(-6.2088, $point->getLatitude());
        $this->assertSame(106.8456, $point->getLongitude());
    }

    public function testReverseTransformWithEmptyArray(): void
    {
        $result = $this->transformer->reverseTransform([]);

        $this->assertNull($result);
    }
}
