<?php

namespace Steveb\LoadBalancer\Tests;

use PHPUnit\Framework\TestCase;
use Steveb\LoadBalancer\HostInstance;
use Steveb\LoadBalancer\LoadBalancer;
use Steveb\LoadBalancer\Repository\HostInstanceRepository;
use Steveb\LoadBalancer\RequestHandler\RequestHandlerInterface;
use Steveb\LoadBalancer\RequestInterface;
use Steveb\LoadBalancer\Strategy\StrategyInterface;

class LoadBalancerTest extends TestCase
{
    public function testLoadBalancerUsesStrategyAndCallsRequestHandlerOnHost(): void
    {
        $hostInstance = $this->createMock(HostInstance::class);
        $unusedHostInstance = $this->createMock(HostInstance::class);

        $repository = $this->createMock(HostInstanceRepository::class);
        $repository->expects($this->once())
            ->method('findHostInstances')
            ->willReturn([$unusedHostInstance, $hostInstance]);

        $strategy = $this->createMock(StrategyInterface::class);
        $strategy->expects($this->once())
            ->method('selectHost')
            ->willReturn($hostInstance);

        $requestHandler = $this->createMock(RequestHandlerInterface::class);
        $requestHandler->expects($this->once())
            ->method('handleRequest')
            ->with($hostInstance);

        $loadBalancer = new LoadBalancer($repository, $strategy, $requestHandler);

        $loadBalancer->handleRequest($this->createMock(RequestInterface::class));
    }
}
