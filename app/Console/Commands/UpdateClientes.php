<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Client;
use Illuminate\Support\Facades\DB;

class UpdateClientes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:clientes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update clientes desde suma';

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
        $datos = Client::where('suma_estado','ManualChecking')->orwhere('suma_estado','ControlListManualChecking')->where(function ($query) { 
            $query->where('status', '<>', 'Archivado')
                  ->orWhereNull('status');
        })->get();

        foreach($datos as $dato)
        {
            $res = $this->checkstatus($dato->suma_id);

            if($res == 'Checked')
            {
                $args = array(
                    'suma_estado' => 'Checked'
                  );
              
                $fields = array(
                    'id' => $dato->id
                  );
              
                $update = Client::updateOrCreate($fields, $args);
            }
        }
    }

    public function checkstatus($data)
    {
      $curl = curl_init();
      $token = $this->gtoken();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://veridocid.azure-api.net/api/id/status",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"uuid\": \"$data\"}",
        CURLOPT_HTTPHEADER => array(
          "Authorization: Bearer $token",
          "Content-Type: text/plain",
          "Cookie: did=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; did_compat=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; ARRAffinity=3f1534e0112c1392eb84f7c0e4028513a1245721c23b8a0853e115a5a59342c9"
        ),
      ));
  
      $response = curl_exec($curl);
      curl_close($curl);
      return $response;
    }

    public function gtoken()
    {
      $token = DB::TABLE('tokens')->where('company', 'veridocid')->first();
  
      return $token->token;
    }
  
  
}
