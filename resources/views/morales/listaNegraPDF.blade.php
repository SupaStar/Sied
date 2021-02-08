<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Resultado Lista Negra</title>
  <style>
    :root {
      --blue: #00cfe8;
      --indigo: #6610f2;
      --purple: #6f42c1;
      --pink: #e83e8c;
      --red: #ea5455;
      --orange: #ff9f43;
      --yellow: #ffc107;
      --green: #28c76f;
      --teal: #20c997;
      --cyan: #7367f0;
      --white: #fff;
      --gray: #b8c2cc;
      --gray-dark: #1e1e1e;
      --primary: #7367f0;
      --secondary: #b8c2cc;
      --success: #28c76f;
      --info: #00cfe8;
      --warning: #ff9f43;
      --danger: #ea5455;
      --light: #babfc7;
      --dark: #1e1e1e;
      --breakpoint-xs: 0;
      --breakpoint-sm: 576px;
      --breakpoint-md: 768px;
      --breakpoint-lg: 992px;
      --breakpoint-xl: 1200px;
      --font-family-sans-serif: "Montserrat", Helvetica, Arial, serif;
      --font-family-monospace: "Montserrat", Helvetica, Arial, serif;
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
    }

    html {
      font-family: sans-serif;
      line-height: 1.15;
      -webkit-text-size-adjust: 100%;
      -webkit-tap-highlight-color: rgba(34, 41, 47, 0);
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      margin-top: 0;
      margin-bottom: 0.5rem;
    }

    img {
      vertical-align: middle;
      border-style: none;
    }

    .img-fluid {
      max-width: 100%;
      height: auto;
      text-align: center;
    }

    .center {
      display: block;
      margin-left: auto;
      margin-right: auto;
      width: 50%;
    }

    .row {
      display: flex;
      flex-wrap: wrap;
      margin-right: -14px;
      margin-left: -14px;
    }

    .table {
      width: 100%;
      margin-bottom: 1rem;
      color: #626262;
    }

    .table th,
    .table td {
      padding: 0.75rem;
      vertical-align: top;
      border-top: 1px solid #f8f8f8;
    }

    .table thead th {
      vertical-align: bottom;
      border-bottom: 2px solid #f8f8f8;
    }

    .table tbody+tbody {
      border-top: 2px solid #f8f8f8;
    }

    .table-sm th,
    .table-sm td {
      padding: 0.3rem;
    }

    .table-bordered {
      border: px solid #1d1c1c;
    }

    .table-bordered th,
    .table-bordered td {
      border: 1px solid #1d1c1c;
    }

    .table-bordered thead th,
    .table-bordered thead td {
      border-bottom-width: 2px;
    }

    .col-md,
    .col-md-auto,
    .col-md-12,
    .col-md-11,
    .col-md-10,
    .col-md-9,
    .col-md-8,
    .col-md-7,
    .col-md-6,
    .col-md-5,
    .col-md-4,
    .col-md-3,
    .col-md-2,
    .col-md-1 {
      position: relative;
      width: 100%;
      padding-right: 14px;
      padding-left: 14px;
    }

    img {
      page-break-inside: avoid;
    }

    <blade media|%20(min-width%3A%20768px)%20%7B%0D>.col-md {
      flex-basis: 0;
      flex-grow: 1;
      max-width: 100%;
    }

    .col-md-auto {
      flex: 0 0 auto;
      width: auto;
      max-width: 100%;
    }

    .col-md-1 {
      flex: 0 0 8.3333333333%;
      max-width: 8.3333333333%;
    }

    .col-md-2 {
      flex: 0 0 16.6666666667%;
      max-width: 16.6666666667%;
    }

    .col-md-3 {
      flex: 0 0 25%;
      max-width: 25%;
    }

    .col-md-4 {
      flex: 0 0 33.3333333333%;
      max-width: 33.3333333333%;
    }

    .col-md-5 {
      flex: 0 0 41.6666666667%;
      max-width: 41.6666666667%;
    }

    .col-md-6 {
      flex: 0 0 50%;
      max-width: 50%;
    }

    .col-md-7 {
      flex: 0 0 58.3333333333%;
      max-width: 58.3333333333%;
    }

    .col-md-8 {
      flex: 0 0 66.6666666667%;
      max-width: 66.6666666667%;
    }

    .col-md-9 {
      flex: 0 0 75%;
      max-width: 75%;
    }

    .col-md-10 {
      flex: 0 0 83.3333333333%;
      max-width: 83.3333333333%;
    }

    .col-md-11 {
      flex: 0 0 91.6666666667%;
      max-width: 91.6666666667%;
    }

    .col-md-12 {
      flex: 0 0 100%;
      max-width: 100%;
    }
    }

  </style>

<body>
  <section>
    <h1>
      Resultados de Busqueda de Coincidencias con Listas de Control
    </h1>
  </section>
  <br><br>
  <section>
    <h2 style="text-align: center"> Documento de Identificacion</h2>
    <div class="col-md-12">
      <hr>
    </div>
    
    <table>
      <tbody>
        <tr>
          <td><img
              src="{{ url('/uploads/fisicas/ine/'.$cliente->id.'-frontal.jpg') }}"
              alt="INE" class="img img-fluid center" style="width:300px; height:auto;"></td>
          <td><img
              src="{{ url('/uploads/fisicas/ine/'.$cliente->id.'-trasera.jpg') }}"
              alt="INE" class="img img-fluid center" style="width:300px; height:auto;"></td>
        </tr>
      </tbody>
    </table>
  </section>
  <section>
    <h2 style="text-align: center">Resultados de la Verificacion</h2>
    <div class="col-md-12">
      <hr>
    </div>
    <h3>Datos del documento</h3>
    <div class="row">
      <table class="table table-bordered">
        <tbody>
          <tr>
            <td><strong>Nombre Completo</strong></td>
            <td>
              {{ $cliente->name.' '.$cliente->lastname.' '.$cliente->o_lastname }}
            </td>
            <td><strong>Sexo</strong></td>
            <td>{{ $cliente->gender }} </td>
            <td><strong>Número Personal</strong></td>
            <td>{{ $cliente->curp }} </td>
          </tr>
          <tr>
            <td><strong>Fecha de Nacimiento</strong></td>
            <td>{{ $cliente->date_birth }} </td>
            <td><strong>Dirección</strong></td>
            <td>{{ $cliente->direccion }} </td>
            <td><strong>Ciudad</strong></td>
            <td>{{ $cliente->city }} </td>
          </tr>
          <tr>
            <td><strong>Delegación Municipio</strong></td>
            <td>{{ $cliente->town }} </td>
            <td><strong>Codigo Postal</strong></td>
            <td>{{ $cliente->cp }} </td>
          </tr>
        </tbody>
      </table>
    </div>
    <br>
    <br>
    <br>
    <h3>Lista de Control</h3>
    <div class="row">
      <table class="table table-bordered">
        <thead class="thead-dark">
          <th style="text-align: center">Nombre</th>
          <th style="text-align: center">Entrada</th>
        </thead>
        <tbody>
          @foreach($cliente->listasNegras as $entrada)
            <tr>
              <td style="text-align: center">{{ $entrada->name }} </td>
              <td style="text-align: center">{{ $entrada->value }} </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </section>
</body>

</html>
