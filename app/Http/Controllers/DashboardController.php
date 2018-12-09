<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware("BackendMiddleware");
    }

    public function getIndex()
    {

        return view("dashboard");
    }
}
