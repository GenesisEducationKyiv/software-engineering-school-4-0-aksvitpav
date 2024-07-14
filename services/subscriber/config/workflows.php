<?php

declare(strict_types=1);

return [
    'workflows_folder' => 'Workflows',

    'base_model' => Illuminate\Database\Eloquent\Model::class,

    'stored_workflow_model' => \App\Models\Workflow\StoredWorkflow::class,

    'stored_workflow_exception_model' => \App\Models\Workflow\StoredWorkflowException::class,

    'stored_workflow_log_model' => \App\Models\Workflow\StoredWorkflowLog::class,

    'stored_workflow_signal_model' => \App\Models\Workflow\StoredWorkflowSignal::class,

    'stored_workflow_timer_model' => \App\Models\Workflow\StoredWorkflowTimer::class,

    'workflow_relationships_table' => 'workflow_relationships',

    'prune_age' => '1 month',

    'monitor' => env('WORKFLOW_MONITOR', false),

    'monitor_url' => env('WORKFLOW_MONITOR_URL'),

    'monitor_api_key' => env('WORKFLOW_MONITOR_API_KEY'),

    'monitor_connection' => env('WORKFLOW_MONITOR_CONNECTION', config('queue.default')),

    'monitor_queue' => env(
        'WORKFLOW_MONITOR_QUEUE',
        config('queue.connections.' . config('queue.default') . '.queue', 'default')
    ),
];
