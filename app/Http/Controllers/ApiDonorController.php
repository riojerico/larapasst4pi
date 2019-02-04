<?php

namespace App\Http\Controllers;

use App\CBRepositories\T4TParticipantRepository;
use App\CBRepositories\Trees4TreesFieldLogoRepository;
use App\CBRepositories\Trees4TreesNodeRepository;
use App\CBServices\ApiLogService;
use App\CBServices\DonorService;
use App\CBServices\ErrorCodeService;
use App\Exceptions\BlockPermanentException;
use App\Exceptions\BlockTemporaryException;
use App\Helpers\BlockedRequestHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\ValidationExceptionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApiDonorController extends ApiController
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    public function postCreate(Request $request)
    {
        $blockedRequest = new BlockedRequestHelper($request);
        $user = $this->initUser();

        try {

            $blockedRequest->checkBlockedRequest();

            $this->validate($request, [
                'first_name' => 'required|string|min:3|max:99',
                'last_name'=> 'required|string|min:3|max:99',
                'email'=> 'required|email',
                'comment'=> 'string|max:255',
                'photo'=>'image'
            ]);

            $parentParticipant = $user->getT4tParticipantNo();
            $participant = DonorService::register($parentParticipant, $request);
            $participantNode = Trees4TreesNodeRepository::findByParticipantID($participant->getId());
            $participantLogo = Trees4TreesFieldLogoRepository::findByEntityId($participantNode->getNid());

            $data = [];
            $data['id_user'] = $participant->getId();
            $data['first_name'] = $participant->getName();
            $data['last_name'] = $participant->getLastname();
            $data['email'] = $participant->getEmail();
            $data['comment'] = $participant->getComment();
            $data['join_date'] = $participant->getDateJoin();
            $data['photo'] = $participantLogo->getFieldLogoFid()->getUri();


            $a = [];
            $a['created_at'] = date('Y-m-d H:i:s');
            $a['name'] = basename(request()->url());
            $a['description'] = "CREATE NEW DONOR";
            $a['url'] = request()->fullUrl();
            $a['ip'] = request()->ip();
            $a['useragent'] = request()->header("User-Agent");
            $a['request_data'] = json_encode(request()->all());
            $a['response_code'] = 201;
            $a['old_data'] = json_encode([]);
            $a['new_data'] = json_encode($data);
            DB::table("api_logs")->insert($a);

            return ResponseHelper::responseAPI(201,  "success", null, $data);
        } catch (ValidationException $e) {

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
            ApiLogService::saveResponse($e->getMessage(),"ERROR EXCEPTION", 400);
            return ResponseHelper::responseAPI(400, $e->getMessage(), ErrorCodeService::GENERAL_ERROR);
        }
    }

    public function postUpdate(Request $request)
    {
        $blockedRequest = new BlockedRequestHelper($request);
        $user = $this->initUser();

        DB::beginTransaction();
        try {
            $blockedRequest->checkBlockedRequest();

            $this->validate($request, [
                'id_participant'=>'required|string',
                'first_name' => 'required|string|min:3|max:99',
                'last_name'=> 'required|string|min:3|max:99',
                'email'=> 'required|email',
                'comment'=> 'string|max:255',
                'photo'=>'image'
            ]);

            $oldParticipant = DB::table(env('DB_T4T_T4T').".t4t_participant")
                ->where("id", request('id_participant'))
                ->first();

            $oldParticipantLogoURI = null;
            $oldParticipantLogo = DB::table(env('DB_TREES').".trees4trees_field_data_field_logo")
                ->where("entity_id", $oldParticipant->id)
                ->first();
            if($oldParticipantLogo) {
                $oldParticipantLogoFile = DB::table(env('DB_TREES').".trees4trees_file_managed")
                    ->where("fid", $oldParticipantLogo->fid)
                    ->first();
                if($oldParticipantLogoFile) {
                    $oldParticipantLogoURI = $oldParticipantLogoFile->uri;
                }
            }

            $participant = DonorService::update($request);

            $participantLogoURI = null;
            $participantLogo = DB::table(env('DB_TREES').".trees4trees_field_data_field_logo")
                ->where("entity_id", $participant->id)
                ->first();
            if($participantLogo) {
                $participantLogoFile = DB::table(env('DB_TREES').".trees4trees_file_managed")
                    ->where("fid", $participantLogo->fid)
                    ->first();
                if($participantLogoFile) {
                    $participantLogoURI = $participantLogoFile->uri;
                }
            }


            $data = [];
            $data['id_user'] = $participant->id;
            $data['first_name'] = $participant->name;
            $data['last_name'] = $participant->lastname;
            $data['email'] = $participant->email;
            $data['comment'] = $participant->comment;
            $data['join_date'] = $participant->date_join;
            $data['photo'] = $participantLogoURI;

            //Save Log
            $oldData = [
                'first_name'=> $oldParticipant->name,
                'last_name'=> $oldParticipant->lastname,
                'email'=> $oldParticipant->email,
                'comment'=> $oldParticipant->comment,
                'photo'=> $oldParticipantLogoURI
            ];

            $a = [];
            $a['created_at'] = date('Y-m-d H:i:s');
            $a['name'] = basename(request()->url());
            $a['description'] = "UPDATE DONOR";
            $a['url'] = request()->fullUrl();
            $a['ip'] = request()->ip();
            $a['useragent'] = request()->header("User-Agent");
            $a['request_data'] = json_encode(request()->all());
            $a['response_code'] = 200;
            $a['old_data'] = json_encode($oldData);
            $a['new_data'] = json_encode($data);
            DB::table("api_logs")->insert($a);

            DB::commit();
            return ResponseHelper::responseAPI(200,  "success", null, $data);
        } catch (ValidationException $e) {

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

            DB::rollBack();

            //Save Log
            ApiLogService::saveResponse($e->getMessage(), "ERROR EXCEPTION", 400);

            return ResponseHelper::responseAPI(400, $e->getMessage(), ErrorCodeService::GENERAL_ERROR);
        }
    }

    public function getList(Request $request)
    {
        $user = $this->initUser();
        $blockedRequest = new BlockedRequestHelper($request);

        try {

            $blockedRequest->checkBlockedRequest();

            $this->validate($request, [
                'email' => 'email',
                'limit'=>'integer',
                'offset'=>'integer'
            ]);

            $limit = $request->get('limit')?:10;
            $offset = $request->get('offset')?:0;

            $parentID = $user->getT4tParticipantNo()->getId();

            $data = DB::table("view_donor_details")
                ->leftjoin("view_donor_total_tree as a","a.id_user","=","view_donor_details.id_user")
                ->where("view_donor_details.id_part", $parentID)
                ->select("view_donor_details.*","a.total_tree")
                ->take($limit)
                ->offset($offset)
                ->orderby('view_donor_details.id_user','desc')
                ->get();

            foreach($data as &$row)
            {
                $row->total_tree = (int) $row->total_tree;
                $wins = DB::table("view_donor_wins")
                    ->where("id_user", $row->id_user)
                    ->get();
                foreach($wins as &$win)
                {
                    $win->land_photo = DB::table(env('DB_T4T_T4T').".t4t_lahan_details")
                        ->where("kd_lahan", $win->land_id)
                        ->get();
                }
                $row->wins = $wins;
            }

            return ResponseHelper::responseAPI(200,  "success", null, $data, ['parent_id'=>$parentID]);
        } catch (ValidationException $e) {
            return ResponseHelper::responseAPI(403, $e->getMessage(), ErrorCodeService::VALIDATION_EXCEPTION);
        } catch (BlockTemporaryException $e) {
            ApiLogService::saveResponse($e->getMessage(), "BLOCK EXCEPTION", 400);
            return ResponseHelper::responseAPI(400, $e->getMessage(), ErrorCodeService::TEMPORARY_BLOCKED);
        } catch (BlockPermanentException $e) {
            ApiLogService::saveResponse($e->getMessage(), "BLOCK EXCEPTION", 400);
            return ResponseHelper::responseAPI(400, $e->getMessage(), ErrorCodeService::PERMANENT_BLOCKED);
        }
    }
}
