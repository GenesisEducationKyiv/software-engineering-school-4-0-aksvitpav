<?php

namespace App\Interfaces\Jobs;

interface EmailJobInterface
{
    public function handle(): void;
}
