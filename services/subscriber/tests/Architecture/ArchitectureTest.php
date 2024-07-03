<?php

namespace Tests\Architecture;

use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
use App\Repositories\AbstractRepository;
use App\Repositories\SubscriberRepository;
use Exception;
use Illuminate\Mail\Mailable;
use Jobs\EmailJobInterface;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\BuildStep;
use PHPat\Test\PHPat;

class ArchitectureTest
{
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_actions_dependencies(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Actions'))
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('App\Repositories'),
                Selector::inNamespace('App\Services'),
            )
            ->because('Actions should be interface dependent, not implementation dependent');
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_adapters_implementation(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Adapters'))
            ->shouldImplement()
            ->classes(
                Selector::classname(CurrencyRateAdapterInterface::class),
            )
            ->because('Currency rate adapters should implement CurrencyRateAdapterInterface');
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_errors_extend_base_exception_class(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Exceptions'))
            ->shouldExtend()
            ->classes(
                Selector::classname(Exception::class),
            )
            ->because('Custom exceptions should extend Exception class');
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_controllers_dependencies(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Http\Controllers'))
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('App\Repositories'),
                Selector::inNamespace('App\Services'),
            )
            ->because('Controllers must be think as possible');
    }

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

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_repos_extend_abstract_repo_class(): BuildStep
    {
        return PHPat::rule()
            ->classes(
                Selector::classname(SubscriberRepository::class)
            )
            ->shouldExtend()
            ->classes(
                Selector::classname(AbstractRepository::class),
            )
            ->because('Repositories should extend AbstractRepository class');
    }
}
