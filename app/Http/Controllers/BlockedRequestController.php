<?php

namespace App\Http\Controllers;

use App\CBModels\BlockedRequests;
use App\CBRepositories\BlockedRequestsRepository;
use App\Helpers\BlockedRequestHelper;
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

    public function getDelete(Request $request, $id)
    {
        $data = BlockedRequests::findById($id);

        //Unblock
        (new BlockedRequestHelper($request))->unblockByKey($data->getRequestSignature());

        BlockedRequests::deleteById($id);
        return redirect()->back()->with(['message'=>'Unblock success!','message_type'=>'success']);
    }
}
