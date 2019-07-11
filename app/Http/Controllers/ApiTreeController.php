<?php

namespace App\Http\Controllers;

use App\CBRepositories\T4TWinsRepository;
use App\CBRepositories\TreeTransactionsRepository;
use App\CBRepositories\ViewTreeStockDetailsRepository;
use App\CBServices\ApiLogService;
use App\CBServices\ErrorCodeService;
use App\CBServices\TreeTransactionService;
use App\CBServices\ViewTreeStockDetailsService;
use App\Exceptions\BlockPermanentException;
use App\Exceptions\BlockTemporaryException;
use App\Helpers\BlockedRequestHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\ValidationExceptionHelper;
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
                'tree_id'=>'required|integer',
                'quantity'=>'required|integer|min:1|max:1'
            ]);


            //Find the wins of id_part_from
            $id_part_from = $user->getT4tParticipantNo()->getId();
            $id_part_to = $request->get('id_part_to');
            $id_pohon = $request->get('tree_id');
            $qty = $request->get('quantity');

            if(!DB::table(env('DB_T4T_T4T').".t4t_pohon")->where("id_pohon", $id_pohon)->exists()) {
                return ResponseHelper::responseAPI(403,'The tree is not found', ErrorCodeService::TREE_NOT_FOUND);
            }

            if(!DB::table(env('DB_T4T_T4T').'.t4t_participant')->where('id', $id_part_to)->exists()) {
                return ResponseHelper::responseAPI(403,'The id_part_to does not exists', ErrorCodeService::USER_NOT_FOUND);
            }

            //Check Stock Pohon
            $stock = ViewTreeStockDetailsRepository::findUnusedTree($id_part_from, $id_pohon);
            if($stock==0) {
                return ResponseHelper::responseAPI(403,'The tree stock is empty', ErrorCodeService::TREE_STOCK_EMPTY);
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
                $winData->land_photo = DB::table(env('DB_T4T_T4T').".t4t_lahan_details")
                    ->where("kd_lahan", $winData->land_id)
                    ->get();
            }

            if($winData) {
                $data = array_merge($data, json_decode(json_encode($winData),true));
            }



            $a = [];
            $a['created_at'] = date('Y-m-d H:i:s');
            $a['name'] = basename(request()->url());
            $a['description'] = "ASSIGN TREE";
            $a['url'] = request()->fullUrl();
            $a['ip'] = request()->ip();
            $a['useragent'] = request()->header("User-Agent");
            $a['request_data'] = json_encode(request()->all());
            $a['response_code'] = 201;
            $a['old_data'] = json_encode([]);
            $a['new_data'] = json_encode($data);
            DB::table("api_logs")->insert($a);

            DB::commit();
            return ResponseHelper::responseAPI(201,'success', null, $data);
        }catch (ValidationException $e) {
            $messages = ValidationExceptionHelper::errorsToString($e->errors(),", ");

            //Save Log
            ApiLogService::saveResponse($messages,"VALIDATION EXCEPTION", 403);

            $blockedRequest->hitBlockedTime();
            return ResponseHelper::responseAPI(403, $messages, ErrorCodeService::VALIDATION_EXCEPTION);
        } catch (BlockTemporaryException $e) {
            ApiLogService::saveResponse($e->getMessage(), "BLOCK EXCEPTION", 400);
            return ResponseHelper::responseAPI(400, $e->getMessage(), ErrorCodeService::TEMPORARY_BLOCKED);
        } catch (BlockPermanentException $e) {
            ApiLogService::saveResponse($e->getMessage(), "BLOCK EXCEPTION", 400);
            return ResponseHelper::responseAPI(400, $e->getMessage(), ErrorCodeService::PERMANENT_BLOCKED);
        } catch (\Exception $e) {
            //Save Log
            DB::rollback();
            ApiLogService::saveResponse($e->getMessage(), "ERROR EXCEPTION", 400);
            return ResponseHelper::responseAPI(400, $e->getMessage(), ErrorCodeService::GENERAL_ERROR);
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
            return ResponseHelper::responseAPI(200, 'success', null, $data);
        }catch (ValidationException $e) {
            //Save Log
            ApiLogService::saveResponse($e->getMessage(),"VALIDATION EXCEPTION", 403);

            $blockedRequest->hitBlockedTime();
            return ResponseHelper::responseAPI(403, $e->getMessage(), ErrorCodeService::VALIDATION_EXCEPTION);
        } catch (BlockTemporaryException $e) {
            ApiLogService::saveResponse($e->getMessage(), "BLOCK EXCEPTION", 400);
            return ResponseHelper::responseAPI(400, $e->getMessage(), ErrorCodeService::TEMPORARY_BLOCKED);
        } catch (BlockPermanentException $e) {
            ApiLogService::saveResponse($e->getMessage(), "BLOCK EXCEPTION", 400);
            return ResponseHelper::responseAPI(400, $e->getMessage(), ErrorCodeService::PERMANENT_BLOCKED);
        } catch (\Exception $e) {
            //Save Log
            ApiLogService::saveResponse($e->getMessage(), "ERROR EXCEPTION", 400);
            return ResponseHelper::responseAPI(400, $e->getMessage(), ErrorCodeService::GENERAL_ERROR);
        }
    }

    public function getStock(Request $request)
    {
        $blockedRequest = new BlockedRequestHelper($request);
        $user = $this->initUser();
        try{
            $blockedRequest->checkBlockedRequest();

            $data = ViewTreeStockDetailsService::findAllStock($user->getT4tParticipantNo()->getId());
            return ResponseHelper::responseAPI(200,'success', null, $data);
        } catch (BlockTemporaryException $e) {
            ApiLogService::saveResponse($e->getMessage(), "BLOCK EXCEPTION", 400);
            return ResponseHelper::responseAPI(400, $e->getMessage(), ErrorCodeService::TEMPORARY_BLOCKED);
        } catch (BlockPermanentException $e) {
            ApiLogService::saveResponse($e->getMessage(), "BLOCK EXCEPTION", 400);
            return ResponseHelper::responseAPI(400, $e->getMessage(), ErrorCodeService::PERMANENT_BLOCKED);
        } catch (\Exception $e) {
            //Save Log
            ApiLogService::saveResponse($e->getMessage(), "ERROR EXCEPTION", 400);
            return ResponseHelper::responseAPI(400, $e->getMessage(), ErrorCodeService::GENERAL_ERROR);
        }
    }
}
