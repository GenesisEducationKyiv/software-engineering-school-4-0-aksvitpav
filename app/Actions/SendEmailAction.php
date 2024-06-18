<?php

namespace App\Actions;

use App\Exceptions\SendingEmailException;
use App\Interfaces\Jobs\EmailJobInterface;
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
            if (!class_exists($emailJobClass)) {
                throw new \InvalidArgumentException("Class $emailJobClass does not exist.");
            }

            if (!in_array(EmailJobInterface::class, class_implements($emailJobClass))) {
                throw new \InvalidArgumentException("Class $emailJobClass must implement EmailJobInterface.");
            }

            $emailJobClass::dispatch($subscriber, ...$emailParams);
        } catch (\Throwable $exception) {
            Log::error('Error sending email: ' . $exception->getMessage(), ['exception' => $exception]);
            throw new SendingEmailException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
