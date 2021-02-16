<?php

namespace App\Console\Commands;

use App\ConfigAlertas;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ActualizarValorUID extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'actualizar:uid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el valor del uid consumiento api';

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
      $hoy = Carbon::now()->format("Y-m-d");
      $endpoint = "https://www.banxico.org.mx/SieAPIRest/service/v1/series/SP68257/datos/" . $hoy . "/" . $hoy;
      $client = curl_init();
      curl_setopt($client, CURLOPT_URL, $endpoint);
      curl_setopt($client,CURLOPT_HTTPHEADER,array(
        'Bmx-Token:ba350db5f68fde993e7979d888bef54441414955063870c0fc04681ec87a86bd'
      ));
      curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
      $data=json_decode(curl_exec($client));
      curl_close($client);
      $db=ConfigAlertas::find(1);
      $db->valor=$data->bmx->series[0]->datos[0]->dato;
      $db->save();
    }
}
