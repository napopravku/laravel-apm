<?php

namespace Napopravku\LaravelAPM\Tasks\Listeners;

use Illuminate\Queue\Events\JobProcessed;
use Napopravku\LaravelAPM\ScriptInfo\DataCreators\ScriptInfoCreator;
use Napopravku\LaravelAPM\Snapshotting\APMSnapshotCollector;
use Napopravku\LaravelAPM\Snapshotting\Events\SnapshottingFinished;
use Napopravku\LaravelAPM\Tasks\Enums\TaskTypes;

class JobTaskListener
{
    private APMSnapshotCollector $snapshotCollector;

    private ScriptInfoCreator $scriptInfoCreator;

    public function __construct(APMSnapshotCollector $snapshotCollector, ScriptInfoCreator $scriptInfoCreator)
    {
        $this->snapshotCollector = $snapshotCollector;
        $this->scriptInfoCreator = $scriptInfoCreator;
    }

    public function handleStart(): void
    {
        $this->snapshotCollector->takeDefaults('start');
    }

    public function handleStop(JobProcessed $event): void
    {
        $this->snapshotCollector->takeDefaults('stop');

        $scriptInfo = $this->scriptInfoCreator->create($event->job->getName(), TaskTypes::JOB);

        event(
            new SnapshottingFinished($this->snapshotCollector->getSnapshotsCollection(), $scriptInfo)
        );

        $this->terminate();
    }

    public function terminate(): void
    {
        $this->snapshotCollector->resetSnapshotsCollection();
    }
}
