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

class ABankCurrencyRateAdapter implements CurrencyRateAdapterInterface
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
        $this->baseUrl = 'https://a-bank.com.ua/backend/api/v1/legal/rates';
    }

    /** @inheritDoc */
    public function getCurrencyRate(): CurrencyRateVOInterface
    {
        $circuit = app()->make(Circuit::class);
        $packet =  $circuit->run("abank-api", function () {
            try {
                $response = $this->client->request('GET', $this->baseUrl, $this->options);
                $requestContents = $response->getBody()->getContents();

                throw_if(
                    !json_validate($requestContents),
                    new CurrencyRateFetchingError('Fetched data has mismatch format.')
                );

                /** @var array{
                 *     data?: array<array{
                 *         curA: string,
                 *         curB: string,
                 *         rate_sell: float,
                 *         rate_buy: float
                 *     }>,
                 *     status: string,
                 *     result: string,
                 *     timestamp: string,
                 *     request_ref: string,
                 *     response_ref: string
                 * } $data
                 */
                $data = json_decode($requestContents, true);

                Log::channel('daily_currency_api')->info('abank', ['data' => $data]);

                $USDBuyRate = null;
                $USDSaleRate = null;

                foreach ($data['data'] ?? [] as $currency) {
                    if ($currency['curB'] === CurrencyCodeEnum::USD->value) {
                        $USDBuyRate = $currency['rate_buy'];
                        $USDSaleRate = $currency['rate_sell'];
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
                return new CurrencyErrorRateVO(errorMessage: $e->getMessage());
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
