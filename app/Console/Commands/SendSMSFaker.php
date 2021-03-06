<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Josh\Faker\Faker;


class SendSMSFaker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sms:faker {count}';

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
        $message = 0;
        for ($i = 0; $i <= $this->argument('count'); $i++) {
            echo $i . "\n";
            $phone = Faker::mobile();
            echo $phone . "\n";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://" . env('VAS_IP') . ":" . env('VAS_PORT') . "/v1/subscribe?product_id=" . env('VAS_PRODUCT_ID') . "&service_id=" . env('VAS_SERVICE_ID') . "&user_number=" . $phone);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: */*'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);
            $server_output = json_decode($server_output, true);
            curl_close($ch);
            print_r($server_output);
            if ($server_output["message"] == "successful")
                $message++;
        }
        echo "count= " . $this->argument('count') . "\n";
        echo "successful= " . $message . "\n";
    }
}
