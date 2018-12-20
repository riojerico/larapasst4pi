<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware("backend");
    }

    public function getIndex()
    {
        $data['pageTitle'] = "Activity Log";
        $data['currentMenu'] = "Acitivity Log";

        return view('activity_log', $data);
    }
}
