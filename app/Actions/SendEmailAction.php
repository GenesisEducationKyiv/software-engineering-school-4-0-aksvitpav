<?php

namespace App\Actions;

use App\Exceptions\SendingEmailException;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Log;

class SendEmailAction
{
    /**
     * @param Subscriber $subscriber
     * @param string $emailJobClass
     * @param mixed ...$emailParams
     * @return void
     * @throws SendingEmailException
     */
    public function execute(Subscriber $subscriber, string $emailJobClass, mixed ...$emailParams): void
    {
        try {
            $emailJobClass::dispatch($subscriber, ...$emailParams);
        } catch (\Exception $exception) {
            Log::error('Error sending email: ' . $exception->getMessage(), ['exception' => $exception]);
            throw new SendingEmailException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
