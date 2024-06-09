<?php

namespace App\Adapters;

use App\Enums\CurrencyCodeEnum;
use App\Exceptions\CurrencyRateFetchingError;
use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
use App\VOs\USDRateVO;
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
    public function getCurrencyRate(): USDRateVO
    {
        try {
            $response = $this->client->request('GET', $this->baseUrl, $this->options);
            /** @var array<array{"ccy":string, "buy":float, "sale":float}> $data */
            $data = json_decode($response->getBody()->getContents(), true);

            throw_if(
                !(is_array($data)),
                new CurrencyRateFetchingError('Fetched data has mismatch format.')
            );

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

            return new USDRateVO(
                buyRate: $USDBuyRate,
                saleRate: $USDSaleRate,
            );
        } catch (Throwable $e) {
            return new USDRateVO(error: $e->getMessage());
        }
    }
}
