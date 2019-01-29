<?php

namespace App\Http\Controllers;

use App\CBModels\ApiLogs;
use App\CBRepositories\OauthClientsRepository;
use App\CBServices\ApiLogService;
use App\CBServices\ErrorCodeService;
use App\Exceptions\BlockPermanentException;
use App\Exceptions\BlockTemporaryException;
use App\Helpers\BlockedRequestHelper;
use App\Helpers\ResponseHelper;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Passport;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;

class ApiAuthController extends AccessTokenController
{

    public function login(ServerRequestInterface $request)
    {
        $blockedRequest = new BlockedRequestHelper(request());

        try {

            //Check Permanent Blocked Request
            $blockedRequest->checkBlockedRequest();

            //get username (default is :email)
            $username = $request->getParsedBody()['username'];
            $clientID = $request->getParsedBody()['client_id'];

            //get user
            $user = User::where('email', '=', $username)->firstOrFail();

            if($user) {
                OauthClientsRepository::setPasswordGrant($clientID);
            }

            //issuetoken
            $tokenResponse = parent::issueToken($request);

            //convert response to json string
            $content = $tokenResponse->getContent();

            //convert json to array
            $data = json_decode($content, true);

            if(isset($data["error"]))
                throw new OAuthServerException('The user credentials were incorrect.', 6, 'invalid_credentials', 401);


            //Save Log
            $log = new ApiLogs();
            $log->setName(basename(request()->url()));
            $log->setUrl(request()->fullUrl());
            $log->setIp(request()->ip());
            $log->setUseragent(request()->header('User-Agent'));
            $log->setRequestData(json_encode(request()->all()));
            $log->setResponseCode(200);
            $log->save();


            return ResponseHelper::responseAPI(200,'success', null,null,$data);
        }
        catch (ModelNotFoundException $e) {
            //return error message

            //Save Log
            $log = new ApiLogs();
            $log->setName(basename(request()->url()));
            $log->setDescription("USER NOT FOUND");
            $log->setUrl(request()->fullUrl());
            $log->setIp(request()->ip());
            $log->setUseragent(request()->header('User-Agent'));
            $log->setRequestData(json_encode(request()->all()));
            $log->setResponseCode(403);
            $log->save();

            return ResponseHelper::responseAPI(403, $e->getMessage(), ErrorCodeService::USER_NOT_FOUND);
        }
        catch (OAuthServerException $e) { //password not correct..token not granted
            //return error message

            //Save Log
            ApiLogService::saveEvent("FAILED CREDENTIAL",401);

            $blockedRequest->hitBlockedTime();

            return ResponseHelper::responseAPI(401, $e->getMessage(), ErrorCodeService::FAILED_CREDENTIAL);
        } catch (BlockTemporaryException $e) {
            ApiLogService::saveResponse($e->getMessage(), "BLOCK EXCEPTION", 400);
            return ResponseHelper::responseAPI(400, $e->getMessage(), ErrorCodeService::TEMPORARY_BLOCKED);
        } catch (BlockPermanentException $e) {
            ApiLogService::saveResponse($e->getMessage(), "BLOCK EXCEPTION", 400);
            return ResponseHelper::responseAPI(400, $e->getMessage(), ErrorCodeService::PERMANENT_BLOCKED);
        } catch (\Exception $e) {
            ApiLogService::saveResponse($e->getTraceAsString(),"ERROR ExCEPTION",400);
            return ResponseHelper::responseAPI(400,$e->getMessage(),ErrorCodeService::GENERAL_ERROR);
        }
    }


}
