<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="SubscribeRequest",
 *      title="Subscribe request",
 *      description="Subscribe request body data",
 *      type="object",
 *      required={"email"},
 *  )
 */
class SubscribeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @OA\Property(property="email", type="string", description="User email", example="test@test.com")
     *
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }
}
