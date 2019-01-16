<?php

namespace App\Http\Controllers;

use App\CBModels\Users;
use App\CBRepositories\UsersRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware("backend");
    }

    public function getIndex()
    {
        $data['pageTitle'] = "Manage User Admin";
        $data['currentMenu'] = "User Admin";
        $data['result'] = UsersRepository::findAllAdminPagination(20);
        return view('admin_user', $data);
    }

    public function getClearCache()
    {
        Cache::flush();
        return redirect()->action('DashboardController@getIndex')
            ->with(['message'=>'Cache has been flushed!','message_type'=>'success']);
    }

    public function getAdd()
    {
        $data = [];
        $data['pageTitle'] = 'Add User Admin';
        $data['currentMenu'] = 'User Admin';
        return view('admin_user_add', $data);
    }

    public function postAddSave(Request $request)
    {
        try{
            $this->validate($request,[
                'name'=>'required',
                'email'=>'required|email'
            ]);

            $update = new Users();
            $update->setName($request->get('name'));
            $update->setEmail($request->get('email'));
            if($request->has("password")) {
                $update->setPassword(\Hash::make($request->get('password')));
            }
            $update->save();

            return redirect()->action("AdminUserController@getIndex")
                ->with(['message'=>'The data has been created!','message_type'=>'success']);

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=>$e->getMessage(),'message_type'=>'warning']);
        }
    }

    public function getEdit($id)
    {
        $data = [];
        $data['pageTitle'] = 'Edit User Admin';
        $data['currentMenu'] = 'User Admin';
        $data['row'] = Users::findById($id);
        return view('admin_user_edit', $data);
    }

    public function postEditSave(Request $request,$id)
    {
        try{
            $this->validate($request,[
                'name'=>'required',
                'email'=>'required|email'
            ]);

            $update = Users::findById($id);
            $update->setName($request->get('name'));
            $update->setEmail($request->get('email'));
            if($request->has("password")) {
                $update->setPassword(\Hash::make($request->get('password')));
            }
            $update->save();

            return redirect()->action("AdminUserController@getIndex")
                ->with(['message'=>'The data has been updated!','message_type'=>'success']);

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=>$e->getMessage(),'message_type'=>'warning']);
        }
    }

    public function getDelete($id)
    {
        Users::deleteById($id);
        return redirect()->back()->with(['message'=>'Delete data has been success!','message_type'=>'success']);
    }
}
