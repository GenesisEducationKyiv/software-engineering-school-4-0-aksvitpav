<?php

namespace App\Http\Controllers;

use App\Actions\SubscribeUserAction;
use App\DTOs\SubscriberDTO;
use App\Exceptions\SubscribtionError;
use App\Http\Requests\SubscribeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class SubscribeController extends Controller
{
    /**
     * @OA\Post(
     *       path="/api/subscribe",
     *       operationId="subscribe-the-email",
     *       summary="Subscribe the email",
     *       tags={"Subscription"},
     *       description="Subscribe the email",
     *       @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(ref="#/components/schemas/SubscribeRequest")
     *       ),
     *       @OA\Response(
     *         response="200",
     *         description="Successful",
     *         @OA\JsonContent(
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Email successfully added"
     *                 )
     *             )
     *       ),
     *      @OA\Response(
     *            response=409,
     *            description="Conflict",
     *            @OA\JsonContent(
     *                @OA\Property(
     *                    property="message",
     *                    type="string",
     *                    example="Email already exists"
     *                )
     *            )
     *        )
     *   )
     *
     * @param SubscribeRequest $request
     * @param SubscribeUserAction $subscribeUserAction
     * @return JsonResponse
     * @throws SubscribtionError
     */
    public function __invoke(
        SubscribeRequest $request,
        SubscribeUserAction $subscribeUserAction
    ): JsonResponse {
        /** @var array{"email":string, "emailed_at"?: Carbon, "id"?: int} $requestData */
        $requestData = $request->validated();
        $dto = SubscriberDTO::fromArray($requestData);

        $isSubscribed = $subscribeUserAction->execute($dto);

        if ($isSubscribed) {
            return response()->json(['message' => 'Email successfully added'], Response::HTTP_OK);
        }

        return response()->json(['message' => 'Email already exists'], Response::HTTP_CONFLICT);
    }
}
