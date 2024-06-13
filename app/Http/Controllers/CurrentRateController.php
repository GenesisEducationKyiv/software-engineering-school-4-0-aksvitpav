<?php

namespace App\Http\Controllers;

use App\Actions\GetCurrentRateAction;
use App\Http\Resources\CurrentRateResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class CurrentRateController extends Controller
{
    /**
     * @OA\Get (
     *      path="/api/rate",
     *      operationId="get-current-rate",
     *      summary="Get current currency rate",
     *      tags={"Currency"},
     *      description="Get current currency rate",
     *      @OA\Response(
     *        response="200",
     *        description="Successful",
     *        @OA\JsonContent(
     *           @OA\Property(
     *               property="data",
     *               type="array",
     *                 @OA\Items(ref="#/components/schemas/CurrentRateResource")
     *           ),
     *        )
     *      ),
     *     @OA\Response(
     *           response=400,
     *           description="Bad Request",
     *           @OA\JsonContent(
     *               @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="Invalid status value"
     *               )
     *           )
     *       )
     *  )
     *
     * @param Request $request
     * @param GetCurrentRateAction $getCurrentRateAction
     * @return CurrentRateResource|JsonResponse
     */
    public function __invoke(
        Request $request,
        GetCurrentRateAction $getCurrentRateAction
    ): CurrentRateResource|JsonResponse {
        $currentRate = $getCurrentRateAction->execute();

        if ($currentRate) {
            return CurrentRateResource::make($currentRate);
        } else {
            return response()->json(['message' => 'Invalid status value'], Response::HTTP_BAD_REQUEST);
        }
    }
}
