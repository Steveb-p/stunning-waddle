<?php
declare(strict_types=1);

namespace Steveb\LoadBalancer;

interface HostInstance
{
    public function getLoad(): float;
    public function handleRequest(RequestInterface $request): void;
}
