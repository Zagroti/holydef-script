<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sms {tableName}';

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
        $users = DB::table($this->argument('tableName'))->get();
        foreach ($users as $key => $value) {
            echo $key . "\n";
            if ($value->phone) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://" . env('VAS_IP') . ":" . env('VAS_PORT') . "/v1/subscribe?product_id=" . env('VAS_PRODUCT_ID') . "&service_id=" . env('VAS_SERVICE_ID') . "&user_number=" . $value->phone);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: */*'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_output = curl_exec($ch);
                $server_output = json_decode($server_output, true);
                curl_close($ch);
                print_r($server_output);
            }
        }


    }
}
