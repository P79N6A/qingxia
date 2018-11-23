<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JyeooClass extends Controller
{
    public function getToken()
    {
        $userPwd = 'a8631c9ed1ae9e17';
        $svr = new \DES3();
        $apiId = '2ff805fc-5fef-4f4e-a2a5-e0388f6ea3d4';
        $apiKey = '05f2bcd76260406bbd71a86c1d1364ed';
        $userID = 'qgjyuser26999';


        $s = $apiId . '#@@#' . $userID . '#@@#' . $userPwd;
        $v = $svr->encrypt ( $s, $apiKey );

        $url = '/v1/user?id=' . $apiId . '&v=' . $v;

        $token = Utility::PostWebRequest ( '', $url );


        $token = $this->SetToken ( $token );

        //重新获取用户token
        if (strlen($token)<200){
            return true;
        }
        $params['last_login_time'] = getDateTime();
        $params['token'] = $token;
        $ret = $this->updateQgjyUserinfoParams($id, $params);

        return $token;
    }
}
