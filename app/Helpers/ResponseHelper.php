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

    public static function responseAPI($status, $message, $data = null)
    {
        $response = [];
        $response['status'] = $status;
        $response['message'] = $message;
        if($data) $response['data'] = $data;
        return response()->json($response, $status);
    }

}