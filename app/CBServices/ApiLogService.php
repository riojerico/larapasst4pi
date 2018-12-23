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
        $log = new ApiLogs();
        $log->setName(basename(request()->url()));
        $log->setDescription($description);
        $log->setUrl(request()->fullUrl());
        $log->setIp(request()->ip());
        $log->setUseragent(request()->header('User-Agent'));
        $log->setRequestData(json_encode(request()->all()));
        $log->setResponseCode($status_code);
        $log->setOldData(json_encode($old_data));
        $log->setNewData(json_encode($new_data));
        $log->save();
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