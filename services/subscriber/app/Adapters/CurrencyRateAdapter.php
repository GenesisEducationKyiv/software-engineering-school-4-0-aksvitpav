<?php

namespace App\Adapters;

use App\Exceptions\CurrencyRateFetchingError;
use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
use App\Interfaces\VOs\CurrencyRateVOInterface;
use App\VOs\CurrencyRateVO;
use GuzzleHttp\Client;

class CurrencyRateAdapter implements CurrencyRateAdapterInterface
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
        $this->baseUrl = 'http://nginx-currency/api/rate';
    }

    /** @inheritDoc */
    public function getCurrencyRate(): CurrencyRateVOInterface
    {
        $response = $this->client->request('GET', $this->baseUrl, $this->options);
        $requestContents = $response->getBody()->getContents();

        if (!json_validate($requestContents)) {
            throw new CurrencyRateFetchingError('Fetched data has mismatch format.');
        }

        /** @var array{
         *     buy?: float,
         *     sale?: float,
         * } $data
         */
        $data = json_decode($requestContents, true);

        $USDBuyRate = $data['buy'] ?? null;
        $USDSaleRate = $data['sale'] ?? null;

        if (!($USDBuyRate && $USDSaleRate)) {
            throw new CurrencyRateFetchingError('Currency rates not found in response.');
        }

        return new CurrencyRateVO(
            buyRate: $USDBuyRate,
            saleRate: $USDSaleRate,
        );
    }
}
