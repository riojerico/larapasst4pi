<?php

namespace App\Http\Controllers;

use App\CBModels\T4tParticipant;
use App\CBModels\Users;
use App\CBRepositories\UsersRepository;
use App\Helpers\FileHelper;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class ManageParticipantController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware("backend");
    }

    public function getIndex()
    {
        $data['currentMenu'] = 'Manage Participant';
        $data['pageTitle'] = 'Manage Participant';

        $data['result'] = UsersRepository::findAllParticipant();
        return view("manage_participant",$data);
    }

    public function getEdit($id)
    {
        $data['currentMenu'] = 'Manage Participant';
        $data['pageTitle'] = 'Edit Participant';
        $data['row'] = Users::findById($id);
        $data['participant'] = $data['row']->getT4tParticipantNo();

        return view('participant_edit', $data);
    }

    public function postEditSave($id)
    {
        try {
            $this->validate(request(), [
                'name' => 'required',
                'email' => 'required'
            ]);

            //Update cms users
            $user = Users::findById($id);
            $user->setName(request('name'));
            $user->setEmail(request('email'));
            if(request()->has('password')) {
                $user->setPassword(\Hash::make(request('password')));
            }

            $user->save();

            //Update t4t participant
            $participant = T4TParticipant::findById($user->getT4tParticipantNo()->getNo());
            $participant->setName($user->getName());
            $participant->setLastname(request('lastname'));
            $participant->setEmail($user->getEmail());
            $participant->setComment(request('comment'));
            $participant->setAddress(request('address'));
            $participant->save();

            return ResponseHelper::goAction("ManageParticipantController@getIndex","The data has been updated!","success");

        } catch (ValidationException $e) {
            return ResponseHelper::goBack($e->getMessage(),"warning");
        }
    }

    public function getDelete($id)
    {
        $user = Users::findById($id);
        $user->delete();

        T4TParticipant::deleteById($user->getT4tParticipantNo()->getNo());

        DB::table("oauth_clients")->where("user_id", $user->getId())->delete();
        DB::table("oauth_access_tokens")->where("user_id", $user->getId())->delete();
        DB::table("oauth_auth_codes")->where("user_id", $user->getId())->delete();

        return ResponseHelper::goBack("User ".$user->getName()." has been deleted!","success");
    }
}
