<?php
declare(strict_types=1);

namespace Steveb\LoadBalancer;

use Steveb\LoadBalancer\Strategy\StrategyInterface;

class LoadBalancer
{
    /**
     * @var HostInstance[]
     */
    private $servers;

    /**
     * @var StrategyInterface
     */
    private $strategy;

    /**
     * @param iterable<HostInstance> $servers
     */
    public function __construct(iterable $servers, StrategyInterface $strategy)
    {
        $this->setServerSet($servers);
        $this->strategy = $strategy;
    }

    public function handleRequest(RequestInterface $request): void
    {
        $host = $this->strategy->selectHost($this->servers);
        $host->handleRequest($request);
    }

    /**
     * @param iterable<HostInstance> $servers
     */
    public function setServerSet(iterable $servers): void
    {
        $this->servers = [];
        foreach ($servers as $server) {
            $this->addServerToSet($server);
        }
    }

    private function addServerToSet(HostInstance $server): void
    {
        $this->servers[] = $server;
    }
}
