<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/10/2018
 * Time: 8:20 PM
 */

namespace App\Helpers;

class ResponseHelper
{

    public static function goAction($action, $message, $type = 'info') {
        return redirect()->action($action)->with(['message'=>$message,'message_type'=>$type]);
    }

    public static function goBack($message, $type = "info")
    {
        return redirect()->back()->with(['message'=>$message,'message_type'=>$type]);
    }

    /**
     * @param $status
     * @param $message
     * @param array $data
     * @param array $additional_object
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseAPI($status, $message, $data = null, $additional_object = null)
    {
        $response = [];
        $response['status'] = $status;
        $response['message'] = $message;

        if($additional_object && is_array($additional_object)) {
            $response = array_merge($response, $additional_object);
        }

        if($data) $response['data'] = $data;
        return response()->json($response, $status);
    }

}