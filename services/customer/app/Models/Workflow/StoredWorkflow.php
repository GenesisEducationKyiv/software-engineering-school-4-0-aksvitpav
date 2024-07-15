<?php

namespace App\Models\Workflow;

use Workflow\Models\StoredWorkflow as BaseStoredWorkflow;

class StoredWorkflow extends BaseStoredWorkflow
{
    protected $connection = 'shared_pgsql';
}
