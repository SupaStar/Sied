<?php

namespace App\Http\Controllers;

use App\ActividadGiro;
use App\DestinoRecursos;
use App\Divisa;
use App\EFResidencia;
use App\EntidadFederativa;
use App\InstrumentoMonetario;
use App\NacionalidadAntecedentes;
use App\OrigenRecursos;
use App\PepExtranjeras;
use App\PepMexicanas;
use App\PerfilMoral;
use App\PersonalidadJuridica;
use App\Pld;
use App\Ponderacion;
use App\Profesion;
use App\AmortizacionMorales;
use App\CreditosMorales;
use App\HistorialFlujos;
use App\Riesgo;
use App\Riesgos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Files;
use Image;
use App\Client;
use App\Moral;
use App\ListaNegra;
use App\Perfil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use PDF;
use App\DestinoCredito;

class Morales extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('checkStatus');
  }

  public function morales()
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Personas Morales'
    ];

    return view('/morales/morales', [
      'pageConfigs' => $pageConfigs
    ]);
  }

  public function infocredito($id)
  {
    $result = CreditosMorales::where('morales_id', $id)->get();
    return datatables()->of($result)
      ->addColumn('vamortizacion', function ($query) {
        $ver = '<button onclick="table(null);" type="button" class="btn btn-flat-dark" style="position: relative;">
        Amortización
        </button>';
        return $ver;
      })
      ->rawColumns(['vamortizacion'])
    ->toJson();
  }


  public function info($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Personas Fisicas'
    ];

    $nacionalidades = db::table('nacionalidades')->get();
    $paises = db::table('paises')->get();
    $entidad = db::table('entidad_federativa')->get();
    $datos = Moral::where('id', '=', $id)->with('personasmorales')->first();

    $nacionalidadesantecedente = db::table('nacionalidad_antecedentes')->get();
    return view('/morales/info', [
      'pageConfigs' => $pageConfigs,
      'nacionalidades' => $nacionalidades,
      'paises' => $paises,
      'entidad' => $entidad,
      'datos' => $datos,
      'miid' => $id,
      'nacionantecedentes'=>$nacionalidadesantecedente,
      'id' => $id

    ]);
  }

  public function amortizacion(Request $request)
  {
    $result = array();
    $data = $request->data;

    if (!empty($data)) {
      $plazo = $data['plazoSliderInput'];
      $tinteres = $data['tinteres'];
      $monto = $data['sliderInput'];
      $frecuencia = $data['frecuencia'];
      $amortizaciones = $data['amortizaciones'];
      $forma = $data['fpago'];
      $disposicion = $data['disposicion'];
      $nuevafecha = '';
      $dias = '';
      $mdis = number_format($monto * -1, 2);
      $saldo = $monto;
      $comision = number_format($monto * 0.01, 2);
      $civa = $data['iva'];
      $intereses = 0;
      $amortizacion = 0;
      $iva = '';
      $flujo = 0;
      $addt = '';
      $add = 1;
      $sumintereses = 0;
      $sumiva = 0;
      $sumflujo = 0;
      if ($frecuencia == 'semanales') {
        $rplazo = round(abs($plazo * 4));
        $addt = 'week';
      }

      if ($frecuencia == 'quincenales') {
        $rplazo = round(abs($plazo * 2));
        $add = 15;
        $addt = 'days';
      }

      if ($frecuencia == 'menusales') {
        $rplazo = round(abs($plazo * 1));
        $addt = 'month';
      }

      if ($frecuencia == 'trimestrales') {
        $rplazo = round(abs($plazo / 3));
        $add = 3;
        $addt = 'month';
      }

      if ($frecuencia == 'semestrales') {
        $rplazo = round(abs($plazo / 3));
        $add = 6;
        $addt = 'month';
      }

      if ($frecuencia == 'anuales') {
        $rplazo = round(abs($plazo / 12));
        $addt = 'year';
      }

      $tp = ($tinteres / 100) / 12;
      $pp = ($tp * pow((1 + $tp), $rplazo)) * $monto / ((pow((1 + $tp), $rplazo)) - 1);
      $pp = ($tp * pow((1 + $tp), $rplazo)) * $monto / ((pow((1 + $tp), $rplazo)) - 1);
      //(A1*(1+A1)^B1)*C1/(((1+A1)^B1)-1)


      for ($i = 0; $i <= $rplazo; $i++) {
        if ($forma == 'VENCIMIENTO') {
          if ($i == 0) {
            $fecha = date('d/m/Y', strtotime($disposicion));
            if ($civa == 'SI') {
              $iva = ($intereses + $comision) * 0.16;
            }

            $flujo = ($monto * -1) + $comision + $amortizacion + $intereses + $iva;

          } else {
            $fecha1 = date_create($disposicion);

            $fecha = date('d/m/Y', strtotime($disposicion));

            $nuevafecha = strtotime('+' . $add . ' ' . $addt, strtotime($disposicion));
            $disposicion = date('Y-m-d', $nuevafecha);
            $nuevafecha = date('d/m/Y', $nuevafecha);

            $fecha2 = date_create($disposicion);

            $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));

            $intereses = ($saldo * ($tinteres / 100) / 360) * $dias;

            if ($rplazo == $i) {
              $amortizacion = $saldo;

              $saldo = 0;

            } else {
              $saldo = $saldo;
              $amortizacion = 0;
            }

            $mdis = '';
            $comision = '';


            if ($civa == 'SI') {
              $iva = $intereses * 0.16;
            }


            $flujo = $amortizacion + $intereses + $iva;

          }

          $sumintereses = $sumintereses + round($intereses);
          $sumiva = $sumiva + round($iva);
          if ($i > 1) {
            $sumflujo = $sumflujo + round($flujo);
          }

          $arr = array(
            'periodo' => $i,
            'fecha' => $fecha . ' - ' . $nuevafecha,
            'dias' => $dias,
            'disposición' => $mdis,
            'saldo' => number_format(round($saldo), 0),
            'comisión' => $comision,
            'amortización' => number_format(round($amortizacion), 0),
            'intereses' => number_format(round($intereses), 0),
            'moratorios' => '',
            'iva' => number_format(round($iva), 0),
            'flujo' => number_format(round($flujo), 0)
          );
          array_push($result, (object)$arr);

        } else {
          if ($amortizaciones == 'Pagos iguales') {
            if ($i == 0) {
              $fecha = date('d/m/Y', strtotime($disposicion));
              if ($civa == 'SI') {
                $iva = ($intereses + $comision) * 0.16;
              }

              $flujo = ($monto * -1) + $comision + $amortizacion + $intereses + $iva;

            } else {
              $fecha1 = date_create($disposicion);

              $fecha = date('d/m/Y', strtotime($disposicion));

              $nuevafecha = strtotime('+' . $add . ' ' . $addt, strtotime($disposicion));
              $disposicion = date('Y-m-d', $nuevafecha);
              $nuevafecha = date('d/m/Y', $nuevafecha);

              $fecha2 = date_create($disposicion);
              $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));
              $mdis = '';
              $comision = '';

              $intereses = (($saldo * ($tinteres / 100)) / 360) * 30;

              if ($civa == 'SI') {
                $iva = $intereses * 0.16;
              }

              $amortizacion = $pp - $intereses;

              $saldo = $saldo - $amortizacion;
              $flujo = $pp + $iva;

            }

            $arr = array(
              'periodo' => $i,
              'fecha' => $fecha . ' - ' . $nuevafecha,
              'dias' => $dias,
              'disposición' => $mdis,
              'saldo' => number_format(round($saldo), 0),
              'comisión' => $comision,
              'amortización' => number_format(round($amortizacion), 0),
              'intereses' => number_format(round($intereses), 0),
              'moratorios' => '',
              'iva' => number_format(round($iva), 0),
              'flujo' => number_format(round($flujo), 0)
            );
            array_push($result, (object)$arr);
          } elseif ($amortizaciones == 'Amortizaciones iguales') {
            if ($i == 0) {
              $fecha = date('d/m/Y', strtotime($disposicion));
              if ($civa == 'SI') {
                $iva = ($intereses + $comision) * 0.16;
              }

              $flujo = ($monto * -1) + $comision + $amortizacion + $intereses + $iva;

            } else {
              $fecha1 = date_create($disposicion);

              $fecha = date('d/m/Y', strtotime($disposicion));

              $nuevafecha = strtotime('+' . $add . ' ' . $addt, strtotime($disposicion));
              $disposicion = date('Y-m-d', $nuevafecha);
              $nuevafecha = date('d/m/Y', $nuevafecha);

              $fecha2 = date_create($disposicion);
              $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));
              $mdis = '';
              $comision = '';

              $intereses = $saldo * ($tinteres / 100) / 360 * $dias;

              if ($civa == 'SI') {
                $iva = $intereses * 0.16;
              }

              $amortizacion = ($monto / $rplazo);


              $saldo = $saldo - $amortizacion;
              $flujo = $amortizacion + $intereses + $iva;

            }

            $arr = array(
              'periodo' => $i,
              'fecha' => $fecha . ' - ' . $nuevafecha,
              'dias' => $dias,
              'disposición' => $mdis,
              'saldo' => number_format(round($saldo), 0),
              'comisión' => $comision,
              'amortización' => number_format(round($amortizacion), 0),
              'intereses' => number_format(round($intereses), 0),
              'moratorios' => '',
              'iva' => number_format(round($iva), 0),
              'flujo' => number_format(round($flujo), 0)
            );
            array_push($result, (object)$arr);

          }
        }
      }

      if ($forma == 'VENCIMIENTO') {
        $arr = array(
          'periodo' => 'Totales',
          'fecha' => '',
          'dias' => '',
          'disposición' => '',
          'saldo' => '',
          'comisión' => '',
          'amortización' => number_format(round($monto), 0),
          'intereses' => number_format(round($sumintereses), 0),
          'moratorios' => '',
          'iva' => number_format(round($sumiva), 0),
          'flujo' => number_format(round($sumflujo), 0)
        );
        array_push($result, (object)$arr);
      }
    }

    return datatables()->of($result)
      /* ->addColumn('names', function ($query) {
         return strtoupper($query->name . ' ' . $query->lastname . ' ' . $query->o_lastname);
       })
       ->rawColumns(['actions', 'names'])*/
      ->toJson();

  }

  public function infoamortizacion($id)
  {
    $result = array();

    $data = CreditosMorales::where('morales_id', $id)->first();
    $hoy = date('Y-m-d');

    if (!empty($data)) {

      $amortizacion = AmortizacionMorales::where('cliente_id', $id)->where('credito_id', $data->id)->orderBy('id', 'asc')->get();

      if (!empty(count($amortizacion))) {
        foreach ($amortizacion as $gdata) {
          if ($gdata->liquidado == 0) {
            $fecha1 = date_create(date('Y-m-d'));
            $fecha2 = date_create($gdata->fin);
            $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));
            $tasa = (CreditosMorales::where('id', $gdata->credito_id)->first()->tasa) / 100;

            if ($dias < 0 && $gdata->dia_mora != $hoy) {
              $dias = abs($dias);
              $intmora = ((($gdata->amortizacion * $tasa) * 2) / 360) * $dias;
              $ivamora = $intmora * 0.16;
              $moratorios = number_format($intmora, 2) + number_format($ivamora, 2);
              $lgcobranza = $gdata->gcobranza ? $gdata->gcobranza : 0;
              $gcobranza = 200;
              if (empty($lgcobranza)) {
                $nflujo = $gdata->amortizacion + $gdata->intereses + $gdata->iva + $moratorios + $gcobranza;

                $mflujo = $gdata->amortizacion + $gdata->intereses + $gdata->iva + $gcobranza;

                $nhistorialflujo = new HistorialFlujos;
                $nhistorialflujo->periodo_id = $gdata->id;
                $nhistorialflujo->monto = $mflujo;
                $nhistorialflujo->cambio = $gcobranza;
                $nhistorialflujo->descripcion = 'Gastos De Cobranza';
                $nhistorialflujo->save();

              } else {
                $nflujo = $gdata->flujo + $moratorios;
              }

              AmortizacionMorales::where('id', $gdata->id)->update(['flujo' => $nflujo, 'dias_mora' => $dias, 'int_mora' => $intmora, 'iva_mora' => $ivamora, 'gcobranza' => $gcobranza, 'dia_mora' => $hoy]);

              HistorialFlujos::where('periodo_id', $gdata->id)->where('descripcion', 'Gastos Moratorios')->delete();
              $nhistorialflujo = new HistorialFlujos;
              $nhistorialflujo->periodo_id = $gdata->id;
              $nhistorialflujo->monto = $nflujo;
              $nhistorialflujo->cambio = $moratorios;
              $nhistorialflujo->descripcion = 'Gastos Moratorios (Interes mora: $' . number_format($intmora, 2) . ' + Iva mora: $' . number_format($ivamora, 2) . ')';
              $nhistorialflujo->save();
            }
          }
        }

        $amortizacion = AmortizacionMorales::selectRaw("
              id,
              cliente_id,
              credito_id,
              periodo,
              fechas,
              inicio,
              fin,
              dias,
              format(disposicion,2) as disposicion,
              format(saldo_insoluto,2) as saldo_insoluto,
              format(comision,2) as comision,
              format(amortizacion,2) as amortizacion,
              format(intereses,2) as intereses,
              format(moratorios,2) as moratorios,
              format(iva,2) as iva,
              format(gcobranza,2) as gcobranza,
              format(int_mora,2) as int_mora,
              format(iva_mora,2) as iva_mora,
              pagos,
              liquidado,
              flujo,
              dias_mora"
        )->where('cliente_id', $id)->where('credito_id', $data->id)->orderBy('id', 'asc')->get();
        $result = $amortizacion;
      } else {

        $plazo = $data->plazo;
        $tinteres = $data->tasa;
        $monto = $data->monto;
        $frecuencia = $data->frecuencia;
        $amortizaciones = $data->amortizacion;
        $forma = $data->fpago;
        $disposicion = $data->disposicion;
        $nuevafecha = '';
        $dias = '';
        $mdis = number_format($monto * -1, 2);
        $saldo = $monto;
        $comision = number_format($monto * 0.01, 2);
        $civa = $data->iva;
        $intereses = 0;
        $amortizacion = 0;
        $iva = '';
        $flujo = 0;
        $addt = '';
        $add = 1;
        $sumintereses = 0;
        $sumiva = 0;
        $sumflujo = 0;
        $cid = $data->id;

        if ($frecuencia == 'semanales') {
          $rplazo = round(abs($plazo * 4));
          $addt = 'week';
        }

        if ($frecuencia == 'quincenales') {
          $rplazo = round(abs($plazo * 2));
          $add = 15;
          $addt = 'days';
        }

        if ($frecuencia == 'menusales') {
          $rplazo = round(abs($plazo * 1));
          $addt = 'month';
        }

        if ($frecuencia == 'trimestrales') {
          $rplazo = round(abs($plazo / 3));
          $add = 3;
          $addt = 'month';
        }

        if ($frecuencia == 'semestrales') {
          $rplazo = round(abs($plazo / 3));
          $add = 6;
          $addt = 'month';
        }

        if ($frecuencia == 'anuales') {
          $rplazo = round(abs($plazo / 12));
          $addt = 'year';
        }

        $tp = ($tinteres / 100) / 12;
        $pp = ($tp * pow((1 + $tp), $rplazo)) * $monto / ((pow((1 + $tp), $rplazo)) - 1);
        $pp = ($tp * pow((1 + $tp), $rplazo)) * $monto / ((pow((1 + $tp), $rplazo)) - 1);

        for ($i = 0; $i <= $rplazo; $i++) {
          if ($forma == 'VENCIMIENTO') {
            if ($i == 0) {
              $fecha = date('d/m/Y', strtotime($disposicion));
              if ($civa == 'SI') {
                $iva = ($intereses + $comision) * 0.16;
              }

              $flujo = ($monto * -1) + $comision + $amortizacion + $intereses + $iva;

            } else {
              $fecha1 = date_create($disposicion);

              $fecha = date('d/m/Y', strtotime($disposicion));

              $nuevafecha = strtotime('+' . $add . ' ' . $addt, strtotime($disposicion));
              $disposicion = date('Y-m-d', $nuevafecha);
              $nuevafecha = date('d/m/Y', $nuevafecha);

              $fecha2 = date_create($disposicion);

              $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));

              $intereses = ($saldo * ($tinteres / 100) / 360) * $dias;

              if ($rplazo == $i) {
                $amortizacion = $saldo;

                $saldo = 0;

              } else {
                $saldo = $saldo;
                $amortizacion = 0;
              }

              $mdis = '';
              $comision = '';


              if ($civa == 'SI') {
                $iva = $intereses * 0.16;
              }


              $flujo = $amortizacion + $intereses + $iva;

            }

            $sumintereses = $sumintereses + round($intereses);
            $sumiva = $sumiva + round($iva);
            if ($i > 1) {
              $sumflujo = $sumflujo + round($flujo);
            }


            $arr = array(
              'periodo' => $i,
              'fecha' => $fecha . ' - ' . $nuevafecha,
              'inicio' => $fecha,
              'fin' => $nuevafecha,
              'dias' => $dias,
              'disposicion' => $mdis,
              'saldo' => number_format(round($saldo), 0),
              'comision' => $comision,
              'amortizacion' => number_format(round($amortizacion), 0),
              'intereses' => number_format(round($intereses), 0),
              'moratorios' => '',
              'iva' => number_format(round($iva), 0),
              'flujo' => number_format(round($flujo), 0)
            );
            array_push($result, (object)$arr);

          } else {
            if ($amortizaciones == 'Pagos iguales') {
              $npp = 0;

              if ($i == 0) {
                $fecha = date('d/m/Y', strtotime($disposicion));
                if ($civa == 'SI') {
                  $iva = ($intereses + $comision) * 0.16;
                }

                $flujo = ($monto * -1) + $comision + $amortizacion + $intereses + $iva;

                $arr = array(
                  'periodo' => $i,
                  'fecha' => $fecha . ' - ' . $nuevafecha,
                  'inicio' => $fecha,
                  'fin' => $nuevafecha,
                  'dias' => $dias,
                  'disposicion' => $mdis,
                  'saldo' => number_format(round($saldo), 0),
                  'comision' => $comision,
                  'amortizacion' => number_format(round($amortizacion), 0),
                  'intereses' => number_format(round($intereses), 0),
                  'moratorios' => '',
                  'iva' => number_format(round($iva), 0),
                  'flujo' => number_format(round($flujo), 0)
                );
                array_push($result, (object)$arr);

              } else {
                $fecha1 = date_create($disposicion);

                $fecha = date('d/m/Y', strtotime($disposicion));
                $d1 = date('Y-m-d', strtotime($disposicion));

                $nuevafecha = strtotime('+' . $add . ' ' . $addt, strtotime($disposicion));
                $disposicion = date('Y-m-d', $nuevafecha);
                $d2 = date('Y-m-d', $nuevafecha);
                $nuevafecha = date('d/m/Y', $nuevafecha);

                $fecha2 = date_create($disposicion);
                $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));
                $mdis = '';
                $comision = '';

                $intereses = (($saldo * ($tinteres / 100)) / 360) * 30;

                if ($civa == 'SI') {
                  $iva = $intereses * 0.16;
                }

                $amortizacion = $pp - $intereses;

                $saldo = $saldo - $amortizacion;
                $flujo = $pp + $iva;

                if (round($npp) < round($flujo)) {
                  $flujo = ($pp - $npp) + $iva;

                  $arr = array(
                    'periodo' => $i,
                    'fecha' => $fecha . ' - ' . $nuevafecha,
                    'inicio' => $fecha,
                    'fin' => $nuevafecha,
                    'dias' => $dias,
                    'disposicion' => $mdis,
                    'saldo' => number_format(round($saldo), 0),
                    'comision' => $comision,
                    'amortizacion' => number_format(round($amortizacion), 0),
                    'intereses' => number_format(round($intereses), 0),
                    'moratorios' => '',
                    'iva' => number_format(round($iva), 0),
                    'flujo' => number_format(round($flujo), 0)
                  );
                  array_push($result, (object)$arr);

                }

              }


            } elseif ($amortizaciones == 'Amortizaciones iguales') {
              if ($i == 0) {
                $fecha = date('d/m/Y', strtotime($disposicion));
                if ($civa == 'SI') {
                  $iva = ($intereses + $comision) * 0.16;
                }

                $flujo = ($monto * -1) + $comision + $amortizacion + $intereses + $iva;

              } else {
                $fecha1 = date_create($disposicion);

                $fecha = date('d/m/Y', strtotime($disposicion));

                $nuevafecha = strtotime('+' . $add . ' ' . $addt, strtotime($disposicion));
                $disposicion = date('Y-m-d', $nuevafecha);
                $nuevafecha = date('d/m/Y', $nuevafecha);

                $fecha2 = date_create($disposicion);
                $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));
                $mdis = '';
                $comision = '';

                $intereses = $saldo * ($tinteres / 100) / 360 * $dias;

                if ($civa == 'SI') {
                  $iva = $intereses * 0.16;
                }

                $amortizacion = ($monto / $rplazo);


                $saldo = $saldo - $amortizacion;
                $flujo = $amortizacion + $intereses + $iva;

              }

              $arr = array(
                'periodo' => $i,
                'fecha' => $fecha . ' - ' . $nuevafecha,
                'inicio' => $fecha,
                'fin' => $nuevafecha,
                'dias' => $dias,
                'disposicion' => $mdis,
                'saldo' => number_format(round($saldo), 0),
                'comision' => $comision,
                'amortizacion' => number_format(round($amortizacion), 0),
                'intereses' => number_format(round($intereses), 0),
                'moratorios' => '',
                'iva' => number_format(round($iva), 0),
                'flujo' => number_format(round($flujo), 0)
              );
              array_push($result, (object)$arr);

            }
          }
        }

        if ($forma == 'VENCIMIENTO') {
          $arr = array(
            'periodo' => 'Totales',
            'fecha' => '',
            'inicio' => '',
            'fin' => '',
            'dias' => '',
            'disposicion' => '',
            'saldo' => '',
            'comision' => '',
            'amortizacion' => number_format(round($monto), 0),
            'intereses' => number_format(round($sumintereses), 0),
            'moratorios' => '',
            'iva' => number_format(round($sumiva), 0),
            'flujo' => number_format(round($sumflujo), 0)
          );
          array_push($result, (object)$arr);
        }

        foreach ($result as $key) {
          $amm = new AmortizacionMorales;
          $amm->cliente_id = $id;
          $amm->credito_id = $data->id;
          $amm->periodo = $key->periodo;
          $amm->fechas = $key->fecha;
          $amm->inicio = $key->inicio ? date('Y-m-d', strtotime(substr($key->inicio, 6, 4) . '-' . substr($key->inicio, 3, 2) . '-' . substr($key->inicio, 0, 2))) : null;
          $amm->fin = $key->fin ? date('Y-m-d', strtotime(substr($key->fin, 6, 4) . '-' . substr($key->fin, 3, 2) . '-' . substr($key->fin, 0, 2))) : null;
          $amm->dias = $key->dias ? $key->dias : null;
          $amm->disposicion = $key->disposicion ? str_replace(',', '', $key->disposicion) : null;
          $amm->saldo_insoluto = $key->saldo ? str_replace(',', '', $key->saldo) : null;
          $amm->comision = $key->comision ? str_replace(',', '', $key->comision) : null;
          $amm->amortizacion = $key->amortizacion ? str_replace(',', '', $key->amortizacion) : null;
          $amm->intereses = $key->intereses ? str_replace(',', '', $key->intereses) : null;;
          $amm->moratorios = $key->moratorios ? str_replace(',', '', $key->moratorios) : null;
          $amm->iva = $key->iva;
          $amm->flujo = $key->flujo ? str_replace(',', '', $key->flujo) : null;
          $amm->dias_mora = 0;
          $amm->save();

          $nhistorialflujo = new HistorialFlujos;
          $nhistorialflujo->periodo_id = $amm->id;
          $nhistorialflujo->monto = $key->flujo ? str_replace(',', '', $key->flujo) : null;
          $nhistorialflujo->cambio = $key->flujo ? str_replace(',', '', $key->flujo) : null;
          $nhistorialflujo->descripcion = 'Flujo Original De Amortización';
          $nhistorialflujo->save();

        }


        $amortizacion = AmortizacionMorales::where('cliente_id', $id)->where('credito_id', $data->id)->orderBy('id', 'asc')->get();
        foreach ($amortizacion as $gdata) {
          if ($gdata->liquidado == 0) {
            $fecha1 = date_create(date('Y-m-d'));
            $fecha2 = date_create($gdata->fin);
            $dias = str_replace('+', '', date_diff($fecha1, $fecha2)->format('%R%a'));
            $tasa = (CreditosMorales::where('id', $gdata->credito_id)->first()->tasa) / 100;

            if ($dias < 0 && $gdata->dia_mora != $hoy) {
              $dias = abs($dias);
              $intmora = ((($gdata->amortizacion * $tasa) * 2) / 360) * $dias;
              $ivamora = $intmora * 0.16;
              $moratorios = number_format($intmora, 2) + number_format($ivamora, 2);
              $lgcobranza = $gdata->gcobranza ? $gdata->gcobranza : 0;
              $gcobranza = 200;
              if (empty($lgcobranza)) {
                $nflujo = $gdata->amortizacion + $gdata->intereses + $gdata->iva + $moratorios + $gcobranza;

                $mflujo = $gdata->amortizacion + $gdata->intereses + $gdata->iva + $gcobranza;

                $nhistorialflujo = new HistorialFlujos;
                $nhistorialflujo->periodo_id = $gdata->id;
                $nhistorialflujo->monto = $mflujo;
                $nhistorialflujo->cambio = $gcobranza;
                $nhistorialflujo->descripcion = 'Gastos De Cobranza';
                $nhistorialflujo->save();


              } else {
                $nflujo = $gdata->flujo + $moratorios;
              }
              AmortizacionMorales::where('id', $gdata->id)->update(['flujo' => $nflujo, 'dias_mora' => $dias, 'int_mora' => $intmora, 'iva_mora' => $ivamora, 'gcobranza' => $gcobranza, 'dia_mora' => $hoy]);

              HistorialFlujos::where('periodo_id', $gdata->id)->where('descripcion', 'Gastos Moratorios')->delete();
              $nhistorialflujo = new HistorialFlujos;
              $nhistorialflujo->periodo_id = $gdata->id;
              $nhistorialflujo->monto = $nflujo;
              $nhistorialflujo->cambio = $moratorios;
              $nhistorialflujo->descripcion = 'Gastos Moratorios (Interes mora: $' . number_format($intmora, 2) . ' + Iva mora: $' . number_format($ivamora, 2) . ')';
              $nhistorialflujo->save();

            }
          }
        }
        $amortizacion = AmortizacionMorales::selectRaw("
              id,
              cliente_id,
              credito_id,
              periodo,
              fechas,
              inicio,
              fin,
              dias,
              format(disposicion,2) as disposicion,
              format(saldo_insoluto,2) as saldo_insoluto,
              format(comision,2) as comision,
              format(amortizacion,2) as amortizacion,
              format(intereses,2) as intereses,
              format(moratorios,2) as moratorios,
              format(iva,2) as iva,
              format(gcobranza,2) as gcobranza,
              format(int_mora,2) as int_mora,
              format(iva_mora,2) as iva_mora,
              pagos,
              liquidado,
              flujo,
              dias_mora"
        )->where('cliente_id', $id)->where('credito_id', $data->id)->orderBy('id', 'asc')->get();
        $result = $amortizacion;


      }

    }

    return datatables()->of($result)
      ->addColumn('saldo_pendiente', function ($query) {

        if ($query->flujo > 0) {
          $pagos = $query->pagos ? $query->pagos : 0;

          $pendiente = number_format($query->flujo - $pagos, 2);
        } else {
          $pendiente = '';
        }

        return $pendiente;
      })
      ->addColumn('pagos', function ($query) {

        $pagos = $query->pagos ? $query->pagos : 0;
        $pagos = number_format($pagos, 2);
        if ($pagos > 0) {
          $pagos = '<button onclick="verpagos(' . $query->id . ');" type="button" class="btn btn-flat-dark" style="position: relative;">
                    $' . $pagos . '
                    </button>';
        } else {
          $pagos = '<button type="button" class="btn btn-flat-dark" style="position: relative;">
                    $0.00
                    </button>';
        }
        return $pagos;
      })
      ->addColumn('flujos', function ($query) {

        $flujo = number_format($query->flujo, 2);

        $bflujo = '<button onclick="verflujos(' . $query->id . ');" type="button" class="btn btn-flat-dark" style="position: relative;">
      $' . $flujo . '
      </button>';

        return $bflujo;
      })
      ->addColumn('cflujos', function ($query) {

        $flujo = number_format($query->flujo, 2);


        return $flujo;
      })
      ->addColumn('cstatus', function ($query) {
        if ($query->liquidado == 1 || $query->flujo < 0) {
          $status = 1;
        } elseif (strtotime(date('Y-m-d')) > strtotime($query->fin)) {
          $status = 2;

          $carbon = new Carbon();
          $to = $carbon::createFromFormat('Y-m-d', $query->fin);
          $from = $carbon->now();
          $diff = $to->diffInMonths($from);

          if ($diff > 0) {
            $status = 3;
          }

        } else {
          $status = 0;
        }
        return $status;
      })
      ->rawColumns(['pagos', 'cstatus', 'saldo_pendiente', 'flujos', 'cflujos'])
      ->toJson();

  }

  

  public function fperfil($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Personas Fisicas'
    ];
    $datos = PerfilMoral::where('id_moral', '=', $id)->first();

    if (isset($datos)) {
      return view('/clients/perfil', compact(
        'pageConfigs',
        'id',
        'datos'
      ));
    } else {
      return view('/clients/perfil', [
        'pageConfigs' => $pageConfigs,
        'id' => $id,

      ]);
    }
  }

  public function friesgo($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Personas Fisicas'
    ];
    $moral = Moral::find($id);
    $riesgo = Riesgo::orderby('id', 'asc')->get();
    $pld = Pld::orderby('id', 'asc')->get();
    $pepmx = PepMexicanas::orderby('id', 'asc')->get();
    $pepex = PepExtranjeras::orderby('id', 'asc')->get();
    $personalidad = PersonalidadJuridica::orderby('id', 'asc')->get();
    $anacionalidad = NacionalidadAntecedentes::orderby('id', 'asc')->get();
    $entidad = EFResidencia::orderby('id', 'asc')->get();
    $antiguedad = $moral->antiguedades();
    $origen = OrigenRecursos::orderby('id', 'asc')->get();
    $destino = DestinoRecursos::orderby('id', 'asc')->get();
    $imonetario = InstrumentoMonetario::orderby('id', 'asc')->get();
    $divisa = Divisa::orderby('id', 'asc')->get();
    $ponderaciones = Ponderacion::orderby('id', 'asc')->get();
    $actividad = ActividadGiro::orderby('id', 'asc')->get();
    $profesion = Profesion::orderby('id', 'asc')->get();
    $riesgos = array();
    foreach ($riesgo as $value) {
      switch ($value->riesgo) {
        case 'BAJO':
          $riesgos['BAJO'] = $value->maximo;
          break;
        case 'MEDIO':
          $riesgos['MEDIO'] = $value->maximo;
          break;
        default:
          // code...
          break;
      }
    }
    $actEconomica = $moral->actividad_profesion();
    $origenR = $moral->origen_recursos();
    $destinoR = $moral->destino_recursos();
    $antecedentes = $moral->llenarAntecedentes();
    $valorAntecedentes = 0;
    $sumaAntecedentes = 0;
    foreach ($antecedentes as $item) {
      $valorAntecedentes += $item['puntaje'] * $item['ponderacion'] / 100;
      $sumaAntecedentes = $item['puntaje'];
    }
    $valorActEconomica = 0;
    $sumaActEconomica = 0;
    foreach ($actEconomica as $item) {
      $valorActEconomica += $item['puntaje'] * $item['ponderacion'] / 100;
      $sumaActEconomica = $item['puntaje'];
    }
    $valorOrigenRecursos = 0;
    $sumaOrigenRecursos = 0;
    foreach ($origenR as $item) {
      $valorOrigenRecursos += $item['puntaje'] * $item['ponderacion'] / 100;
      $sumaOrigenRecursos = $item['puntaje'];
    }
    $valorDestino = $destinoR[0]['puntaje'] * 1;
    $sumatoria = $valorAntecedentes + $valorActEconomica + $valorOrigenRecursos + $valorDestino;
    $valorRes = ($valorAntecedentes * .5) + ($valorActEconomica * .17) + ($valorOrigenRecursos * .25) + ($valorDestino * .08);
    if ($valorRes < $riesgos['BAJO']) {
      $criesgo = 'BAJO';
    } elseif ($valorRes < $riesgos['MEDIO']) {
      $criesgo = 'MEDIO';
    } else {
      $criesgo = 'ALTO';
    }
    return view('/morales/riesgo', compact(
      'pageConfigs', 'actEconomica', 'origenR', 'destinoR', 'valorRes', 'valorAntecedentes', 'valorActEconomica',
      'valorOrigenRecursos', 'valorDestino', 'criesgo', 'riesgo', 'sumatoria', 'ponderaciones',
      'sumaAntecedentes', 'antecedentes', 'pld', 'pepmx', 'pepex', 'anacionalidad', 'antiguedad', 'personalidad',
      'entidad', 'sumaActEconomica', 'profesion', 'actividad', 'sumaOrigenRecursos', 'origen', 'imonetario', 'divisa', 'destino'
    ));
  }

  public function getmorales(Request $request)
  {

    $result = Moral::with('personasMorales');


    return datatables()->of($result)
      ->addColumn('socios', function ($query) {
        return count($query->personasMorales);
      })
      ->addColumn('blacklist', function ($query) {
        $result = '<button title="Listas Negras" onclick="noblacklist()" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-check-circle success"></i></button>';

        return $result;
      })
      ->addColumn('status', function ($query) {

        $perfil = DB::TABLE('perfil_transacional_moral')->where('id_moral', $query->id)->first();
        $credito = DB::TABLE('credito_morales')->where('morales_id', $query->id)->where('status', '<>', 'liquidado')->first();
        $text = " - ";

        if (isset($perfil)) {

          if (empty($perfil->monto) || empty($perfil->tcredito) || empty($perfil->frecuencia) || empty($perfil->instrumento_monetario) || empty($perfil->origen_recursos) || empty($perfil->destino_recursos) || empty($perfil->divisas)) {
            $text = 'Pendiente <br> <a href="/morales/perfil/' . $query->id . '" class="warning">Perfil Transacional</a>';
          } else if (empty($perfil->profesion) || empty($perfil->actividad_giro) || empty($perfil->efr)) {
            $text = 'Pendiente <br> <a href="/morales/morales/ebr/' . $query->id . '" class="warning">Criterios de Riesgo</a>';

          } else if (isset($credito)) {
            $text = 'Aprobado <br> <a href="/morales/info/' . $query->id . '" class="warning">Información</a>';
          } else {
            $text = 'Pendiente <br> <a href="/morales/continuar/' . $query->id . '" class="warning">Credito</a>';
          }
        } else {
          $text = 'Pendiente <br> <a href="/morales/perfil/' . $query->id . '" class="warning">Perfil Transacional</a>';
        }

        return $text;

      })
      ->addColumn('actions', function ($query) {
        $user = Auth::user();
        return '
              <a href="/morales/info/' . $query->id . '" title="Información"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-eye"></i></button></a>
              <a href="/morales/continuar/' . $query->id . '" title="Crédito"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-dollar-sign warning"></i></button></a>
              <a href="/morales/perfil/' . $query->id . '" title="Perfil Transacional"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-file success"></i></button></a>
              <a href="morales/ebr/' . $query->id . '" title="Criterios de Riesgos"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-info info"></i></button></a>
              <a href="/morales/riesgo/' . $query->id . '" title="Grado de Riesgo"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-bar-chart-2 warning"></i></button></a>
              <button title="Descarga Archivos" onclick="files(' . $query->id . ');" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-archive primary"></i></button>
              <a href="/morales/editarmoral/' . $query->id . '" title="Editar"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
              <button title="Archivar" onclick="del(' . $query->id . ');" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-trash danger"></i></button>';
      })
      ->rawColumns(['actions', 'socios', 'status', 'blacklist'])
      ->toJson();

    // if ($request->filtro == 'Archivados') {
    //   $result = DB::table('clientes')->where('status', 'Archivado');
    // } elseif ($request->filtro == 'H') {
    //   $result = DB::table('clientes')->where('gender', 'H');
    // } elseif ($request->filtro == 'M') {
    //   $result = DB::table('clientes')->where('gender', 'M');
    // } else {
    //   $result = DB::table('clientes')->where('status', '<>', 'Archivado');;
    // }

    // return datatables()->of($result)
    //   ->addColumn('names', function ($query) {
    //     return strtoupper($query->name . ' ' . $query->lastname . ' ' . $query->o_lastname);
    //   })

    //   ->addColumn('actions', function ($query) {
    //     $user = Auth::user();
    //     return '
    //           <a href="/clientes/fisicas/info/' . $query->id . '" title="Información"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-eye"></i></button></a>
    //           <a href="/clientes/fisicas/perfil/' . $query->id . '" title="Perfil Transacional"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-file success"></i></button></a>
    //           <a href="/clientes/fisicas/riesgo/' . $query->id . '" title="Grado de Riesgo"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-bar-chart-2 warning"></i></button></a>
    //           <button title="Descarga Archivos" onclick="files(' . $query->id . ');" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-archive primary"></i></button>
    //           <a href="/clientes/fisicas/editar/' . $query->id . '" title="Editar"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
    //           <button title="Archivar" onclick="del(' . $query->id . ');" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-trash danger"></i></button>';
    //   })
    //   ->rawColumns(['actions', 'names'])
    //   ->toJson();
  }


  public function eperfil(Request $request)
  {
    $cid = $request->id;

    $user = Auth::user();
    $args = array(
      'divisas' => $request->divisa,
      'monto' => $request->monto,
      'tcredito' => $request->tcredito,
      'frecuencia' => $request->frecuencia,
      'origen_recursos' => $request->orecursos,
      'destino_recursos' => $request->drecursos,
      'instrumento_monetario' => $request->imonetario,
      'actividad' => false,
      'propietario' => false,
      'proovedor' => false,
      'dactividad' => false,
      'dpasivos' => false,
      'dotro' => false,
      'total' => false,
      'aceptable' => false,
      'dificil' => false,
      'ingreso' => $request->ingreso ? $request->ingreso : null,
      'conducta' => $request->conducta,
      'comentario' => $request->comentario,
    );

    switch ($request->actividad) {
      case 'actividad':
        $args['actividad'] = true;
        break;
      case 'propietario':
        $args['propietario'] = true;
        break;
      case 'proovedor':
        $args['proovedor'] = true;
        break;
    }

    switch ($request->dactividad) {
      case 'dactividad':
        $args['dactividad'] = true;
        break;
      case 'dpasivos':
        $args['dpasivos'] = true;
        break;
      case 'dotro':
        $args['dotro'] = $request->dotro;
        break;
    }

    switch ($request->disponibilidad) {
      case 'total':
        $args['total'] = true;
        break;
      case 'aceptable':
        $args['aceptable'] = true;
        break;
      case 'dificil':
        $args['dificil'] = true;
        break;
    }


    $fields = array(
      'id_moral' => $cid
    );

    $update = PerfilMoral::updateOrCreate($fields, $args);
    $riesgos = new Riesgos();
    $a = $riesgos->gradoMorales($cid);
    return redirect('/morales/morales')->with('message', 'OK');
  }

  public function editado(Request $request)
  {

    $bith = substr($request->fnacimiento, 6, 4) . '-' . substr($request->fnacimiento, 0, 2) . '-' . substr($request->fnacimiento, 3, 2);
    $dd = date('Y-m-d', strtotime($bith));
    $cid = $request->id;
    if ($request->id = !null) {
      $cliente = Client::find($request->id);
    } else {
      $cliente = new Client;
    }

    $user = Auth::user();

    $cliente->name = strtoupper($request->nombre);
    $cliente->lastname = strtoupper($request->apellidop);
    $cliente->o_lastname = strtoupper($request->apellidom);
    $cliente->gender = strtoupper($request->genero);
    $cliente->date_birth = $dd;
    $cliente->country_birth = $request->pais_nacimiento;
    $cliente->nationality = strtoupper($request->lnacimiento);
    $cliente->place_birth = strtoupper($request->nacionalidad);
    $cliente->job = strtoupper($request->ocupacion);
    $cliente->street = strtoupper($request->calle);
    $cliente->exterior = $request->exterior;
    $cliente->inside = $request->interior;
    $cliente->pc = $request->cp;
    $cliente->colony = strtoupper($request->colonia);
    $cliente->town = strtoupper($request->municipio);
    $cliente->city = strtoupper($request->ciudad);
    $cliente->ef = strtoupper($request->entidad);
    $cliente->country = strtoupper($request->pais);
    $cliente->phone1 = $request->telefono1;
    $cliente->phone2 = $request->telefono2;
    $cliente->email = strtoupper($request->memail);
    $cliente->curp = strtoupper($request->curp);
    $cliente->rfc = strtoupper($request->rfc);
    $cliente->c_name = strtoupper($request->cnombre);
    $cliente->c_lastname = strtoupper($request->capellidop);
    $cliente->c_o_lastname = strtoupper($request->capellidom);
    $cliente->c_phone = $request->ctelefono;
    $cliente->c_email = strtoupper($request->cemail);
    $cliente->save();

    $fileine = $request->file('inefront') ? $request->file('inefront') : 1;
    $ineback = $request->file('ineback') ? $request->file('ineback') : 1;
    $filecurp = $request->file('filecurp') ? $request->file('filecurp') : 1;
    $filedom = $request->file('filedom') ? $request->file('filedom') : 1;
    $filecom1 = $request->file('filecom1') ? $request->file('filecom1') : 1;
    $filecom2 = $request->file('filecom2') ? $request->file('filecom2') : 1;
    $filecom3 = $request->file('filecom3') ? $request->file('filecom3') : 1;
    $filerfc = $request->file('filerfc') ? $request->file('filerfc') : 1;

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
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });

        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
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

        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
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

    if ($filecurp != 1) {
      $path = 'fisicas/curp';
      $extension = strtolower($filecurp->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'CURP';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filecurp));
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        })->insert(public_path('images/confidencial.png'), 'center');
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'CURP';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filecurp));
      }
    }

    if ($filedom != 1) {
      $path = 'fisicas/dom';
      $extension = strtolower($filedom->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'COMPROBANTE DOMICILIO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filedom));
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'COMPROBANTE DOMICILIO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filedom));
      }
    }

    if ($filecom1 != 1) {
      $path = 'fisicas/com1';
      $extension = strtolower($filecom1->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filecom1));
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filecom1));
      }
    }

    if ($filecom2 != 1) {
      $path = 'fisicas/com2';
      $extension = strtolower($filecom2->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = '2 COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filecom2));
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = '2 COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filecom2));
      }
    }

    if ($filecom3 != 1) {
      $path = 'fisicas/com3';
      $extension = strtolower($filecom3->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = '3 COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filecom3));
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = '3 COMPROBANTE DE INGRESO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filecom3));
      }
    }

    if ($filerfc != 1) {
      $path = 'fisicas/rfc';
      $extension = strtolower($filerfc->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'RFC';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->save();

        $image = Image::make(File::get($filerfc));
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'RFC';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filerfc));
      }
    }

    return redirect('/clientes/fisica')->with('message', 'OK');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function editar($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Editar Cliente'
    ];

    $nacionalidades = db::table('nacionalidades')->get();
    $paises = db::table('paises')->get();
    $entidad = db::table('entidad_federativa')->get();
    $datos = db::table('clientes')->where('id', $id)->first();

    return view('/morales/fisicas-editar', [
      'pageConfigs' => $pageConfigs,
      'nacionalidades' => $nacionalidades,
      'paises' => $paises,
      'entidad' => $entidad,
      'datos' => $datos
    ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function NuevaEmpresa()
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Nueva Empresa'
    ];

    $nacionalidades = db::table('nacionalidades')->get();
    $paises = db::table('paises')->get();
    $entidad = db::table('entidad_federativa')->get();
    $nacionalidadesantecedente = db::table('nacionalidad_antecedentes')->get();

    return view('/morales/nueva-empresa', [
      'pageConfigs' => $pageConfigs,
      'nacionalidades' => $nacionalidades,
      'paises' => $paises,
      'entidad' => $entidad,
      'nacionantecedentes'=>$nacionalidadesantecedente
    ]);
  }

  public function editarmoral($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Editar Moral'
    ];

    $nacionalidades = db::table('nacionalidades')->get();
    $nacionantecedentes = db::table('nacionalidad_antecedentes')->get();
    $paises = db::table('paises')->get();
    $entidad = db::table('entidad_federativa')->get();

    $datos2 = DB::TABLE('morales')->where('id', $id)->first();

    $datos = Moral::where('id', '=', $id)->with('personasmorales')->with('perfil')->first();
    $origen = ActividadGiro::get();
    $destino = DestinoRecursos::get();
    $instrumento = InstrumentoMonetario::get();
    $divisa = Divisa::get();
    $profesiones = Profesion::get();
    $actividad = ActividadGiro::get();
    //TODO corroborar consultas
    $profesion = Moral::where('id', $id)->first()->personasMorales[0]->job;
    $actividad = ActividadGiro::get();
    $efresidencia = EFResidencia::get();
    $gresidencia = Moral::where('id', $id)->first()->ef;
    $residencia = EntidadFederativa::where('code', $gresidencia)->first()->entity;

    if (isset($datos)) {

      return view('/morales/morales-editar', compact(
        'pageConfigs',
        'id',
        'datos',
        'datos2',
        'origen',
        'instrumento',
        'divisa',
        'destino',
        'profesiones',
        'profesion',
        'actividad',
        'nacionalidades',
        'paises',
        'entidad', 'profesion',
        'efresidencia',
        'residencia',
        'actividad',
        'nacionantecedentes'
      ));
    }
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function editarfisica()
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Editar'
    ];

    $nacionalidades = db::table('nacionalidades')->get();
    $paises = db::table('paises')->get();
    $entidad = db::table('entidad_federativa')->get();

    return view('/clients/new-fisica', [
      'pageConfigs' => $pageConfigs,
      'nacionalidades' => $nacionalidades,
      'paises' => $paises,
      'entidad' => $entidad,
    ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $user = DB::TABLE('users')->where('id', $id)->first();

    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Editar Usuario '
    ];
    return view('/users/edit-user', [
      'pageConfigs' => $pageConfigs,
      'user' => $user
    ]);
  }

  public function agregarArchivo($archivo, $cid, $uid, $tipo)
  {
    if (isset($archivo)) {
      $path = 'personas-morales/' . Str::slug($tipo);
      $extension = ("jpg");
      //$extension = strtolower($archivo->getClientOriginalExtension());
      if (strtolower($extension) == 'pdf') {
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($archivo));
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = $tipo;
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $uid;
        $uploads->tipo = 1;
        $uploads->save();
      }
    }
    return '';
  }

  public function agregarImagen($archivo, $cid, $uid, $tipo)
  {
    if (isset($archivo)) {
      $image = Image::make(File::get($archivo));
      $path = 'personas-morales/imagenes';
      $id = rand();
     // $extension = strtolower($archivo->getClientOriginalExtension());
      $extension = ("jpg");
      $filename = $id . '-destino.' . $extension;
      $image->resize(1280, null, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      });

      Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      $uploads = new Files();
      $uploads->client_id = $cid;
      $uploads->type = $tipo;
      $uploads->path = $path;
      $uploads->extension = $extension;
      $uploads->name = $filename;
      $uploads->full = $path . '/' . $filename;
      $uploads->user_id = $uid;
      $uploads->tipo = 1;
      $uploads->save();
    }
    return;
  }

  public function create(Request $request)
  {
    /*$request->validate([
        'email' => 'required|string|email|unique:users'
      ]);*/
    $moral = new Moral($request->all());
    $moral->id_nacionalidad_antecedente=$request->nacionalidad_ante;

    DB::beginTransaction();
    try {
      $moral->save();
      if (isset($request->personasMorales)) {
        $moral->personasMorales()->createMany($request->personasMorales);
      }
    } catch (\Exception $ex) {
      DB::rollback();
      return response()->json(['error' => $ex->getMessage()], 500);
    }
    DB::commit();


    $cid = $moral->id;
    $user = $request->user();
    $userid = $user->id;
    $this->agregarArchivo($request->file('entrevista'), $cid, $userid, 'Entrevista');
    $this->agregarArchivo($request->file('reporte'), $cid, $userid, 'Reporte');
    $this->agregarArchivo($request->file('autorizacion_reporte_circulo_credito'), $cid, $userid, 'Reporte Circulo');
    $this->agregarArchivo($request->file('ultima_declaracion_anual'), $cid, $userid, 'Declaracion anual');
    $this->agregarArchivo($request->file('estados_financieros_anuales'), $cid, $userid, 'Estados Anuales');
    $this->agregarArchivo($request->file('estados_financieros_recientes'), $cid, $userid, 'Estados Recientes');
    $this->agregarImagen($request->file('fotografia1'), $cid, $userid, 'Imagen1');
    $this->agregarImagen($request->file('fotografia2'), $cid, $userid, 'Imagen2');

    $random = Str::random(50);
    $category = new User;
    $category->name = $request->nombre;
    $category->lastname = "-";
    $category->o_lastname = "-";
    $category->email = $request->email;
    $category->role = 3;
    $category->status = 'Activo';
    $category->password = bcrypt('123456');
    $category->activate = $random;
    $category->save();
    //TODO cuidar email
    //Mail::to(array($request->email))->send(new EmailVerification($random));


    $moral->refresh();
    $personasMoralesCurp = array_column($request->personasMorales, 'curp');
    foreach ($moral->personasMorales as $personaMoral) {
      $key = array_search($personaMoral->curp, $personasMoralesCurp);

      $files = $request->file('personasMorales');
      $fileine = $files[$key]['inefront'] ? $files[$key]['inefront'] : 1;
      $ineback = $files[$key]['ineback'] ? $files[$key]['ineback'] : 1;

      if ($fileine != 1) {
        $path = 'personas-morales/ine';
       // $extension = strtolower($fileine->getClientOriginalExtension());
        $extension = ("jpg");
        if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
          $filename = $personaMoral->id . '-frontal.' . $extension;
          $uploads = new Files();
          $uploads->client_id = $personaMoral->id;
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

          Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode('jpg', 30));
        } else {
          $uploads = new Files();
          $uploads->client_id = $personaMoral->id;
          $uploads->type = 'INE';
          $uploads->path = $path;
          $uploads->extension = $extension;
          $uploads->name = $personaMoral->id . '-frontal.' . $extension;
          $uploads->full = $path . '/' . $personaMoral->id . '.' . $extension;
          $uploads->user_id = $user->id;
          $uploads->tipo = 1;
          $uploads->save();
          Storage::disk('public')->put($path . '/' . $personaMoral->id . '.' . $extension, File::get($fileine));
        }
      }

      if ($ineback != 1) {
        $path = 'personas-morales/ine';
        //$extension = strtolower($ineback->getClientOriginalExtension());
        $extension = ("jpg");
        if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
          $filename = $personaMoral->id . '-trasera.' . $extension;
          $uploads = new Files();
          $uploads->client_id = $personaMoral->id;
          $uploads->type = 'INE';
          $uploads->path = $path;
          $uploads->extension = $extension;
          $uploads->name = $filename;
          $uploads->full = $path . '/' . $filename;
          $uploads->user_id = $user->id;
          $uploads->tipo = 1;
          $uploads->save();

          $image = Image::make(File::get($ineback));
          $image->insert(public_path('images/confidencial.png'), 'center');
          $image->resize(1280, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
          });

          Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode('jpg', 30));
        } else {
          $uploads = new Files();
          $uploads->client_id = $personaMoral->id;
          $uploads->type = 'INE';
          $uploads->path = $path;
          $uploads->extension = $extension;
          $uploads->name = $personaMoral->id . '-trasera.' . $extension;
          $uploads->full = $path . '/' . $personaMoral->id . '.' . $extension;
          $uploads->user_id = $user->id;
          $uploads->tipo = 1;
          $uploads->save();
          Storage::disk('public')->put($path . '/' . $personaMoral->id . '.' . $extension, File::get($ineback));
        }
      }
    }


    $filecurp = $request->file('filecurp') ? $request->file('filecurp') : 1;
    $filedom = $request->file('filedom') ? $request->file('filedom') : 1;
    $filerfc = $request->file('filerfc') ? $request->file('filerfc') : 1;


    if ($filecurp != 1) {
      $path = 'morales/acta';
     // $extension = strtolower($filecurp->getClientOriginalExtension());
      $extension = ("jpg");
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'ACTA';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->tipo = 1;
        $uploads->save();

        $image = Image::make(File::get($filecurp));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'ACTA';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->tipo = 1;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filecurp));
      }
    }

    if ($filedom != 1) {
      $path = 'morales/dom';
//      $extension = strtolower($filedom->getClientOriginalExtension());
      $extension = ("jpg");
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'COMPROBANTE DOMICILIO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->tipo = 1;
        $uploads->save();

        $image = Image::make(File::get($filedom));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'COMPROBANTE DOMICILIO';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->tipo = 1;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filedom));
      }
    }


    if ($filerfc != 1) {
      $path = 'morales/rfc';
      $extension = ("jpg");
      //$extension = strtolower($filerfc->getClientOriginalExtension());
      if (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif') {
        $filename = $cid . '.' . $extension;
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'RFC';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $filename;
        $uploads->full = $path . '/' . $filename;
        $uploads->user_id = $user->id;
        $uploads->tipo = 1;
        $uploads->save();

        $image = Image::make(File::get($filerfc));
        $image->insert(public_path('images/confidencial.png'), 'center');
        $image->resize(1280, null, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        Storage::disk('public')->put($path . '/' . $filename, (string)$image->encode($extension, 30));
      } else {
        $uploads = new Files();
        $uploads->client_id = $cid;
        $uploads->type = 'RFC';
        $uploads->path = $path;
        $uploads->extension = $extension;
        $uploads->name = $cid . '.' . $extension;
        $uploads->full = $path . '/' . $cid . '.' . $extension;
        $uploads->user_id = $user->id;
        $uploads->tipo = 1;
        $uploads->save();
        Storage::disk('public')->put($path . '/' . $cid . '.' . $extension, File::get($filerfc));
      }
    }


    return redirect('/morales/morales')->with('message', 'OK');
  }

  public function edited(Request $request)
  {
    $request->validate([
      'name' => 'required|string',
      'lastname' => 'required|string',
      'olastname' => 'required|string',
      'status' => 'required|string|',
      'rol' => 'required|string|'
    ]);

    $guser = DB::TABLE('users')->where('email', $request->email)->first();

    $id = $guser->id;
    $user = User::find($id);
    $user->name = $request->name;
    $user->lastname = $request->lastname;
    $user->o_lastname = $request->olastname;
    $user->email = $request->email;
    $user->role = $request->rol;
    $user->status = $request->status;
    $user->save();

    return redirect('/morales/morales')->with('message', 'ED');
  }


  public function activar(Request $request)
  {
    $id = $request->id;
    $user = Moral::find($id);
    $user->status = 'Activo';
    $user->save();

    return response('OK', 200);
  }

  public function archivar(Request $request)
  {
    $id = $request->id;
    $user = Moral::find($id);
    $user->status = 'Archivado';
    $user->save();

    return response('OK', 200);
  }


  public function data(Request $request)
  {
    $id = $request->id;
    $guser = DB::TABLE('clientes')->where('id', $id)->first();
    $data = array(
      'nombre' => strtoupper($guser->name . ' ' . $guser->lastname . ' ' . $guser->o_lastname),
      'genero' => $guser->gender,
      'fnac' => $guser->date_birth,
      'pnac' => $guser->place_birth,
      'ocupacion' => $guser->job,
      'direccion' => $guser->street . ' ' . $guser->exterior . ' ' . $guser->inside . ' ' . $guser->town . ' ' . $guser->city . ' ' . $guser->pc,
      'telefonos' => $guser->phone1 . ' ' . $guser->phone2,
      'email' => strtoupper($guser->email),
      'curp' => strtoupper($guser->curp),
      'rfc' => strtoupper($guser->rfc),
      'cnombre' => strtoupper($guser->c_name . ' ' . $guser->c_lastname . ' ' . $guser->c_o_lastname),
      'ctelefono' => $guser->c_phone,
      'cemail' => strtoupper($guser->c_email),
      'images' => array()
    );

    $images = DB::TABLE('files')->where('client_id', $id)->where('tipo', 1)->get();

    foreach ($images as $img) {
      array_push($data['images'], array('extension' => $img->extension, 'name' => $img->name, 'path' => $img->full));
    }

    return response($data, 200);
  }

  public function desactivar(Request $request)
  {
    $id = $request->id;
    $user = User::find($id);
    $user->status = 'Desactivado';
    $user->save();

    return response('OK', 200);
  }

  public function getfiles($id)
  {

    $images = DB::TABLE('files')->where('client_id', $id)->where('tipo', 1)->get();
    $data = '';
    foreach ($images as $img) {
      $data .= '<tr><td>' . $img->type . '</td><td>' . $img->extension . '</td><td>' . $img->created_at . '</td><td><a href="/uploads/' . $img->full . '" target="_blank"><button  style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-eye primary"></i></button></a></td><td><a href="/storage/' . $img->full . '" target="_blank"><button  style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-download primary"></i></button></a></td></tr>';
    }

    return $data;
  }

  public function continuar($id)
  {
    $detinoC = new DestinoCredito();
    $client = Moral::where('id', $id)->first();
    //$destino=$detinoC::all();
    //$destino=$detinoC::all();
    $destino = DestinoRecursos::get();
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageHeader' => true,
      'pageName' => 'Continuar Registro'
    ];

    $monto = PerfilMoral::where('id_moral', '=', $id)->first()->limite_credito;

    $monto = $monto ? $monto : 0;

    $creditos = DB::SELECT("select (sum(flujo) - case when sum(pagos) is null then 0 else sum(pagos) end) as pendiente from  amortizaciones_morales where cliente_id=$id and flujo > 0");

    foreach($creditos as $cc)
    {

      $pendiente = $cc->pendiente ? $cc->pendiente : 0;
      $rmonto = $monto - $pendiente;
    }
    
    return view('/morales/continuar', [
      'pageConfigs' => $pageConfigs,
      'client' => $client,
      'id' => $id, 'destino' => $destino,
      'monto' => $rmonto
    ]);
  }

  public function credito(Request $request, $id)
  {
    $ncredito = new CreditosMorales;
    $ncredito->morales_id = $id;
    $ncredito->tcredito = $request->tcredito;
    $ncredito->contrato = $request->ncontrato;
    $ncredito->monto = $request->sliderInput;
    $ncredito->fpago = $request->fpago;
    $ncredito->frecuencia = $request->frecuencia;
    $ncredito->plazo = $request->numero_plazo;
    $ncredito->amortizacion = $request->amortizaciones;
    $ncredito->iva = $request->iva;
    $ncredito->tasa = $request->tinteres;
    $ncredito->disposicion = $request->disposicion;
    $ncredito->save();


    Moral::where('id', $id)->update(['status' => 'credito']);
    $detinoC = new DestinoCredito();
    $destino = $detinoC::all();
    $detinoC->id_credito = $ncredito->id;
    $detinoC->id_destino_recursos = $request->recurso;
    $detinoC->titular = $request->titular;
    $detinoC->numero_cuenta_clabe = $request->numero_cuenta_clabe;
    $detinoC->tipo_cuenta = $request->tipo_cuenta;
    $detinoC->save();
    $alerta = new Alerta();
   /* $alerta->validarDestino($request, $id, $ncredito->id);
    $alerta->validarRiesgo($id, $ncredito->id, "Nuevo credito");*/
    return redirect('/morales/morales')->with('credito', 'OK');

  }


  public function ebr($id)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Criterios de Riesgos'
    ];
    $datos = PerfilMoral::where('id_moral', '=', $id)->first();
    $origen = OrigenRecursos::get();
    $destino = DestinoRecursos::get();
    $instrumento = InstrumentoMonetario::get();
    $divisa = Divisa::get();
    $profesiones = Profesion::get();
    $actividad = ActividadGiro::get();
    $efresidencia = EFResidencia::get();
    $gresidencia = Moral::where('id', $id)->first()->ef;
    $residencia = EntidadFederativa::where('code', $gresidencia)->first()->entity;
    $profesions = DB::TABLE('morales')->where('id', $id)->first()->giro;

    if (isset($datos)) {
      return view('/morales/ebr', compact(
        'pageConfigs',
        'id',
        'datos',
        'origen',
        'instrumento',
        'divisa',
        'destino',
        'profesiones',
        'profesions',
        'efresidencia',
        'residencia',
        'actividad'
      ));
    } else {
      return redirect()->route('perfil_web_ebr', ['id' => $id, 'redireccion' => true]);
    }
  }

  public function eebr(Request $request)
  {
    $cid = $request->id;

    $user = Auth::user();

    $args = array(
      'profesion' => $request->profesion ? $request->profesion : null,
      'actividad_giro' => $request->actividad ? $request->actividad : null,
      'efr' => $request->efr ? $request->efr : null,
      'limite_credito' => $request->limite_credito ? $request->limite_credito : null
    );

    $fields = array(
      'id_moral' => $cid
    );

    $update = PerfilMoral::updateOrCreate($fields, $args);
    $riesgos = new Riesgos();
    $riesgos->gradoMorales($cid);
    return redirect('/morales/morales')->with('ebr', 'OK');
  }

  public function listaNegraPDF($id)
  {
    $cliente = Client::where('id', $id)->with('listasNegras')->first();
    $documentos = Files::where('client_id', '=', $id)->get();

    return PDF::loadView('/clients/listaNegraPDF', compact('cliente', 'documentos'))->stream();
    //return view('/clients/listaNegraPDF', compact('cliente', 'documentos'));
  }

  public function getPerfil($id, $redireccion = 0)
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Personas Morales'
    ];
    $datos = PerfilMoral::where('id_moral', '=', $id)->first();
    $origen = OrigenRecursos::get();
    $destino = DestinoRecursos::get();
    $instrumento = InstrumentoMonetario::get();
    $divisa = Divisa::get();
    $profesiones = Profesion::get();
    $actividad = ActividadGiro::get();
    $profesion = DB::TABLE('morales')->where('id', $id)->first();


    if (isset($datos)) {
      return view('/morales/perfil', compact(
        'pageConfigs',
        'id',
        'datos',
        'origen',
        'instrumento',
        'divisa',
        'destino',
        'profesiones',
        'profesion',
        'actividad',
        'redireccion'
      ));
    } else {
      return view('/morales/perfil', compact(
        'pageConfigs',
        'id',
        'origen',
        'instrumento',
        'divisa',
        'destino',
        'profesiones',
        'profesion',
        'actividad',
        'redireccion'
      ));
    }


  }

}
