<?php

namespace App\Http\Controllers;

use App\CBModels\ApiLogs;
use App\CBRepositories\ApiLogsRepository;
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
        $data['currentMenu'] = "Activity Log";
        $data['result'] = ApiLogsRepository::findAllPagination(20);
        return view('activity_log', $data);
    }

    public function getDetail($id)
    {
        $data['pageTitle'] = 'Detail Activity';
        $data['currentMenu'] ='Activity Log';
        $data['row'] = ApiLogs::findById($id);
        return view('activity_log_detail', $data);
    }
}
