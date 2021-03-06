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


  public function imgto64(Request $request)
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
        $data = $this->checkine($unique, $base64inefront, $base64ineback);
        $status = '';
        $logs = new Logs();
        $logs->type = 'test';
        $logs->message = 'antes de status';
        $logs->controller = 'utilities';
        $logs->function = 'imgto64';
        $logs->save();

        while ($status == 'WaitingIdentification' or $status == 'WaitingChecked' or $status == '' or $status == 'WaitingChecking') {
          $status = $this->checkstatus($data);

          $logs = new Logs();
          $logs->type = 'test';
          $logs->message = $status . ' ' . $data;
          $logs->controller = 'utilities';
          $logs->function = 'imgto64';
          $logs->save();
        }

        $logs = new Logs();
        $logs->type = 'test';
        $logs->message = $status . ' ' . $data;
        $logs->controller = 'utilities';
        $logs->function = 'imgto64';
        $logs->save();

        if ($status == 'Checked') {
          return $this->ineresult($data);
        } else if ($status == "ManualChecking") {
          $res = array(
            "message" => "ManualChecking",
            "code" => "310"
          );
        } else if ($status == 'ControlListManualChecking') {
          $res = array(
            "message" => "ControlListManualChecking",
            "code" => "320",
            "data" => $this->ineresult($data)
          );
        }
      }
    }
    // $path = resource_path() . "/json/exampleResponse.json";

    // $res = json_decode(file_get_contents($path), true);
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

    if ($type == 'PYME') {
      $ncon = 'PM' . date('Ymd') . $id . '1';
    }
    if ($type == 'GRUPAL') {
      $ncon = 'GP' . date('Ymd') . $id . '1';
    }
    if ($type == 'INDIVIDUAL') {
      $ncon = 'ID' . date('Ymd') . $id . '1';
    }
    if ($type == 'NOMINA') {
      $ncon = 'NM' . date('Ymd') . $id . '1';
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
    if (isset($data->code)) {
      $res = array(
        "message" => "exist",
        "state" => $data->code,
        "code" => "200"
      );
    } else {
      $res = array(
        "message" => "notexist",
        "code" => "300"
      );
    }

    return response()->json($res);
  }

  public function checkcurp($curp)
  {

    $ccurp = DB::TABLE('clientes')->where('CURP', $curp)->FIRST();

    if ($ccurp) {

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
        CURLOPT_POSTFIELDS => "{\"curp\": \"$curp\",\n\t\"id\": \"id\" \n}",
        CURLOPT_HTTPHEADER => array(
          "Authorization: Bearer $token",
          "Content-Type: text/plain",
          "Cookie: did=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; did_compat=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; ARRAffinity=8d69b90c05ecaa44b24e8b059b2b951042d72e06f694220099f034a811455a01"
        ),
      ));

      $response = curl_exec($curl);
      $error = curl_error($curl);
      curl_close($curl);
      $response = json_decode($response, true);

      $response['estatus'] = 'EXISTE';
      $response['mensaje'] = 'Este cliente ya se encuentra registrado';

      return response()->json($response);
    } else {
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
        CURLOPT_POSTFIELDS => "{\"curp\": \"$curp\",\n\t\"id\": \"id\" \n}",
        CURLOPT_HTTPHEADER => array(
          "Authorization: Bearer $token",
          "Content-Type: text/plain",
          "Cookie: did=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; did_compat=s%3Av0%3A654617a0-e32c-11ea-bedb-a934c062229e.5uWIh8s5FfTFQuEMny1pTQ%2BET0D1720ookhRGRHcbm4; ARRAffinity=8d69b90c05ecaa44b24e8b059b2b951042d72e06f694220099f034a811455a01"
        ),
      ));

      $response = curl_exec($curl);
      $error = curl_error($curl);
      curl_close($curl);
      return $response;
    }

    //return $response;
  }


  public function gtoken()
  {
    $token = DB::TABLE('tokens')->where('company', 'veridocid')->first();

    return $token->token;
  }

  public function ineresult($data)
  {
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
}
