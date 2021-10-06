<?php

namespace Napopravku\LaravelAPM\Statistics\Data;

use Napopravku\LaravelAPM\Statistics\Contracts\StatisticsData;

class SummaryStatisticsData implements StatisticsData
{
    public int $executionTime;

    public int $startedAtTimestamp;

    public int $peakMemory;

    public static function create(int $executionTime, int $startedAtTimestamp, int $maxPeakMemory): self
    {
        $instance = new static();

        $instance->executionTime      = $executionTime;
        $instance->startedAtTimestamp = $startedAtTimestamp;
        $instance->peakMemory         = $maxPeakMemory;

        return $instance;
    }
}
