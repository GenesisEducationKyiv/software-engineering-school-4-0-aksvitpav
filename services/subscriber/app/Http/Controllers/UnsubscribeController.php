<?php

namespace App\Http\Controllers;

use App\Actions\UnsubscribeUserAction;
use App\DTOs\SubscriberDTO;
use App\Exceptions\SubscribtionError;
use App\Http\Requests\UnsubscribeRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class UnsubscribeController extends Controller
{
    /**
     * @OA\Get(
     *       path="/api/unsubscribe",
     *       operationId="unsubscribe-from-emails",
     *       summary="Unsubscribe from emails",
     *       tags={"Subscription"},
     *       description="Unsubscribe from emails",
     *       @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(ref="#/components/schemas/UnubscribeRequest")
     *       ),
     *       @OA\Response(
     *         response="200",
     *         description="Successful",
     *         @OA\JsonContent(
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="You are successfully unsubscribed"
     *                 )
     *             )
     *       )
     *   )
     *
     * @param UnsubscribeRequest $request
     * @param UnsubscribeUserAction $unsubscribeUserAction
     * @return JsonResponse
     * @throws SubscribtionError
     */
    public function __invoke(
        UnsubscribeRequest $request,
        UnsubscribeUserAction $unsubscribeUserAction
    ): JsonResponse {
        /** @var array{"email":string} $requestData */
        $requestData = $request->validated();
        $dto = SubscriberDTO::fromArray($requestData);

        $unsubscribed = $unsubscribeUserAction->execute($dto);

        if ($unsubscribed) {
            return response()->json(['message' => 'Email successfully unsubscribed'], Response::HTTP_OK);
        }

        return response()->json(['message' => 'Email is not exist or already unsubscribed'], Response::HTTP_CONFLICT);
    }
}
