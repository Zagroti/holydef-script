<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Exceptions\ApiException;
use App\Http\Controllers\ApiController;
use App\Inside\Constants;
use App\User;
use App\UserApps;
use App\UsersLoginToken;
use App\UsersLoginTokenLog;
use App\Wallet;
use Hashids\Hashids;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Redis;

class ZamanakController extends ApiController
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
                'your phone number is wrong'
            );
        $phone = '98' . $matches[0][0];
        $phone = str_replace('+', '', $phone);
        $phone = $this->normalizePhoneNumber($phone);
        $token = rand(1000, 9999);
        if (!$this->UsersLoginToken($phone, $token, Constants::LOGIN_TYPE_SMS))
            throw new ApiException(
                ApiException::EXCEPTION_BAD_REQUEST_400,
                'To many request'
            );
        if ($request->header('X-DEBUG') == 1)
            return $this->respond(["status" => "success", 'code' => $token], null);
        $result = $this->callZamanakApi([
            "method" => "sendCaptchaSms",
            "username" => "xxxx",
            "password" => "xxx",
            "mobile" => $phone,
            "captcha" => $token
        ]);
        if (isset($result['error']) != null)
            throw new ApiException(
                ApiException::EXCEPTION_NOT_FOUND_404,
                $result['error']
            );
        return $this->respond(["status" => "success"], null);
    }

    public function postCallRequest(Request $request)
    {
        $phone = $request->input('mobile');
        $re = '/(\0)?([ ]|,|-|[()]){0,2}9[0|1|2|3|4|9]([ ]|,|-|[()]){0,2}(?:[0-9]([ ]|,|-|[()]){0,2}){8}/m';
        $str = $phone;
        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
        if (!$matches)
            throw new ApiException(
                ApiException::EXCEPTION_NOT_FOUND_404,
                'your phone number is wrong'
            );
        $phone = '98' . $matches[0][0];
        $phone = str_replace('+', '', $phone);
        $phone = $this->normalizePhoneNumber($phone);
        $token = rand(1000, 9999);
        if (!$this->UsersLoginToken($phone, $token, Constants::LOGIN_TYPE_CALL))
            throw new ApiException(
                ApiException::EXCEPTION_BAD_REQUEST_400,
                'To many request'
            );
        if ($request->header('X-DEBUG') == 1)
            return $this->respond(["status" => "success", 'code' => $token], null);
        $phone_send = substr($phone, 2);
        $result = $this->callZamanakApi([
            "method" => "voiceOtp",
            "username" => "xxxx",
            "password" => "xxxx",
            "mobile" => '0' . $phone_send,
            "numberToSay" => $token,
            "captcha" => null
        ]);
        if (isset($result['error']) != null)
            throw new ApiException(
                ApiException::EXCEPTION_NOT_FOUND_404,
                $result['error']
            );
        return $this->respond(["status" => "success"], null);
    }

    public function postVerifyRequest(Request $request)
    {
        if (!$request->header('uuid'))
            throw new ApiException(
                ApiException::EXCEPTION_NOT_FOUND_404,
                'Plz check your uuid'
            );
        if (!$request->header('app'))
            throw new ApiException(
                ApiException::EXCEPTION_NOT_FOUND_404,
                'Plz check your app'
            );
        if (!$request->input('code'))
            throw new ApiException(
                ApiException::EXCEPTION_NOT_FOUND_404,
                'Plz check your code'
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
                'your phone number is wrong'
            );
        $phone = '98' . $matches[0][0];
        $phone = str_replace('+', '', $phone);
        $phone = $this->normalizePhoneNumber($phone);
        if (!$this->CheckUsersLoginToken($phone, $request->input('code')))
            throw new ApiException(
                ApiException::EXCEPTION_NOT_FOUND_404,
                'code isn`t true'
            );
        return $this->respond($this->verify($phone, $request->header('agent'), $request->header('app'), $request->header('uuid')));
    }

    private function callZamanakApi($req)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://zamanak.ir/api/json-v5");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "req=" . urlencode(json_encode($req)));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
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


    private function UsersLoginToken($phone, $token, $type)
    {
        UsersLoginToken::where('expire_at', '<', strtotime(date('Y-m-d H:i:s')))->delete();
        $UsersLoginToken = UsersLoginToken::where(['login' => $phone])->first();
        Redis::incr($phone);
        if (Redis::get($phone) > 5) {
            if (Redis::get($phone) == 6)
                Redis::expireAt($phone, time() + 120);
            return false;
        }
        if (!$UsersLoginToken) {
            UsersLoginToken::create(['login' => $phone, 'token' => $token, 'expire_at' => strtotime(date('Y-m-d H:i:s', strtotime("+1 min"))), 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
            UsersLoginTokenLog::create(['login' => $phone, 'token' => $token, 'type' => $type, 'expire_at' => strtotime(date('Y-m-d H:i:s', strtotime("+1 min"))), 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
            return true;
        } else {
            UsersLoginToken::where(['login' => $phone])->update(['token' => $token, 'expire_at' => strtotime(date('Y-m-d H:i:s', strtotime("+1 min")))]);
            UsersLoginTokenLog::create(['login' => $phone, 'token' => $token, 'type' => $type, 'expire_at' => strtotime(date('Y-m-d H:i:s', strtotime("+1 min"))), 'created_at' => strtotime(date('Y-m-d H:i:s'))]);
            return true;
        }
    }

    private function CheckUsersLoginToken($phone, $token)
    {
        UsersLoginToken::where('expire_at', '<', strtotime(date('Y-m-d H:i:s')))->delete();
        $UsersLoginToken = UsersLoginToken::where(['login' => $phone, 'token' => $token])->first();
        if ($UsersLoginToken) {
            UsersLoginToken::where(['login' => $phone, 'token' => $token])->delete();
            return true;
        } else
            return false;
    }


    private function verify($phone, $agent, $app, $uuid)
    {
        $user = User::join(Constants::USERS_APPS_DB, Constants::USERS_DB . '.id', '=', Constants::USERS_APPS_DB . '.user_id')
            ->where([Constants::USERS_APPS_DB . '.type_app' => $app, Constants::USERS_DB . '.active' => 1, Constants::USERS_DB . '.phone' => $phone])
            ->select(
                Constants::USERS_DB . '.id',
                Constants::USERS_DB . '.phone',
                Constants::USERS_DB . '.email',
                Constants::USERS_DB . '.username',
                Constants::USERS_DB . '.active',
                Constants::USERS_DB . '.user_level',
                Constants::USERS_DB . '.auto_charge',
                Constants::USERS_DB . '.first_name',
                Constants::USERS_DB . '.last_name',
                Constants::USERS_DB . '.birthday',
                Constants::USERS_DB . '.bio',
                Constants::USERS_DB . '.profile_type',
                Constants::USERS_DB . '.gender',
                Constants::USERS_DB . '.ref_link',
                Constants::USERS_DB . '.created_at',
                Constants::USERS_DB . '.updated_at',
                Constants::USERS_APPS_DB . '.type_app'
            )
            ->first();
        if (!$user) {
            $hashIds = new Hashids("arioo");
            $refLink = $hashIds->encode($phone, intval(microtime(true)));
            $user = User::create(['phone' => $phone, 'email' => '', 'auto_charge' => 0, 'active' => 1, 'user_level' => 0,
                'remember_token' => '', 'first_name' => '', 'birthday' => 0, 'bio' => '', 'username' => '', 'profile_type' => 0, 'gender' => 0, 'last_name' => '', "ref_link" => $refLink]);
            $info = UserApps::create(['user_id' => $user->id, 'type_app' => $app, 'created_at' => strtotime(date('Y-m-d'))]);
            $user->type_app = $info->type_app;
            $user->media_id = 0;
            //create default wallet
            Wallet::create([
                'user_id' => $user->id,
                'title' => Constants::MAIN_DEFAULT_WALLET_TITLE,
                'is_default' => Constants::MAIN_DEFAULT_WALLET_VALUE,
                'price' => Constants::MAIN_DEFAULT_WALLET_PRICE,
            ]);
        }
        if ($user->first_name == '' && $user->last_name == '')
            $user->isFirst = true;
        else
            $user->isFirst = false;
        $user->photo = $user->photo;
        $user->media_id = $user->media_id;
        $this->generateToken($user, $agent, $app);

        // Hamed - disabling queue and exchange creation from api for now
        /* create queue for user device and create exchange for user */
        /*$connection = new AMQPStreamConnection(config("rabbitmq.server"), config("rabbitmq.port"), config("rabbitmq.user"), config("rabbitmq.password"), '/');
        $channel = $connection->channel();
        $channel->exchange_declare('ex.u.' . $user->id, 'topic', false, true, false);
        $channel->queue_declare('u.' . $user->id . '.' . $uuid, false, true, false, false);
        $channel->queue_bind('u.' . $user->id . '.' . $uuid, 'ex.u.' . $user->id);
        $channel->close();
        $connection->close();*/
        /* create queue for user device and create exchange for user */
        return $user;
    }

    private function generateToken(User $user, $agent, $app)
    {
        $object = array(
            "user_id" => $user->id,
            "agent" => $agent,
            "app" => $app
        );
        $token = JWT::encode($object, config("jwt.secret"));
        $user->token = $token;
        return true;
    }
}
