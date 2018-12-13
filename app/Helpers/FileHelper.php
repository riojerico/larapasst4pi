<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/10/2018
 * Time: 8:20 PM
 */

namespace App\Helpers;

class FileHelper
{

    public static function uploadFile($name)
    {
        $fileData = request()->file($name);
        $filename = md5(str_random(6)).".".$fileData->getClientOriginalExtension();

        if(!file_exists(public_path("uploads"))) @mkdir(public_path("uploads"));
        if(!file_exists(public_path("uploads/".date('Y-m')))) @mkdir(public_path("uploads/".date("Y-m")));

        $destination = "uploads/".date('Y-m');
        $fileData->move($destination, $filename);
        return $destination.'/'.$filename;
    }
}