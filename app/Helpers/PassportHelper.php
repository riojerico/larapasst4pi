<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/10/2018
 * Time: 8:20 PM
 */

namespace App\Helpers;


class PassportHelper
{

    public static function getAllUser()
    {
        $client = new \GuzzleHttp\Client();
        $request = $client->request("GET", url("oauth/clients"),[
            "headers"=>[
                "X-Requested-With"=>"XMLHttpRequest"
            ]
        ]);
        $response = $request->getBody()->getContents();
        return $response;
    }

}