<?php

namespace App\Exports;

use App\Alerta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AlertasExport implements FromCollection, WithHeadings, WithMapping
{
  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
//    ->where('created_at', '<', $this->fechaTermino)
    $alertas = Alerta::where('created_at', '>', $this->fechaInicio)->get();
    foreach ($alertas as $alerta) {
      if ($alerta->estatus == 1) {
        $alerta->estatus = "Nuevo";
      } elseif ($alerta->estatus == 2) {
        $alerta->estatus = "Recabando información";
      } elseif ($alerta->estatus == 3) {
        $alerta->estatus = "En proceso";
      } elseif ($alerta->estatus == 4) {
        $alerta->estatus = "Observaciones";
      } else {
        $alerta->estatus = "Concluido";
      }
      if ($alerta->envio == 1) {
        $alerta->operacion = "Operación no preocupante";
      } elseif ($alerta->envio == 2) {
        $alerta->operacion = "Operación inusual";
      } elseif ($alerta->envio == 3) {
        $alerta->operacion = "Clientes Clasificados en el mayor grado de mayor riesgo";
      } elseif ($alerta->envio == 4) {
        $alerta->operacion = "Operación clientes Clasificados en el mayor grado de mayor riesgo";
      } elseif ($alerta->envio == 5) {
        $alerta->operacion = "Operaciones relevantes";
      } else {
        $alerta->operacion = "Operaciones relevantes";
      }
      $alerta->cliente = $alerta->cliente->name . ' ' . $alerta->cliente->lastname . ' ' . $alerta->cliente->o_lastname;
      $alerta->credito;
    }
    return $alertas;
  }

  public function fechaInicio($fechaInicio, $fechaTermino)
  {
    $this->fechaInicio = $fechaInicio;
    $this->fechaTermino = $fechaTermino;
    return $this;
  }

  public function headings(): array
  {
    return [
      '#',
      'Cliente',
      'Contrato Credito',
      'Tipo de Alerta',
      'Alerta',
      'Titulo',
      'Descripcion',
      'Estatus',
      'Observacion',
      'Prioridad'
    ];
  }

  public function map($row): array
  {
    return [
      $row->id,
      $row->cliente,
      $row->credito->contrato,
      $row->operacion,
      $row->tipo_alerta,
      $row->titulo,
      $row->descripcion,
      $row->estatus,
      $row->observacion,
      $row->created_at,
    ];
  }
}
