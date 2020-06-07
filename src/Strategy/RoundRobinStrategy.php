<?php
declare(strict_types=1);

namespace Steveb\LoadBalancer\Strategy;

use Steveb\LoadBalancer\HostInstance;

class RoundRobinStrategy implements StrategyInterface
{
    public function selectHost(iterable $instances): HostInstance
    {
        $servers = [];
        foreach ($instances as $instance) {
            $servers[] = $instance;
        }

        if (empty($servers)) {
            throw new \RuntimeException('No hosts provided to selectHost method.');
        }

        $key = array_rand($servers);

        return $servers[$key];
    }
}
