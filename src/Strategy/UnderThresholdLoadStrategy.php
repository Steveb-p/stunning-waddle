<?php
declare(strict_types=1);

namespace Steveb\LoadBalancer\Strategy;

use Steveb\LoadBalancer\HostInstance;

class UnderThresholdLoadStrategy implements StrategyInterface
{
    private const THRESHOLD = 0.75;

    /**
     * @var StrategyInterface
     */
    private $selectionStrategy;

    public function __construct(?StrategyInterface $selectionStrategy = null)
    {
        $this->selectionStrategy = $selectionStrategy ?: new RoundRobinStrategy();
    }

    /**
     * @param iterable<HostInstance> $instances
     */
    public function selectHost(iterable $instances): HostInstance
    {
        $preferredInstances = [];
        $otherInstances = [];

        foreach ($instances as $instance) {
            $load = $instance->getLoad();
            if ($load <= self::THRESHOLD) {
                $preferredInstances[] = $instance;
            } else {
                $otherInstances[] = $instance;
            }
        }

        if (!$preferredInstances && !$otherInstances) {
            throw new \RuntimeException('No hosts provided to selectHost method.');
        }

        return $this->selectionStrategy->selectHost($preferredInstances ?: $otherInstances);
    }
}
