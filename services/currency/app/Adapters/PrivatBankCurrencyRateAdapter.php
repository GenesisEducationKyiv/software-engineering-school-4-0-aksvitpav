<?php

namespace App\Adapters;

use App\Enums\CurrencyCodeEnum;
use App\Exceptions\CurrencyRateFetchingError;
use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
use App\Interfaces\VOs\CurrencyRateVOInterface;
use App\VOs\CurrencyErrorRateVO;
use App\VOs\CurrencyRateVO;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Leyton\LaravelCircuitBreaker\Circuit;
use Throwable;
use UnexpectedValueException;

class PrivatBankCurrencyRateAdapter implements CurrencyRateAdapterInterface
{
    /**
     * @var string
     */
    protected string $baseUrl;

    /**
     * @var array<string, array<string, int|string>>
     */
    protected array $options = [];

    /**
     * @param Client $client
     */
    public function __construct(protected Client $client)
    {
        $this->baseUrl = 'https://api.privatbank.ua/p24api/pubinfo';

        $this->options = [
            'query' => [
                'exchange' => 1,
                'coursid' => 5
            ],
        ];
    }

    /** @inheritDoc */
    public function getCurrencyRate(): CurrencyRateVOInterface
    {
        $circuit = app()->make(Circuit::class);
        $packet = $circuit->run("privatbank-api", function () {
            try {
                $response = $this->client->request('GET', $this->baseUrl, $this->options);
                $requestContents = $response->getBody()->getContents();

                throw_if(
                    !json_validate($requestContents),
                    new CurrencyRateFetchingError('Fetched data has mismatch format.')
                );

                /** @var array<array{"ccy":string, "buy":float, "sale":float}> $data */
                $data = json_decode($requestContents, true);

                Log::channel('daily_currency_api')->info('privatbank', ['data' => $data]);

                $USDBuyRate = null;
                $USDSaleRate = null;

                foreach ($data as $currency) {
                    if ($currency['ccy'] === CurrencyCodeEnum::USD->value) {
                        $USDBuyRate = $currency['buy'];
                        $USDSaleRate = $currency['sale'];
                    }
                }

                throw_if(
                    !($USDBuyRate && $USDSaleRate),
                    new CurrencyRateFetchingError('Currency rates not found in response.')
                );

                return new CurrencyRateVO(
                    buyRate: $USDBuyRate,
                    saleRate: $USDSaleRate,
                );
            } catch (Throwable $e) {
                return new CurrencyErrorRateVO(errorMessage: $e->getMessage(), previous: $e->getPrevious());
            }
        });

        /** @var CurrencyRateVOInterface $result */
        $result = $packet->result;

        if (!($result instanceof CurrencyRateVOInterface)) {
            throw new UnexpectedValueException('Expected instance of CurrencyRateVOInterface.');
        }

        return $result;
    }
}
