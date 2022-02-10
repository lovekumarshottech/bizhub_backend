<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class BaseController extends Controller
{
    public function sendResponse($status = Response::HTTP_OK, $result, $message = "")
    {
    	$response = [
            'status' => $status,
            'message' => $message,
            'data'    => $result,
        ];


        return response()->json($response, $status);
    }

    public function sendError($error, $errorMessages = [], $code = Response::HTTP_NOT_FOUND)
    {
    	$response = [
            'code' => $code,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}
