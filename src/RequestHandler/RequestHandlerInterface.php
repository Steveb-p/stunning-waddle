<?php
declare(strict_types=1);

namespace Steveb\LoadBalancer\RequestHandler;

use Steveb\LoadBalancer\HostInstance;
use Steveb\LoadBalancer\RequestInterface;

interface RequestHandlerInterface
{
    public function handleRequest(HostInstance $hostInstance, RequestInterface $request): void;
}
