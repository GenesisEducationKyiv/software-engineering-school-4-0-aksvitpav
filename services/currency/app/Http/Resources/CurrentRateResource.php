<?php

namespace App\Http\Resources;

use App\Models\CurrencyRate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="CurrentRateResource",
 *      title="CurrentRateResource",
 *  )
 */
class CurrentRateResource extends JsonResource
{
    /** @var CurrencyRate */
    public $resource;

    /**
     * @var null|string
     */
    public static $wrap = null;

    /**
     *  Transform the resource into an array.
     *
     * @OA\Property(property="buy", type="float", description="Current buy currency rate", example=39.5)
     * @OA\Property(property="sale", type="float", description="Current sale currency rate", example=41.5)
     *
     * @param Request $request
     * @return array<string, float>
     */
    public function toArray(Request $request): array
    {
        return [
            'buy' => $this->resource->buy_rate,
            'sale' => $this->resource->sale_rate,
        ];
    }
}
