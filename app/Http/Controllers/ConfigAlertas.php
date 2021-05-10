<?php

namespace App\Http\Controllers;

use App\ConfiguracionesGenerales;
use Illuminate\Http\Request;

class ConfigAlertas extends Controller
{
  public function todos()
  {
    $pageConfigs = [
      'mainLayoutType' => 'vertical',
      'pageName' => 'Configuraciones Generales'
    ];
    $configuracionesAlertas = \App\ConfigAlertas::find(1);
    $configuracionesGenerales = ConfiguracionesGenerales::find(1);
    return view('/configuracion/configuracion', [
      'pageConfigs' => $pageConfigs,
      'configuraciones' => $configuracionesGenerales,
      'configuracionesAlertas' => $configuracionesAlertas
    ]);
  }

  public function editarConfigGenerales(Request $request)
  {
    $configuracionesGenerales = ConfiguracionesGenerales::find(1);
    if ($configuracionesGenerales) {
      $configuracionesGenerales->gasto_cobranza = $request->input('GastoCobranza');
      $configuracionesGenerales->iva = $request->input('IVA');
      $configuracionesGenerales->save();
    } else {
      $configuracionesGenerales = new ConfiguracionesGenerales();
      $configuracionesGenerales->gasto_cobranza = $request->input('GastoCobranza');
      $configuracionesGenerales->iva = $request->input('IVA');
      $configuracionesGenerales->save();
    }
    return redirect('configuracion/configuraciones');
  }
  public function editarConfigAlertas(Request $request)
  {
    $configuracionesAlertas = \App\ConfigAlertas::find(1);
    if ($configuracionesAlertas) {
      $configuracionesAlertas->pagosMes = $request->input('nPagos');
      $configuracionesAlertas->monto = $request->input('monto');
      $configuracionesAlertas->save();
    } else {
      $configuracionesAlertas = new ConfiguracionesGenerales();
      $configuracionesAlertas->gasto_cobranza = $request->input('nPagos');
      $configuracionesAlertas->iva = $request->input('monto');
      $configuracionesAlertas->save();
    }
    return redirect('configuracion/configuraciones');
  }

}
