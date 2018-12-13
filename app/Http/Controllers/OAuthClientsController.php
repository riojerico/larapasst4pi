<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OAuthClientsController extends Controller
{
    //

    public function getIndex()
    {
        $data['pageTitle'] = "List OAuth Client";
        $data['currentMenu'] = 'OAuth Clients';
        return view("oauth_clients", $data);
    }
}
