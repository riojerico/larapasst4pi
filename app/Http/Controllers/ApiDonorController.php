<?php

namespace App\Http\Controllers;

use App\CBModels\T4tParticipant;
use App\CBModels\Trees4treesFieldDataFieldLogo;
use App\CBModels\Trees4TreesFieldLogo;
use App\CBModels\Trees4treesNode;
use App\CBModels\Users;
use App\CBRepositories\T4tParticipantRepository;
use App\CBRepositories\Trees4treesFieldDataFieldLogoRepository;
use App\CBRepositories\Trees4treesNodeRepository;
use App\CBServices\DonorService;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApiDonorController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    private function initUser()
    {
        return Users::findById(Auth::id());
    }

    public function postCreate(Request $request)
    {
        $user = $this->initUser();
        try {
            $this->validate($request, [
                'first_name' => 'required|string|min:3|max:99',
                'last_name'=> 'required|string|min:3|max:99',
                'email'=> 'required|email',
                'comment'=> 'string|max:255',
                'photo'=>'image'
            ]);

            $parentParticipant = $user->getT4tParticipantNo();
            $participant = DonorService::register($parentParticipant, $request);
            $participantNode = Trees4treesNodeRepository::findByParticipantID($participant->getId());
            $participantLogo = Trees4TreesFieldLogo::findById($participantNode->getNid());

            $data = [];
            $data['id_user'] = $participant->getId();
            $data['first_name'] = $participant->getName();
            $data['last_name'] = $participant->getLastname();
            $data['email'] = $participant->getEmail();
            $data['comment'] = $participant->getComment();
            $data['join_date'] = $participant->getDateJoin();
            $data['photo'] = $participantLogo->getFieldLogoFid()->getUri();

            return ResponseHelper::responseAPI(200,  "success", $data);
        } catch (ValidationException $e) {
            return ResponseHelper::responseAPI(403, $e->getMessage());
        } catch (\Exception $e) {
            return ResponseHelper::responseAPI(403, $e->getMessage());
        }
    }

    public function postUpdate(Request $request)
    {
        $user = $this->initUser();
        try {
            $this->validate($request, [
                'id_participant'=>'required|string',
                'first_name' => 'string|min:3|max:99',
                'last_name'=> 'string|min:3|max:99',
                'email'=> 'email',
                'comment'=> 'string|max:255',
                'photo'=>'image'
            ]);

            $parentParticipant = $user->getT4tParticipantNo();
            $participant = DonorService::update($request);
            $participantNode = Trees4treesNodeRepository::findByParticipantID($participant->getId());
            $participantLogo = Trees4TreesFieldLogo::findById($participantNode->getNid());

            $data = [];
            $data['id_user'] = $participant->getId();
            $data['first_name'] = $participant->getName();
            $data['last_name'] = $participant->getLastname();
            $data['email'] = $participant->getEmail();
            $data['comment'] = $participant->getComment();
            $data['join_date'] = $participant->getDateJoin();
            $data['photo'] = $participantLogo->getFieldLogoFid()->getUri();

            return ResponseHelper::responseAPI(200,  "success", $data);
        } catch (ValidationException $e) {
            return ResponseHelper::responseAPI(403, $e->getMessage());
        } catch (\Exception $e) {
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

            $data = DB::table("view_donor_details")
                ->join("view_donor_total_tree as a","a.id_user","=","view_donor_details.id_user")
                ->where("view_donor_details.id_part", $user->getT4tParticipantNo()->getId())
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
