<?php

namespace App\Http\Controllers;

use App\CBRepositories\T4TWinsRepository;
use App\CBRepositories\TreeTransactionsRepository;
use App\CBRepositories\ViewTreeStockDetailsRepository;
use App\CBServices\ApiLogService;
use App\CBServices\TreeTransactionService;
use App\CBServices\ViewTreeStockDetailsService;
use App\Helpers\BlockedRequestHelper;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class ApiTreeController extends ApiController
{
    //

    public function __construct()
    {
        $this->middleware("auth:api");
    }

    public function postAssignTree(Request $request)
    {
        $blockedRequest = new BlockedRequestHelper($request);
        $user = $this->initUser();
        DB::beginTransaction();
        try{
            $blockedRequest->checkBlockedRequest();

            $this->validate($request,[
                'id_part_to'=>'required|string',
                'id_pohon'=>'integer',
                'quantity'=>'required|integer|min:1|max:1'
            ]);


            //Find the wins of id_part_from
            $id_part_from = $user->getT4tParticipantNo()->getId();
            $id_part_to = $request->get('id_part_to');
            $id_pohon = $request->get('id_pohon');
            $qty = $request->get('quantity');

            //Check Stock Pohon
            $stock = ViewTreeStockDetailsRepository::findUnusedTree($id_part_from, $id_pohon);
            if($stock==0) {
                return ResponseHelper::responseAPI(403,'The tree stock is empty!');
            }

            $id_trans = TreeTransactionService::assignTree($id_part_from, $id_part_to, $qty, $id_pohon);
            $trans = DB::table("tree_transactions")->where("id",$id_trans)->first();

            $data = [];
            $data['date_donated'] = date('Y-m-d H:i:s');
            $data['quantity'] = $qty;
            $data['id_pohon'] = $id_pohon;
            $data['id_part_to'] = $id_part_to;
            $data['no_trans'] = $trans->no_transaction;

            $t4tWin = T4TWinsRepository::findByTrans($id_trans);
            $winData = DB::table("view_donor_wins")->where("wins", $t4tWin->getWins())->first();
            if($winData) {
                $winData->land_photo = DB::table("t4t_t4t.t4t_lahan_details")
                    ->where("kd_lahan", $winData->land_id)
                    ->get();
            }

            $data = array_merge($data, json_decode(json_encode($winData),true));

//            ApiLogService::saveData([], $data, "ASSIGN TREE", 200);

            $a = [];
            $a['name'] = basename(request()->url());
            $a['description'] = "ASSIGN TREE";
            $a['url'] = request()->fullUrl();
            $a['ip'] = request()->ip();
            $a['useragent'] = request()->header("User-Agent");
            $a['request_data'] = json_encode(request()->all());
            $a['response_code'] = 200;
            $a['old_data'] = json_encode([]);
            $a['new_data'] = json_encode($data);
            DB::table("api_logs")->insert($a);

            DB::commit();
            return ResponseHelper::responseAPI(200,'success', $data);
        }catch (ValidationException $e) {
            //Save Log
            ApiLogService::saveResponse($e->getMessage(),"VALIDATION EXCEPTION", 403);

            $blockedRequest->hitBlockedTime();
            return ResponseHelper::responseAPI(403, $e->getMessage());
        }catch (\Exception $e) {
            //Save Log
            DB::rollback();
            ApiLogService::saveResponse($e->getMessage(), "ERROR EXCEPTION", 403);
            return ResponseHelper::responseAPI(403, $e->getMessage());
        }
    }

    public function getHistoryTransaction(Request $request)
    {
        $blockedRequest = new BlockedRequestHelper($request);
        $user = $this->initUser();
        try {
            $blockedRequest->checkBlockedRequest();

            $this->validate($request, [
                'date_from' => 'date',
                'date_until' => 'date',
                'limit'=>'integer',
                'offset'=>'integer'
            ]);

            $dateFrom = $request->get('date_from', date('Y-m') . '-01');
            $dateUntil = $request->get('date_until', date('Y-m-t'));
            $limit = $request->get('limit',10);
            $offset = $request->get('offset',0);
            $data = TreeTransactionsRepository::findAllTransactionByParticipant($user->getT4tParticipantNo()->getId(), $dateFrom, $dateUntil, $limit, $offset);
            return ResponseHelper::responseAPI(200, 'success', $data);
        }catch (ValidationException $e) {
            //Save Log
            ApiLogService::saveResponse($e->getMessage(),"VALIDATION EXCEPTION", 403);

            $blockedRequest->hitBlockedTime();
            return ResponseHelper::responseAPI(403, $e->getMessage());
        }catch (\Exception $e) {
            //Save Log
            ApiLogService::saveResponse($e->getMessage(), "ERROR EXCEPTION", 403);
            return ResponseHelper::responseAPI(403, $e->getMessage());
        }
    }

    public function getStock()
    {
        $user = $this->initUser();
        try{
            $data = ViewTreeStockDetailsService::findAllStock($user->getT4tParticipantNo()->getId());
            return ResponseHelper::responseAPI(200,'success', $data);
        }catch (\Exception $e) {
            //Save Log
            ApiLogService::saveResponse($e->getMessage(), "ERROR EXCEPTION", 403);
            return ResponseHelper::responseAPI(403, $e->getMessage());
        }
    }
}
