<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Files;
use App\Logs;
use Image;
use App\Client;
use App\EntidadFederativa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class Utilities extends Controller
{


  public function imgto64ine(Request $request)
  {
    $res = array(
      "message" => "fail",
      "code" => "300"
    );

    $inefront = $request->file('inefront');
    $ineback = $request->file('ineback');

    $extinefront = strtolower($inefront->getClientOriginalExtension());
    $extineback = strtolower($ineback->getClientOriginalExtension());

    if (($extinefront == 'jpeg') or ($extinefront == 'png') or ($extinefront == 'jpg')) {
      if (($extineback == 'jpeg') or ($extineback == 'png') or ($extineback == 'jpg')) {
        $base64inefront = base64_encode(file_get_contents($inefront));
        $base64ineback = base64_encode(file_get_contents($ineback));

        $unique = uniqid();
        $data = $this->checkine($unique, $base64inefront, $base64ineback);
        $status = '';

        $this->logs('CONSULTA SUMA', 'ID:'.$unique.', TIPO: INE');

        while ($status == 'WaitingIdentification' or $status == 'WaitingChecked' or $status == '' or $status == 'WaitingChecking') {
          $status = $this->checkstatus($data);

          $this->logs('CONSULTA SUMA', 'ID:'.$unique.', TIPO: INE, SUMAID: '.$data.', ESTADO: '.$status);
        }

        $this->logs('CONSULTA SUMA', 'ID:'.$unique.', TIPO: INE, SUMAID: '.$data.', ESTADO: '.$status);

        if ($status == 'Checked') {
          return $this->ineresult($data);
        } else if ($status == "ManualChecking") {
          $this->pendiente($inefront, $ineback, null, $unique, $data, $status);
          $res = array(
            "message" => "ManualChecking",
            "code" => "310"
          );
        } else if ($status == 'ControlListManualChecking') {
          $this->pendiente($inefront, $ineback, null, $unique, $data, $status);
          $res = array(
            "message" => "ControlListManualChecking",
            "code" => "320",
            "data" => $this->ineresult($data)
          );
        } else {
          $res = array(
            "message" => "Imagenes Invalidas",
            "code" => "330",
            "data" => $this->ineresult($data)
          );
        }
      }
    }

    return response()->json($res);
  }

  public function pendiente($inefront, $ineback, $pasaportefront, $id, $sumaid, $sumaestado)
  {

    $pasaporte = $pasaportefront ? $pasaportefront : 1;
    $fileine = $inefront ? $inefront : 1;
    $ineback = $ineback ? $ineback : 1;


    $cliente = new Client();
    $cliente->consulta_id = $id;
    $cliente->suma_id = $sumaid;
    $cliente->suma_estado = $sumaestado;
    $cliente->save();

    $cid = $cliente->id;
    $user = Auth::user();
    $userid = $user->id;

    if ($pasaporte != 1) {
      $path = 'fisicas/pasaporte';
      $extension = strtolower($pasaporte->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '-pasaporte.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'PASAPORTE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($pasaporte));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });

        Storage::disk('public')->put($path . '/' . $filename, (string) $image->encode('jpg', 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'PASAPORTE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '-pasaporte.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($pasaporte));
      }
    }


    if ($fileine != 1) {
      $path = 'fisicas/ine';
      $extension = strtolower($fileine->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '-frontal.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'INE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($fileine));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });

        Storage::disk('public')->put($path . '/' . $filename, (string) $image->encode('jpg', 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'INE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '-frontal.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($fileine));
      }
    }

    if ($ineback != 1) {
      $path = 'fisicas/ine';
      $extension = strtolower($ineback->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '-trasera.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'INE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($ineback));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });

        Storage::disk('public')->put($path . '/' . $filename, (string) $image->encode('jpg', 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'INE';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '-trasera.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($ineback));
      }
    }


  }

  public function imgto64pasaporte(Request $request)
  {
    $res = array(
      "message" => "fail",
      "code" => "300"
    );

    $pasaportefront = $request->file('pasaportefront');

    $extpasaportefront = strtolower($pasaportefront->getClientOriginalExtension());

    if (($extpasaportefront == 'jpeg') or ($extpasaportefront == 'png') or ($extpasaportefront == 'jpg')) {
        $base64pasaportefront = base64_encode(file_get_contents($pasaportefront));

        $unique = uniqid();
        $data = $this->checkpasaporte($unique, $base64pasaportefront);
        $status = '';

        $this->logs('CONSULTA SUMA', 'ID:'.$unique.', TIPO: PASAPORTE');

        while ($status == 'WaitingIdentification' or $status == 'WaitingChecked' or $status == '' or $status == 'WaitingChecking') {
          $status = $this->checkstatus($data);

          $this->logs('CONSULTA SUMA', 'ID:'.$unique.', TIPO: PASAPORTE, SUMAID: '.$data.', ESTADO: '.$status);
        }

        $this->logs('CONSULTA SUMA', 'ID:'.$unique.', TIPO: PASAPORTE, SUMAID: '.$data.', ESTADO: '.$status);

        if ($status == 'Checked') {
          return $this->ineresult($data);
        } else if ($status == "ManualChecking") {
          $this->pendiente(null, null, $pasaportefront, $unique, $data, $status);

          $res = array(
            "message" => "ManualChecking",
            "code" => "310"
          );
        } else if ($status == 'ControlListManualChecking') {
          $this->pendiente(null, null, $pasaportefront, $unique, $data, $status);

          $res = array(
            "message" => "ControlListManualChecking",
            "code" => "320",
            "data" => $this->ineresult($data)
          );
        } else {
          $res = array(
            "message" => "Imagen Invalida",
            "code" => "330",
            "data" => $this->ineresult($data)
          );
        }
    }

    return response()->json($res);
  }


  public function checkemail($email)
  {

    $res = array(
      "message" => "notexist",
      "code" => "200"
    );
    $user = DB::TABLE('users')->where('email', $email)->count();

    if ($user > 0) {
      $res = array(
        "message" => "exist",
        "code" => "300"
      );
    }

    return response()->json($res);
  }

  public function generateContract(Request $request)
  {
    $type = $request->ncontrato;
    $id = $request->id;

    if($type == 'PYME'){
      $ncon = 'PM'.date('Ymd').$id.'1';
    }
    if($type == 'GRUPAL'){
      $ncon = 'GP'.date('Ymd').$id.'1';
    }
    if($type == 'INDIVIDUAL'){
      $ncon = 'ID'.date('Ymd').$id.'1';
    }
    if($type == 'NOMINA'){
      $ncon = 'NM'.date('Ymd').$id.'1';
    }
    $res = array(
      "message" => $ncon,
      "code" => "200"
    );

    return response()->json($res);
  }

  public function checkstate($state)
  {
    $state = strtoupper($state);
    $data = EntidadFederativa::whereRaw("UPPER(entity) = '$state'")->first();
    if(isset($data->code)){
      $res = array(
        "message" => "exist",
        "state" => $data->code,
        "code" => "200"
      );
    }else{
      $res = array(
        "message" => "notexist",
        "code" => "300"
      );
    }

    return response()->json($res);
  }

  public function checkcurp($curp)
  {
        try {
          $ccurp = DB::TABLE('clientes')->where('CURP', $curp)->FIRST();
          $unique = uniqid();
      
          if($ccurp)
          {
            $response['estatus'] = 'EXISTE';
            $response['mensaje'] = 'Este cliente ya se encuentra registrado';
            
            $this->logs('CONSULTA SUMA', 'ID:'.$unique.', TIPO: CURP, CURP: '.$curp.', ESTADO: CLIENTE EXISTENTE');
      
            return response()->json($response);
          }else{
      
            $this->logs('CONSULTA SUMA', 'ID:'.$unique.', TIPO: CURP, CURP: '.$curp.', ESTADO: VERIFICANDO');
      
            $curl = curl_init();
            $token = $this->gtoken();
      
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://veridocid.azure-api.net/api/gov/curp",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => "{\"curp\": \"$curp\",\n\t\"id\": \"$unique\" \n}",
              CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: text/plain",
                "Cookie: did=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; did_compat=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; ARRAffinity=8d69b90c05ecaa44b24e8b059b2b951042d72e06f694220099f034a811455a01"
              ),
            ));
      
            $response = curl_exec($curl);
            $error = curl_error($curl);
            curl_close($curl);
      
            $this->logs('CONSULTA SUMA', 'ID:'.$unique.', TIPO: CURP, CURP: '.$curp.', ESTADO: OK');
            
            if($error)
            {
              return $error;
            }
            return $response;
          }
            } catch (\Throwable $th) {
          return response()->json([$th->getMessage()], 400);
      }


  }


  public function gtoken()
  {
    $token = DB::TABLE('tokens')->where('company', 'veridocid')->first();

    return $token->token;
  }

  public function ineresult($data)
  {

    try {
      $curl = curl_init();
      $token = $this->gtoken();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://veridocid.azure-api.net/api/id/results",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n\t\"uuid\": \"$data\",\n\t\"includeImages\": false\n}",
        CURLOPT_HTTPHEADER => array(
          "Authorization: Bearer $token",
          "Content-Type: text/plain",
          "Cookie: did=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; did_compat=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; ARRAffinity=8d69b90c05ecaa44b24e8b059b2b951042d72e06f694220099f034a811455a01"
        ),
      ));
  
      $response = curl_exec($curl);
  
      curl_close($curl);
      return $response;
        }  catch (\Throwable $th) {
          $res = array(
            "message" => "fail",
            "code" => "400"
          );
    
      return response()->json($res, 200);
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

  public function checkine($id, $base64inefront, $base64ineback)
  {
    $curl = curl_init();
    $token = $this->gtoken();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://veridocid.azure-api.net/api/id/verify",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\n      \t\"id\": \"$id\",\n         \"frontImage\":\"$base64inefront\",\n         \"backImage\":\"$base64ineback\",\n \"faceImage\":\"\"\n }",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $token",
        "Content-Type: text/plain",
        "Cookie: did=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; did_compat=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; ARRAffinity=8d69b90c05ecaa44b24e8b059b2b951042d72e06f694220099f034a811455a01"
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }

  public function checkpasaporte($id, $basepasaportfront)
  {
    $curl = curl_init();
    $token = $this->gtoken();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://veridocid.azure-api.net/api/id/verify",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\n      \t\"id\": \"$id\",\n         \"frontImage\":\"$basepasaportfront\",\n  \"faceImage\":\"\"\n }",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $token",
        "Content-Type: text/plain",
        "Cookie: did=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; did_compat=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; ARRAffinity=8d69b90c05ecaa44b24e8b059b2b951042d72e06f694220099f034a811455a01"
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }


  function logs($tipo, $log){ 
      $date = date('Y-m-d');

      $flog = '../../storage/app/log-'.$date.'.log';

      if (file_exists($flog)) {
        $ddf = fopen('../storage/app/log-'.$date.'.log','w');
      } else {
        $ddf = fopen('../storage/app/log-'.$date.'.log','a');
      }


      fwrite($ddf,"[".$tipo."] " .date('H:i:s'). ": $log\r\n"); 
      fclose($ddf); 

      return 'ok';
   } 
   
}
