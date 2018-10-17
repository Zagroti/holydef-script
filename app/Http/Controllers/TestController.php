<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://217.218.113.22:8090/v1/subscribe?service_id=9823&product_id=8YEARS_3000&charge_code=BAHSUBCBAH3DAYSTO8");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: */*'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $server_output = json_decode($server_output, true);
        curl_close($ch);
        dd($server_output);
    }
}
