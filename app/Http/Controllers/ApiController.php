<?php

namespace App\Http\Controllers;

use App\CBModels\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    /**
     * @return Users
     */
    public function initUser()
    {
        return Users::findById(Auth::id());
    }
}
