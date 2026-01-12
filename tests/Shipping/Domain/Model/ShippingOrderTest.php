<?php

declare(strict_types=1);

namespace App\Tests\Shipping\Domain\Model;

use App\Shipping\Domain\Model\ShippingId;
use App\Shipping\Domain\Model\ShippingOrder;
use App\Shipping\Domain\Model\Weight;
use PHPUnit\Framework\TestCase;

class ShippingOrderTest extends TestCase
{
    /** @test */
    public function it_should_create_a_valid_shipping_order(): void
    {
        $order = ShippingOrder::create(
            new ShippingId('756627f1-80a1-4322-9653-9031d2c6087b'),
            'Madrid, Spain',
            'Mexico City, Mexico',
            new Weight(10.5),
            'test@example.com'
        );

        $this->assertInstanceOf(ShippingOrder::class, $order);
        $this->assertEquals('test@example.com', $order->senderEmail());
    }

    /** @test */
    public function it_should_throw_exception_when_shipping_to_antarctica(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Shipping to Antarctica is not supported yet.');

        ShippingOrder::create(
            new ShippingId('756627f1-80a1-4322-9653-9031d2c6087b'),
            'Madrid, Spain',
            'Base Esperanza, Antarctica',
            new Weight(5.0),
            'test@example.com'
        );
    }

    /** @test */
    public function it_should_not_allow_negative_weights(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        
        new Weight(-1.0);
    }
}
