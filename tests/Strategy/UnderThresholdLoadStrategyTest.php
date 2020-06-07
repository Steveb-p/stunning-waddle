<?php

namespace Steveb\LoadBalancer\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use Steveb\LoadBalancer\HostInstance;
use Steveb\LoadBalancer\Strategy\UnderThresholdLoadStrategy;

class UnderThresholdLoadStrategyTest extends TestCase
{

    public function testSelectHostWorks(): void
    {
        $strategy = new UnderThresholdLoadStrategy();
        $host = $strategy->selectHost([$this->createHostInstance(0.80)]);
        $this->assertInstanceOf(HostInstance::class, $host);
    }

    public function testThrowsAnExceptionWhenNoHostsArePassed(): void
    {
        $strategy = new UnderThresholdLoadStrategy();
        $this->expectException(\RuntimeException::class);
        $strategy->selectHost([]);
    }

    private function createHostInstance(float $load): HostInstance
    {
        $mock = $this->createMock(HostInstance::class);
        $mock->method('getLoad')->willReturn($load);

        return $mock;
    }
}
