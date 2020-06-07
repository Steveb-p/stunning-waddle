<?php
declare(strict_types=1);

namespace Steveb\LoadBalancer\Strategy;

use Steveb\LoadBalancer\HostInstance;

interface StrategyInterface
{
    /**
     * @param iterable<HostInstance> $instances
     */
    public function selectHost(iterable $instances): HostInstance;
}
