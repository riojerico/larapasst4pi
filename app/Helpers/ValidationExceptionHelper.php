<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/23/2018
 * Time: 3:34 PM
 */

namespace App\Helpers;


class ValidationExceptionHelper
{

    public static function errorsToString($errors, $separated = "<br/>")
    {
        $messages = [];
        foreach($errors as $key=>$val) {
            $messages[] = "-".$val[0];
        }
        return implode($separated, $messages);
    }
}