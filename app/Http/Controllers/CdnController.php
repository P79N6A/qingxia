<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CdnController extends Controller
{
    private $accessKeyId="1g1V0oXyE6QKFps0";
    private $accessSecret="mPsGVPPr0aghttEB9EoZlDpX9mYjK0";
    public function refresh($cdnFile){
        $iClientProfile = \DefaultProfile::getProfile("cn-hangzhou", $this->accessKeyId, $this->accessSecret);
        $client = new \DefaultAcsClient($iClientProfile);

        $req = new \Cdn\Request\V20141111\RefreshObjectCachesRequest();
        $req->setObjectType("File"); // or Directory
        $req->setObjectPath($cdnFile);
        $resp = $client->getAcsResponse($req);
        return $resp;
    }
}
