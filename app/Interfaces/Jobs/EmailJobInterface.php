<?php

namespace App\Interfaces\Jobs;

use App\Actions\SetEmailedAtForSubscriberAction;

interface EmailJobInterface
{
    public function handle(SetEmailedAtForSubscriberAction $setEmailedAtForSubscriberAction): void;
}
