<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class CheckStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::join("users_token", "users.id", "=", "users_token.user_id")->get();
        $subscriptionStatus = 0;
        $userProductStatus = 0;
        foreach ($users as $value) {
            echo "phone= " . $value->phone . "\n";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://" . env('VAS_IP') . ":" . env('VAS_PORT') . "/v1/charge/status?token=" . $value->token);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: */*'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);
            $server_output = json_decode($server_output, true);
            curl_close($ch);
            echo "status= ";
            print_r($server_output);
            if ($server_output["data"]["subscriptionStatus"] == "ACTIVE")
                $subscriptionStatus++;
            if ($server_output["data"]["userProductStatus"] == "ACTIVE")
                $userProductStatus++;
        }
        echo "count= " . sizeof($users) . "\n";
        echo "subscriptionStatus= " . $subscriptionStatus . "\n";
        echo "userProductStatus= " . $userProductStatus . "\n";
    }
}
