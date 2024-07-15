<?php

namespace Tests\Architecture;

use Illuminate\Mail\Mailable;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\BuildStep;
use PHPat\Test\PHPat;

class ArchitectureTest
{
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_mails_extend_mailable_class(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Mail'))
            ->shouldExtend()
            ->classes(
                Selector::classname(Mailable::class),
            )
            ->because('Mails should extend Mailable class');
    }
}
