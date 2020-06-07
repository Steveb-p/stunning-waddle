<?php

namespace Steveb\LoadBalancer\Tests\Strategy;

use Steveb\LoadBalancer\HostInstance;
use Steveb\LoadBalancer\Strategy\RoundRobinStrategy;
use PHPUnit\Framework\TestCase;

class RoundRobinStrategyTest extends TestCase
{

    public function testSelectHostWorks(): void
    {
        $strategy = new RoundRobinStrategy();
        $host = $strategy->selectHost([$this->createMock(HostInstance::class)]);
        $this->assertInstanceOf(HostInstance::class, $host);
    }

    public function testThrowsAnExceptionWhenNoHostsArePassed(): void
    {
        $strategy = new RoundRobinStrategy();
        $this->expectException(\RuntimeException::class);
        $strategy->selectHost([]);
    }
}
