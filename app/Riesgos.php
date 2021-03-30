<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Riesgos extends Model
{
  protected $table = "riesgos";

  public function cliente()
  {
    return $this->hasOne("App\Client", 'id', 'id_cliente');
  }

  public function gradoMorales($id)
  {
    $moral = Moral::find($id);
    $profesion = $moral->perfil->profesionn;
    if ($profesion != null) {
      $riesgo = Riesgo::orderby('id', 'asc')->get();
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
      foreach ($antecedentes as $item) {
        $valorAntecedentes += $item['puntaje'] * $item['ponderacion'] / 100;
      }
      $valorActEconomica = 0;
      foreach ($actEconomica as $item) {
        $valorActEconomica += $item['puntaje'] * $item['ponderacion'] / 100;
      }
      $valorOrigenRecursos = 0;
      foreach ($origenR as $item) {
        $valorOrigenRecursos += $item['puntaje'] * $item['ponderacion'] / 100;
      }
      $valorDestino = $destinoR[0]['puntaje'] * 1;
      $valorRes = ($valorAntecedentes * .5) + ($valorActEconomica * .17) + ($valorOrigenRecursos * .25) + ($valorDestino * .08);
      $riesgoBD = Riesgos::where('id_cliente', $id)->where('tipo', 1)->first();
      if ($riesgoBD == null) {
        $riesgo = new Riesgos();
        $riesgo->id_cliente = $id;
        $riesgo->valor = $valorRes;
        $riesgo->tipo = 1;
        $riesgo->save();
      } else {
        $riesgoBD->valor = $valorRes;
        $riesgoBD->save();
      }
    }
  }

  public function editarGrado($id)
  {
    $credito = Creditos::where('client_id', $id)->first();
    if ($credito != null) {
      $edad = Edad::orderby('id', 'asc')->get();
      $edades = array();
      foreach ($edad as $value) {
        switch ($value->descripcion) {
          case 'MENORES 22 AÑOS':
            $edades['22'] = $value->id;
            break;
          case 'DE 23 A 30 AÑOS':
            $edades['23'] = $value->id;
            break;
          case 'DE 31 A 50 AÑOS':
            $edades['31'] = $value->id;
            break;
          case 'DE 51 A 99 AÑOS':
            $edades['51'] = $value->id;
            break;
          default:
            break;
        }
      }
      $gedad = Client::where('id', $id)->first()->date_birth;
      $birthDate = Carbon::createFromFormat('Y-m-d', $gedad);
      $currentDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
      $diferencia = $currentDate->diffInYears($birthDate);
      $eid = 1;
      switch ($diferencia) {
        case $diferencia < 22:
          $eid = $edades['22'];
          break;
        case $diferencia < 31:
          $eid = $edades['23'];
          break;
        case $diferencia < 51:
          $eid = $edades['31'];
          break;
        case $diferencia > 50:
          $eid = $edades['51'];
          break;
        default:
          break;
      }
      $pactividad = DB::SELECT("select factor,
                      case when factor='Actividad o Giro' then actividad_giro.descripcion
                           when factor='Profesion' then profesion.descripcion
                      		 end as descripcion,
                      ponderacion,
                      case when factor='Actividad o Giro' then actividad_giro.puntaje
                           when factor='Profesion' then profesion.puntaje
                      		 end as puntaje,
                      format((
                      case when factor='Actividad o Giro' then actividad_giro.puntaje
                           when factor='Profesion' then profesion.puntaje
                      		 end
                      *(ponderacion/100)),2) as resultado
                      from ponderacion
                      LEFT JOIN perfil_transacional on perfil_transacional.cliente_id=$id
                      LEFT JOIN actividad_giro on actividad_giro.id=perfil_transacional.actividad_giro
                      LEFT JOIN profesion on profesion.id=perfil_transacional.profesion
                      where tipo='actividad'");
      $porigen = DB::SELECT("select factor,
                                case when factor='Origen de Recursos' then origen_recursos.descripcion
                                     when factor='Divisa' then divisa.descripcion
                                     when factor='Instrumento Monetario' then instrumento_monetario.descripcion
                                		 end as descripcion,
                                ponderacion,
                                case when factor='Origen de Recursos' then origen_recursos.puntaje
                                     when factor='Divisa' then divisa.puntaje
                                     when factor='Instrumento Monetario' then instrumento_monetario.puntaje
                                		 end as puntaje,

                                format((
                                case when factor='Origen de Recursos' then origen_recursos.puntaje
                                     when factor='Divisa' then divisa.puntaje
                                     when factor='Instrumento Monetario' then instrumento_monetario.puntaje
                                		 end
                                *(ponderacion/100)),2) as resultado
                                from ponderacion
                                LEFT JOIN perfil_transacional on perfil_transacional.cliente_id=$id
                                LEFT JOIN origen_recursos on origen_recursos.id=perfil_transacional.origen_recursos
                                LEFT JOIN divisa on divisa.id=perfil_transacional.divisa
                                LEFT JOIN instrumento_monetario on instrumento_monetario.id=perfil_transacional.instrumento_monetario
                                where tipo='origen'");
      $pantecedentes = DB::SELECT("select factor,
                            case when factor='Alertas PLD/FT' then pld.descripcion
                                 when factor='Edad' then edad.descripcion
                                 when factor='Antiguedad' then antiguedad.descripcion
                                 when factor='Personalidad Juridica' then personalidad_juridica.descripcion
                                 when factor='Confirmado Listas PEP Mexicano' then pep_mexicanas.descripcion
                                 when factor='Confirmado Listas PEP Extranjero' then pep_extranjeras.descripcion
                                 when factor='Nacionalidad' then nacionalidad_antecedentes.descripcion
                                 when factor='Entidad Federativa Residencia' then entidad_federativa_residencia.descripcion
                            		 end as descripcion,
                            ponderacion,
                            case when factor='Alertas PLD/FT' then pld.puntaje
                                 when factor='Edad' then edad.puntaje
                                 when factor='Antiguedad' then antiguedad.puntaje
                                 when factor='Personalidad Juridica' then personalidad_juridica.puntaje
                                 when factor='Confirmado Listas PEP Mexicano' then pep_mexicanas.puntaje
                                 when factor='Confirmado Listas PEP Extranjero' then pep_extranjeras.puntaje
                                 when factor='Nacionalidad' then nacionalidad_antecedentes.puntaje
                                 when factor='Entidad Federativa Residencia' then entidad_federativa_residencia.puntaje
                            		 end as puntaje,
                            format((
                            case when factor='Alertas PLD/FT' then pld.puntaje
                                 when factor='Edad' then edad.puntaje
                                 when factor='Antiguedad' then antiguedad.puntaje
                                 when factor='Personalidad Juridica' then personalidad_juridica.puntaje
                                 when factor='Confirmado Listas PEP Mexicano' then pep_mexicanas.puntaje
                                 when factor='Confirmado Listas PEP Extranjero' then pep_extranjeras.puntaje
                                 when factor='Nacionalidad' then nacionalidad_antecedentes.puntaje
                                 when factor='Entidad Federativa Residencia' then entidad_federativa_residencia.puntaje
                            		 end
                            *(ponderacion/100)),2) as resultado
                            from ponderacion
                            LEFT JOIN clientes on clientes.id=$id
                            LEFT JOIN perfil_transacional on perfil_transacional.cliente_id=$id
                            LEFT JOIN pld on pld.id=perfil_transacional.pld
                            LEFT JOIN edad on edad.id=$eid
                            LEFT JOIN antiguedad on antiguedad.id=clientes.antiguedad
                            LEFT JOIN personalidad_juridica on personalidad_juridica.descripcion='Persona Fisica'
                            LEFT JOIN pep_mexicanas on pep_mexicanas.descripcion='No'
                            LEFT JOIN pep_extranjeras on pep_extranjeras.descripcion='No'
                            LEFT JOIN nacionalidad_antecedentes on nacionalidad_antecedentes.descripcion='Mexicana'
                            LEFT JOIN entidad_federativa_residencia on entidad_federativa_residencia.id=perfil_transacional.efr
                            where tipo='antecedentes'");
      $pdestino = DB::SELECT("select factor, descripcion, ponderacion, puntaje, format((puntaje*(ponderacion/100)),2) as resultado
                            from ponderacion
                            LEFT JOIN perfil_transacional on perfil_transacional.cliente_id=$id
                            LEFT JOIN destino_recursos on destino_recursos.id=perfil_transacional.destino_recursos
                            where tipo='destino'");
      $antecedentesres = 0;
      $antecedentespon = 50;
      foreach ($pantecedentes as $value) {
        $antecedentesres = $antecedentesres + $value->resultado;
      }
      $actividadres = 0;
      $actividadpon = 17;
      foreach ($pactividad as $value) {
        $actividadres = $actividadres + $value->resultado;
      }
      $origenres = 0;
      $origenpon = 25;
      foreach ($porigen as $value) {
        $origenres = $origenres + $value->resultado;
      }
      $destinores = 0;
      foreach ($pdestino as $value) {
        $destinores = $destinores + $value->resultado;
      }
      $destinorespon = $destinores * ($origenpon / 100);
      $origenrespon = $origenres * ($origenpon / 100);
      $actividadrespon = $actividadres * ($actividadpon / 100);
      $antecedentesponres = $antecedentesres * ($antecedentespon / 100);
      $totalrespon = $antecedentesponres + $actividadrespon + $origenrespon + $destinorespon;
      $riesgoBD = Riesgos::where('id_cliente', $id)->first();
      if ($riesgoBD == null) {
        $riesgo = new Riesgos();
        $riesgo->id_cliente = $id;
        $riesgo->valor = $totalrespon;
        $riesgo->save();
      } else {
        $riesgoBD->valor = $totalrespon;
        $riesgoBD->save();
      }
    }
  }
}
