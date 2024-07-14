<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribeRequest;
use App\Workflows\SubscribeSaga;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Workflow\WorkflowStub;

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
     * @return JsonResponse
     */
    public function __invoke(SubscribeRequest $request): JsonResponse
    {
        /** @var array{"email":string, "emailed_at"?: Carbon, "id"?: int} $requestData */
        $requestData = $request->validated();
        $email = $requestData['email'];

        $saga = WorkflowStub::make(SubscribeSaga::class);
        $saga->start($email);
        while ($saga->running());

        if ($saga->output()) {
            return response()->json(['message' => 'Email successfully added'], Response::HTTP_OK);
        } else {
            Log::error('Subscribe user saga failed');
            return response()->json(
                ['message' => 'Email already exists or something went wrong'],
                Response::HTTP_CONFLICT
            );
        }
    }
}
