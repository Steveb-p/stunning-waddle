<?php

namespace Steveb\LoadBalancer\Tests;

use PHPUnit\Framework\TestCase;
use Steveb\LoadBalancer\HostInstance;
use Steveb\LoadBalancer\LoadBalancer;
use Steveb\LoadBalancer\Repository\HostInstanceRepository;
use Steveb\LoadBalancer\RequestInterface;
use Steveb\LoadBalancer\Strategy\StrategyInterface;

class LoadBalancerTest extends TestCase
{
    public function testLoadBalancerUsesStrategyAndCallsRequestHandlerOnHost(): void
    {
        $hostInstance = $this->createMock(HostInstance::class);
        $hostInstance->expects($this->once())
            ->method('handleRequest');

        $unusedHostInstance = $this->createMock(HostInstance::class);
        $unusedHostInstance->expects($this->never())
            ->method('handleRequest');

        $repository = $this->createMock(HostInstanceRepository::class);
        $repository->expects($this->once())
            ->method('findHostInstances')
            ->willReturn([$unusedHostInstance, $hostInstance]);

        $strategy = $this->createMock(StrategyInterface::class);
        $strategy->expects($this->once())
            ->method('selectHost')
            ->willReturn($hostInstance);

        $loadBalancer = new LoadBalancer($repository, $strategy);

        $loadBalancer->handleRequest($this->createMock(RequestInterface::class));
    }
}
