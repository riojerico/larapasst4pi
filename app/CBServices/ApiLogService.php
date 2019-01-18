<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/23/2018
 * Time: 1:48 PM
 */

namespace App\CBServices;


use App\CBModels\ApiLogs;

class ApiLogService
{

    /**
     * @param string $description
     * @param int $status_code
     * @throws \Exception
     */
    public static function saveEvent($description, $status_code)
    {
        $log = new ApiLogs();
        $log->setName(basename(request()->url()));
        $log->setDescription($description);
        $log->setUrl(request()->fullUrl());
        $log->setIp(request()->ip());
        $log->setUseragent(request()->header('User-Agent'));
        $log->setRequestData(json_encode(request()->all()));
        $log->setResponseCode($status_code);
        $log->save();
    }

    /**
     * @param array $old_data
     * @param array $new_data
     * @param string $description
     * @param int $status_code
     * @throws \Exception
     */
    public static function saveData($old_data, $new_data, $description, $status_code)
    {
        $a = [];
        $a['name'] = basename(request()->url());
        $a['description'] = $description;
        $a['url'] = request()->fullUrl();
        $a['ip'] = request()->ip();
        $a['useragent'] = request()->header("User-Agent");
        $a['request_data'] = json_encode(request()->all());
        $a['response_code'] = $status_code;
        $a['old_data'] = json_encode($old_data);
        $a['new_data'] = json_encode($new_data);
        DB::table("api_logs")->insert($a);
    }

    /**
     * @param string $response
     * @param string $description
     * @param int $status_code
     * @throws \Exception
     */
    public static function saveResponse($response, $description, $status_code)
    {
        $log = new ApiLogs();
        $log->setName(basename(request()->url()));
        $log->setDescription($description);
        $log->setUrl(request()->fullUrl());
        $log->setIp(request()->ip());
        $log->setUseragent(request()->header('User-Agent'));
        $log->setRequestData(json_encode(request()->all()));
        $log->setResponseCode($status_code);
        $log->setResponseData($response);
        $log->save();
    }
}