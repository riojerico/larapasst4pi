<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware("backend");
    }

    public function getIndex()
    {

        $data['currentMenu'] = 'Dashboard';
        $data['pageTitle'] = 'Dashboard';
        return view("dashboard",$data);
    }
}
