<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/9/2018
 * Time: 10:31 PM
 */

namespace App\CBServices;


use App\CBModels\Users;
use App\CBRepositories\T4TParticipantRepository;
use App\CBRepositories\UsersRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ErrorCodeService
{
    const FAILED_CREDENTIAL = 401001;
    const GENERAL_ERROR = 400000;
    const TEMPORARY_BLOCKED = 400001;
    const PERMANENT_BLOCKED = 400002;
    const USER_NOT_FOUND = 403001;
    const VALIDATION_EXCEPTION = 403002;
    const TREE_STOCK_EMPTY = 403003;
    const TREE_NOT_FOUND = 403004;

}