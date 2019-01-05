<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Exceptions\ApiException;
use App\Http\Controllers\ApiController;
use App\Inside\Constants;
use App\User;
use App\UsersToken;
use Hashids\Hashids;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;

class VasController extends ApiController
{
    public function postSmsRequest(Request $request)
    {
        $phone = $request->input('mobile');
        $re = '/(\0)?([ ]|,|-|[()]){0,2}9[0|1|2|3|4|9]([ ]|,|-|[()]){0,2}(?:[0-9]([ ]|,|-|[()]){0,2}){8}/m';
        $str = $phone;
        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
        if (!$matches)
            throw new ApiException(
                ApiException::EXCEPTION_NOT_FOUND_404,
                'شماره تلفن شما اشتباه است'
            );
        $phone = '98' . $matches[0][0];
        $phone = str_replace('+', '', $phone);
        $phone = $this->normalizePhoneNumber($phone);
        $result = $this->callApiSubscribe($phone);
        if (isset($result['message']) != "successful" && isset($result['status']) != "0")
            throw new ApiException(
                ApiException::EXCEPTION_NOT_FOUND_404,
               "لطفا شماره همراه اول وارد نمایید"
            );
        return $this->respond(["status" => "success"], null);
    }


    public function postVerifyRequest(Request $request)
    {
        if (!$request->input('code'))
            throw new ApiException(
                ApiException::EXCEPTION_NOT_FOUND_404,
                'لطفا کد را وارد نمایید'
            );
        if (!$request->header('agent'))
            throw new ApiException(
                ApiException::EXCEPTION_NOT_FOUND_404,
                'Plz check your agent'
            );
        $phone = $request->input('mobile');
        $re = '/(\0)?([ ]|,|-|[()]){0,2}9[0|1|2|3|4|9]([ ]|,|-|[()]){0,2}(?:[0-9]([ ]|,|-|[()]){0,2}){8}/m';
        $str = $phone;
        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
        if (!$matches)
            throw new ApiException(
                ApiException::EXCEPTION_NOT_FOUND_404,
                'شماره تلفن شما اشتباه است'
            );
        $phone = '98' . $matches[0][0];
        $phone = str_replace('+', '', $phone);
        $phone = $this->normalizePhoneNumber($phone);
        $result = $this->callApiVerifySubscribe($phone, $request->input('code'));
        if (isset($result['message']) != "successful" || isset($result['status']) != "0")
            throw new ApiException(
                ApiException::EXCEPTION_NOT_FOUND_404,
                'کد درست نیست'
            );
        return $this->respond($this->verify($phone, $request->header('agent'), $result["token"]));
    }

    private function callApiSubscribe($phone)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://" . env('VAS_IP') . ":" . env('VAS_PORT') . "/v1/subscribe?product_id=" . env('VAS_PRODUCT_ID') . "&service_id=" . env('VAS_SERVICE_ID') . "&user_number=" . $phone);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: */*'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $server_output = json_decode($server_output, true);
        curl_close($ch);
        return $server_output;
    }

    private function callApiVerifySubscribe($phone, $code)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://" . env('VAS_IP') . ":" . env('VAS_PORT') . "/v1/verify_subscribe?product_id=" . env('VAS_PRODUCT_ID') . "&service_id=" . env('VAS_SERVICE_ID') . "&user_number=" . $phone . "&activation_code=" . $code);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: */*'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $server_output = json_decode($server_output, true);
        curl_close($ch);
        return $server_output;
    }

    private function normalizePhoneNumber($phone)
    {
        $newNumbers = range(0, 9);
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $string = str_replace($arabic, $newNumbers, $phone);
        $string = str_replace($persian, $newNumbers, $string);
        $string = str_replace(' ', '', $string);
        return $string;
    }

    private function verify($phone, $agent, $token)
    {
        $user = User::where([Constants::USERS_DB . '.active' => 1, Constants::USERS_DB . '.phone' => $phone])
            ->select(
                Constants::USERS_DB . '.id',
                Constants::USERS_DB . '.phone',
                Constants::USERS_DB . '.email',
                Constants::USERS_DB . '.username',
                Constants::USERS_DB . '.active',
                Constants::USERS_DB . '.auto_charge',
                Constants::USERS_DB . '.first_name',
                Constants::USERS_DB . '.last_name',
                Constants::USERS_DB . '.birthday',
                Constants::USERS_DB . '.bio',
                Constants::USERS_DB . '.gender',
                Constants::USERS_DB . '.ref_link',
                Constants::USERS_DB . '.created_at',
                Constants::USERS_DB . '.updated_at'
            )
            ->first();
        if (!$user) {
            $hashIds = new Hashids(config("config.hashIds"));
            $refLink = $hashIds->encode($phone, intval(microtime(true)));
            $user = User::create(['phone' => $phone, 'email' => '', 'auto_charge' => 0, 'active' => 1, 'remember_token' => '', 'first_name' => '', 'birthday' => 0, 'bio' => '', 'username' => '', 'gender' => 0, 'last_name' => '', "ref_link" => $refLink]);
        }
        if ($user->first_name == '' && $user->last_name == '')
            $user->isFirst = true;
        else
            $user->isFirst = false;
        if (UsersToken::where(["user_id" => $user->id])->exists())
            UsersToken::where(["user_id" => $user->id])->update(["token" => $token]);
        else
            UsersToken::create(["user_id" => $user->id, "token" => $token]);
        $this->generateToken($user, $agent, $token);
        return $user;
    }

    private function generateToken(User $user, $agent, $token)
    {
        $object = array(
            "user_id" => $user->id,
            "agent" => $agent,
            "token" => $token,
        );
        $tokenJWT = JWT::encode($object, config("jwt.secret"));
        $user->token = $tokenJWT;
        return true;
    }
}
