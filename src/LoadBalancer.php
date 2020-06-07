<?php
declare(strict_types=1);

namespace Steveb\LoadBalancer;

use Steveb\LoadBalancer\Repository\HostInstanceRepository;
use Steveb\LoadBalancer\RequestHandler\RequestHandlerInterface;
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

    /**
     * @var RequestHandlerInterface
     */
    private $requestHandler;

    public function __construct(HostInstanceRepository $repository, StrategyInterface $strategy, RequestHandlerInterface $requestHandler)
    {
        $this->strategy = $strategy;
        $this->repository = $repository;
        $this->requestHandler = $requestHandler;
    }

    public function handleRequest(RequestInterface $request): void
    {
        $host = $this->strategy->selectHost($this->repository->findHostInstances());
        $this->requestHandler->handleRequest($host, $request);
    }
}
