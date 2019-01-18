<?php

namespace App\Http\Controllers;

use App\CBRepositories\T4TParticipantRepository;
use App\CBRepositories\Trees4TreesFieldLogoRepository;
use App\CBRepositories\Trees4TreesNodeRepository;
use App\CBServices\ApiLogService;
use App\CBServices\DonorService;
use App\Helpers\BlockedRequestHelper;
use App\Helpers\ResponseHelper;
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

            //Save Log
            ApiLogService::saveData([],$data,"CREATE NEW DONOR",200);

            return ResponseHelper::responseAPI(200,  "success", $data);
        } catch (ValidationException $e) {

            //Save Log
            ApiLogService::saveResponse($e->getMessage(),"VALIDATION EXCEPTION", 403);

            $blockedRequest->hitBlockedTime();
            return ResponseHelper::responseAPI(403, $e->getMessage());
        } catch (\Exception $e) {

            //Save Log
            ApiLogService::saveResponse($e->getMessage(),"ERROR EXCEPTION", 403);

            return ResponseHelper::responseAPI(403, $e->getMessage());
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


            $participant = DonorService::update($request);
            $participantNode = Trees4TreesNodeRepository::findByParticipantID($participant->getId());
            $participantLogo = Trees4TreesFieldLogoRepository::findByEntityId($participantNode->getNid());

//            $oldParticipant = T4TParticipantRepository::findByParticipantID(request('id_participant'));
//
//            $oldParticipantNode = Trees4TreesNodeRepository::findByParticipantID($oldParticipant->getId());
//            $oldParticipantLogo = Trees4TreesFieldLogoRepository::findByEntityId($oldParticipantNode->getNid());



            $data = [];
            $data['id_user'] = $participant->getId();
            $data['first_name'] = $participant->getName();
            $data['last_name'] = $participant->getLastname();
            $data['email'] = $participant->getEmail();
            $data['comment'] = $participant->getComment();
            $data['join_date'] = $participant->getDateJoin();
//            $data['photo'] = $participantLogo->getFieldLogoFid()->getUri();

            //Save Log
//            ApiLogService::saveData([
//                'first_name'=> $oldParticipant->getName(),
//                'last_name'=> $oldParticipant->getLastname(),
//                'email'=> $oldParticipant->getEmail(),
//                'comment'=> $oldParticipant->getComment(),
//                'photo'=> $oldParticipantLogo->getFieldLogoFid()->getUri()
//            ], $data, "UPDATE DONOR", 200);

            DB::commit();
            return ResponseHelper::responseAPI(200,  "success", $data);
        } catch (ValidationException $e) {

            //Save Log
            ApiLogService::saveResponse($e->getMessage(),"VALIDATION EXCEPTION", 403);

            $blockedRequest->hitBlockedTime();
            return ResponseHelper::responseAPI(403, $e->getMessage());
        } catch (\Exception $e) {

            //Save Log
            ApiLogService::saveResponse($e->getMessage(), "ERROR EXCEPTION", 403);

            DB::rollBack();
            return ResponseHelper::responseAPI(403, $e->getMessage());
        }
    }

    public function getList(Request $request)
    {
        $user = $this->initUser();

        try {
            $this->validate($request, [
                'email' => 'email'
            ]);

            $parentID = $user->getT4tParticipantNo()->getId();

            $data = DB::table("view_donor_details")
                ->leftjoin("view_donor_total_tree as a","a.id_user","=","view_donor_details.id_user")
                ->where("view_donor_details.id_part", $parentID)
                ->select("view_donor_details.*","a.total_tree")
                ->get();

            foreach($data as &$row)
            {
                $row->total_tree = (int) $row->total_tree;
                $wins = DB::table("view_donor_wins")
                    ->where("id_user", $row->id_user)
                    ->get();
                foreach($wins as &$win)
                {
                    $win->land_photo = DB::table("t4t_t4t.t4t_lahan_details")
                        ->where("kd_lahan", $win->land_id)
                        ->get();
                }
                $row->wins = $wins;
            }

            return ResponseHelper::responseAPI(200,  "success", $data);
        } catch (ValidationException $e) {
            return ResponseHelper::responseAPI(403, $e->getMessage());
        }
    }
}
