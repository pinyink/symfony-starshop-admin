<?php

declare(strict_types=1);

namespace App\Tests\Form\DataTransformer;

use App\Form\DataTransformer\RupiahTransformer;
use PHPUnit\Framework\TestCase;

class RupiahTransformerTest extends TestCase
{
    private RupiahTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new RupiahTransformer();
    }

    public function testTransform(): void
    {
        $this->assertSame('150.000', $this->transformer->transform(150000));
        $this->assertSame('1.500.000', $this->transformer->transform(1500000));
        $this->assertSame('', $this->transformer->transform(null));
        $this->assertSame('', $this->transformer->transform(0));
    }

    public function testReverseTransform(): void
    {
        $this->assertSame(150000, $this->transformer->reverseTransform('150.000'));
        $this->assertSame(1500000, $this->transformer->reverseTransform('1.500.000'));
        $this->assertSame(0, $this->transformer->reverseTransform(''));
        $this->assertSame(0, $this->transformer->reverseTransform(null));
    }
}
