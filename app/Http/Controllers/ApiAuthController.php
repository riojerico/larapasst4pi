<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;

class ApiAuthController extends AccessTokenController
{

    public function issueToken(ServerRequestInterface $request)
    {
        try {
            //get username (default is :email)
            $username = $request->getParsedBody()['username'];

            //get user
            $user = User::where('email', '=', $username)->firstOrFail();

            //issuetoken
            $tokenResponse = parent::issueToken($request);

            //convert response to json string
            $content = $tokenResponse->getContent();
//            $content = $tokenResponse->getBody()->__toString();

            //convert json to array
            $data = json_decode($content, true);

            if(isset($data["error"]))
                throw new OAuthServerException('The user credentials were incorrect.', 6, 'invalid_credentials', 401);

            //add access token to user
            $user = collect($user);
            $user->put('access_token', $data['access_token']);

            return response()->json([
                'status'=>200,
                'message'=>'success',
                'access_token'=> $data['access_token'],
                'refresh_token'=> $data['refresh_token'],
                'data'=>$user
            ]);
        }
        catch (ModelNotFoundException $e) { // email notfound
            //return error message
            return response()->json(['status'=>403,'message'=>$e->getMessage()], 403);
        }
        catch (OAuthServerException $e) { //password not correct..token not granted
            //return error message
            return response()->json(['status'=>401,'message'=>$e->getMessage()], 401);
        }
        catch (\Exception $e) {
            ////return error message
            return response()->json(['status'=>400,'message'=>$e->getMessage()],400);
        }
    }
}
