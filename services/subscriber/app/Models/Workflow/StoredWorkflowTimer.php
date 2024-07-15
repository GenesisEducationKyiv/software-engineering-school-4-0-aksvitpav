<?php

namespace App\Models\Workflow;

use Workflow\Models\StoredWorkflowTimer as BaseStoredWorkflowTimer;

class StoredWorkflowTimer extends BaseStoredWorkflowTimer
{
    protected $connection = 'shared_pgsql';
}
