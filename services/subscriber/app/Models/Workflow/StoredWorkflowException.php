<?php

namespace App\Models\Workflow;

use Workflow\Models\StoredWorkflowException as BaseStoredWorkflowException;

class StoredWorkflowException extends BaseStoredWorkflowException
{
    protected $connection = 'shared_pgsql';
}
