<?php

namespace App\Http\Controllers;

use App\CBModels\BlockedRequests;
use App\CBRepositories\BlockedRequestsRepository;
use Illuminate\Http\Request;

class BlockedRequestController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware("backend");
    }

    public function getIndex()
    {
        $data['pageTitle'] = "Blocked Request";
        $data['currentMenu'] = "Blocked Request";
        $data['result'] = BlockedRequestsRepository::findAllPagination(20);
        return view('blocked_request', $data);
    }

    public function getDelete($id)
    {
        BlockedRequests::deleteById($id);
        return redirect()->back()->with(['message'=>'Unblock success!','message_type'=>'success']);
    }
}
