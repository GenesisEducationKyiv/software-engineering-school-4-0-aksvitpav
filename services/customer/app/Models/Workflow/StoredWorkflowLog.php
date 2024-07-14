<?php

namespace App\Models\Workflow;

use Workflow\Models\StoredWorkflowLog as BaseStoredWorkflowLog;

class StoredWorkflowLog extends BaseStoredWorkflowLog
{
    protected $connection = 'shared_pgsql';
}
