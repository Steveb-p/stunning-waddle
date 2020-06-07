<?php
declare(strict_types=1);

namespace Steveb\LoadBalancer;

use Steveb\LoadBalancer\Repository\HostInstanceRepository;
use Steveb\LoadBalancer\Strategy\StrategyInterface;

class LoadBalancer
{
    /**
     * @var StrategyInterface
     */
    private $strategy;

    /**
     * @var HostInstanceRepository
     */
    private $repository;

    public function __construct(HostInstanceRepository $repository, StrategyInterface $strategy)
    {
        $this->strategy = $strategy;
        $this->repository = $repository;
    }

    public function handleRequest(RequestInterface $request): void
    {
        $host = $this->strategy->selectHost($this->repository->findHostInstances());
        $host->handleRequest($request);
    }
}
