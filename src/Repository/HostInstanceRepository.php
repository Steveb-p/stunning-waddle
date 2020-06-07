<?php
declare(strict_types=1);

namespace Steveb\LoadBalancer\Repository;

use Steveb\LoadBalancer\HostInstance;

interface HostInstanceRepository
{
    /**
     * @return iterable<HostInstance>
     */
    public function findHostInstances(): iterable;
}
