<?php

namespace App\Adapters;

use App\Enums\CurrencyCodeEnum;
use App\Exceptions\CurrencyRateFetchingError;
use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
use App\Interfaces\VOs\CurrencyRateVOInterface;
use App\VOs\CurrencyRateVO;
use App\VOs\USDErrorRateVO;
use GuzzleHttp\Client;
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
    protected array $options;

    /**
     * @param Client $client
     */
    public function __construct(protected Client $client)
    {
        $baseUrl = config('currency_api.privat_bank_api_base_uri');
        if (!is_string($baseUrl)) {
            throw new UnexpectedValueException('The base URL must be a string.');
        }

        $this->baseUrl = $baseUrl;

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
        try {
            $response = $this->client->request('GET', $this->baseUrl, $this->options);
            $requestContents = $response->getBody()->getContents();

            throw_if(
                !json_validate($requestContents),
                new CurrencyRateFetchingError('Fetched data has mismatch format.')
            );

            /** @var array<array{"ccy":string, "buy":float, "sale":float}> $data */
            $data = json_decode($requestContents, true);

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
            return new USDErrorRateVO(errorMessage: $e->getMessage());
        }
    }
}
