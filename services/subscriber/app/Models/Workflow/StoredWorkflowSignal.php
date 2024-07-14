<?php

namespace App\Models\Workflow;

use Workflow\Models\StoredWorkflowSignal as BaseStoredWorkflowSignal;

class StoredWorkflowSignal extends BaseStoredWorkflowSignal
{
    protected $connection = 'shared_pgsql';
}
