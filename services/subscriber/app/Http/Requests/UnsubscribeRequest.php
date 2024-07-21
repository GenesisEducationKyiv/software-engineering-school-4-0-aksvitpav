<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="UnsubscribeRequest",
 *      title="Unsubscribe request",
 *      description="Unsubscribe request body data",
 *      type="object",
 *      required={"email"},
 *  )
 */
class UnsubscribeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => false,
        ]);
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
            'is_active' => 'boolean',
        ];
    }
}
