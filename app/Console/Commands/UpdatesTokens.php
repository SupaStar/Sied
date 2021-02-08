<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tokens;
use Illuminate\Support\Facades\DB;

class UpdatesTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update tokens external sites';

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

      $gdata = DB::TABLE('tokens')->where('company', 'veridocid')->first();

      $sumaid = $gdata->id;
      $client = $gdata->client;
      $secret = $gdata->secret;
      $audience = $gdata->audience;
      $grant_type = $gdata->grant_type;

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://veridocid.azure-api.net/api/auth/token",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "grant_type=$grant_type&client_id=$client&client_secret=$secret&audience=$audience",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/x-www-form-urlencoded",
            "Cookie: did=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; did_compat=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; ARRAffinity=8d69b90c05ecaa44b24e8b059b2b951042d72e06f694220099f034a811455a01"
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $rdata = json_decode($response, true);

        $args = array(
          'token' =>  $rdata['access_token']);


        $fields = array(
            'id' => $sumaid
        );

      Tokens::updateOrCreate($fields,$args);


        echo $rdata['access_token'];

    }
}
