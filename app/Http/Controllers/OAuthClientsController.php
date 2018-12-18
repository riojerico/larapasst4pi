<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OAuthClientsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware("backend");
    }

    public function getIndex()
    {
        $data['pageTitle'] = "OAuth Client Secret";
        $data['currentMenu'] = 'OAuth Clients';
        return view("oauth_clients", $data);
    }
}
