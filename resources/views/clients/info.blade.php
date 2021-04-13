@extends('layouts/contentLayoutMaster')

@section('title', 'Información')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/pages/invoice.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">

@endsection
@section('content')


  {{-- Nav Justified Starts --}}
  <section id="nav-justified">
    <div class="row">
      <div class="col-sm-12">
        <div class="card overflow-hidden">
          <div class="card-content">
            <div class="card-body">
              <h5>CLIENTE: {{$nombre}} -- CRÉDITO: {{$contrato}}</h5>
              <br>
              <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab-justified" data-toggle="tab" href="#home-just" role="tab"
                     aria-controls="home-just" aria-selected="true">DATOS PERSONALES</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-credito" data-toggle="tab" href="#profile-credito" role="tab"
                     aria-controls="profile-just" aria-selected="true">CRÉDITO</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-amortizacion" data-toggle="tab" href="#profile-amortizacion" role="tab"
                     aria-controls="profile-just" aria-selected="true">AMORTIZACÓN</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-pagos" data-toggle="tab" href="#profile-pagos" role="tab"
                     aria-controls="profile-just" aria-selected="true">PAGOS</a>
                </li>
              </ul>

              {{-- Tab panes --}}
              <div class="tab-content pt-1">
                <div class="tab-pane active" id="home-just" role="tabpanel" aria-labelledby="home-tab-justified">
                  <!-- invoice functionality start -->
                  <section class="invoice-print mb-1">
                    <div class="row">
                      <fieldset class="col-12 col-md-5 mb-1 mb-md-0">
                      </fieldset>
                      <div class="col-12 col-md-7 d-flex flex-column flex-md-row justify-content-end">
                        <button class="btn btn-primary btn-print mb-1 mb-md-0"> <i class="feather icon-file-text"></i> Imprimir</button>
                        <!-- <button class="btn btn-outline-primary  ml-0 ml-md-1"> <i class="feather icon-download"></i> Descargar</button> -->
                      </div>
                    </div>
                  </section>
                  <!-- invoice functionality end -->
                  <section class="card invoice-page">
                    <div id="invoice-template" class="card-body">

                      <!-- Invoice Items Details -->
                      <div id="invoice-items-details" class="pt-1 invoice-items-table">



                        <div class="container-fluid">
                          <div class="row justify-content-center mb-4">
                            <div class="offset-6 col-3 text-center">
                              <a href="{{url('/uploads/fisicas/ine/'.$ine1)}}" target="_blank"> <img src="{{url('/uploads/fisicas/ine/'.$ine1)}}" alt="INE" height="100"></a>
                            </div>
                            <div class="col-3 text-center">
                              <a href="{{url('/uploads/fisicas/ine/'.$ine2)}}" target="_blank"> <img src="{{url('/uploads/fisicas/ine/'.$ine2)}}" alt="INE" height="100"></a>
                            </div>
                          </div>
                        </div>


                        <div class="row">
                          <div class="table-responsive col-sm-12">

                            <p></p>

                            <br>
                            <h3>Cliente Persona Fisica</h3>

                            <!-- Step 1 -->
                            <h6><i class="step-icon feather icon-user"></i> Datos Personales</h6>
                            <fieldset>
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="firstName3">
                                      Nombre(s)
                                    </label>
                                    <input type="text" readonly class="form-control required" id="nombre" name="nombre" value="@if(isset($datos->name)) {{$datos->name}} @endif" >
                                  </div>
                                </div>

                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="lastName3">
                                      Apellido Paterno
                                    </label>
                                    <input type="text" readonly class="form-control required" id="apellidop" name="apellidop" value="@if(isset($datos->lastname)) {{$datos->lastname}} @endif">
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="lastName3">
                                      Apellido Materno
                                    </label>
                                    <input type="text" readonly class="form-control required" id="apellidom" name="apellidom" value="@if(isset($datos->o_lastname)) {{$datos->o_lastname}} @endif">
                                  </div>
                                </div>
                              </div>
                              <div class="row">

                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="proposalTitle3">
                                      Genero
                                    </label>
                                    <input type="text" readonly value="@if(isset($datos->gender)) {{$datos->gender}} @endif" class="form-control required"   >
                                  </div>
                                </div>

                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="lastName3">
                                      Fecha de Nacimiento
                                    </label>
                                    <input type='text' readonly class="form-control pickadate-translations" id="nacimiento" name="fnacimiento" value="@if(isset($datos->date_birth)) {{$datos->date_birth}} @endif" />
                                  </div>
                                </div>



                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="firstName3">
                                      País de Nacimiento
                                    </label>
                                    <select class="form-control" id="basicSelect" name="pais_nacimiento" readonly>
                                      <option selected disabled>Seleccionar</option>
                                      @foreach ($paises as $dd)
                                        <option @if(isset($datos->country_birth) && $datos->country_birth == $dd->code) selected @endif value="{{$dd->code}}">{{$dd->pais}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="firstName3">
                                      Lugar de Nacimiento
                                    </label>
                                    <select class="form-control" id="lnacimiento" name="lnacimiento" readonly>
                                      <option selected disabled>Seleccionar</option>
                                      @foreach ($entidad as $dd)
                                        <option @if(isset($datos->nationality) && $datos->nationality == $dd->code) selected @endif value="{{$dd->code}}">{{$dd->entity}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                </div>


                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="firstName3">
                                      Nacionalidad
                                    </label>
                                    <select class="form-control" id="basicSelect" name="nacionalidad" readonly>
                                      <option selected disabled>Seleccionar</option>
                                      @foreach ($nacionalidades as $dd)
                                        <option @if(isset($datos->place_birth) && $datos->place_birth == $dd->code) selected @endif value="{{$dd->code}}">{{$dd->country}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="lastName3">
                                      Ocupación
                                    </label>
                                    <input type="text" readonly value="@if(isset($datos->job)) {{$datos->job}} @endif" class="form-control required"  name="ocupacion">
                                  </div>
                                </div>
                              </div>

                            </fieldset>

                            <!-- Step 2 -->
                            <h6><i class="step-icon feather icon-map-pin"></i> Dirección</h6>
                            <fieldset>
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="proposalTitle3">
                                      Calle
                                    </label>
                                    <input type="text" readonly value="@if(isset($datos->street)) {{$datos->street}} @endif" class="form-control required"  name="calle">
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="proposalTitle3">
                                      # Exterior
                                    </label>
                                    <input type="text" readonly value="@if(isset($datos->exterior)) {{$datos->exterior}} @endif" class="form-control required"  name="exterior">
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="proposalTitle3">
                                      # Interior
                                    </label>
                                    <input type="text" readonly value="@if(isset($datos->inside)) {{$datos->inside}} @endif" class="form-control required"  name="interior">
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="proposalTitle3">
                                      Codigo Postal
                                    </label>
                                    <input type="text" readonly value="@if(isset($datos->pc)) {{$datos->pc}} @endif" class="form-control required"  name="cp" id="cp" onchange="sepomex();" >
                                  </div>
                                </div>

                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="proposalTitle3">
                                      Colonia
                                    </label>
                                    <input type="text" readonly value="@if(isset($datos->colony)) {{$datos->colony}} @endif" class="form-control required"   >
                                  </div>
                                </div>


                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="proposalTitle3">
                                      Alcaldia o Municipio
                                    </label>
                                    <input type="text" readonly value="@if(isset($datos->town)) {{$datos->town}} @endif" class="form-control required"   >
                                  </div>
                                </div>


                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="proposalTitle3">
                                      Ciudad o Población
                                    </label>
                                    <input type="text" readonly value="@if(isset($datos->city)) {{$datos->city}} @endif" class="form-control required"   >
                                  </div>
                                </div>



                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="firstName3">
                                      Entidad Federativa
                                    </label>
                                    <select class="form-control" id="entidad" name="entidad" readonly>
                                      <option selected disabled>Seleccionar</option>
                                      @foreach ($entidad as $dd)
                                        <option @if(isset($datos->ef) && $datos->ef == $dd->code) selected @endif  value="{{$dd->code}}">{{$dd->entity}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="firstName3">
                                      País
                                    </label>
                                    <select class="form-control" id="pais" name="pais" readonly>
                                      @if(isset($datos->country))
                                        <option disabled>Seleccionar</option>
                                        <option selected value="México">México</option>
                                      @else
                                        <option selected disabled>Seleccionar</option>
                                        <option value="México">México</option>
                                      @endif

                                    </select>
                                  </div>
                                </div>
                              </div>
                            </fieldset>

                            <!-- Step 3 -->
                            <h6><i class="step-icon feather icon-file-plus"></i> Datos Adicionales</h6>
                            <fieldset>
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="firstName3">
                                      Número Teléfonico 1
                                    </label>
                                    <input type="text" readonly value="@if(isset($datos->phone1)) {{$datos->phone1}} @endif"  class="form-control"  name="telefono1" >
                                  </div>
                                </div>

                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="lastName3">
                                      Número Teléfonico 2
                                    </label>
                                    <input type="text" readonly value="@if(isset($datos->phone2)) {{$datos->phone2}} @endif"  class="form-control " name="telefono2">
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="lastName3">
                                      Email
                                    </label>
                                    <input type="text" readonly value="@if(isset($datos->email)) {{$datos->email}} @endif"  class="form-control " id="memail" name="memail">
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="firstName3">
                                      CURP
                                    </label>
                                    <input type="text" readonly value="@if(isset($datos->curp)) {{$datos->curp}} @endif"  class="form-control required"  name="curp" id="curp" >
                                  </div>
                                </div>

                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="lastName3">
                                      RFC
                                    </label>
                                    <input type="text" readonly value="@if(isset($datos->rfc)) {{$datos->rfc}} @endif"  class="form-control required" name="rfc" id="rfc">
                                  </div>
                                </div>
                              </div>
                              <h6><i class="step-icon feather icon-file-plus"></i> Datos De Cónyuge</h6>

                              <div id="conyuge" style="display:block;" >
                                <div class="row">
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="firstName3">
                                        Nombre(s)
                                      </label>
                                      <input type="text" readonly value="@if(isset($datos->c_name)) {{$datos->c_name}} @endif" class="form-control"  name="cnombre"  >
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="lastName3">
                                        Apellido Paterno
                                      </label>
                                      <input type="text" readonly value="@if(isset($datos->c_lastname)) {{$datos->c_lastname}} @endif" class="form-control " name="capellidop">
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="lastName3">
                                        Apellido Materno
                                      </label>
                                      <input type="text" readonly value="@if(isset($datos->c_o_lastname)) {{$datos->c_o_lastname}} @endif" class="form-control " name="capellidom">
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="lastName3">
                                        Número Teléfonico
                                      </label>
                                      <input type="text" readonly value="@if(isset($datos->c_phone)) {{$datos->c_phone}} @endif" class="form-control " name="ctelefono">
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="lastName3">
                                        Correo Eletrónico
                                      </label>
                                      <input type="text" readonly value="@if(isset($datos->c_email)) {{$datos->c_email}} @endif" class="form-control " name="cemail">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </fieldset>


                          </div>
                        </div>
                      </div>

                      <!-- Invoice Footer -->
                      <div id="invoice-footer" class="text-right pt-3">
                        <p>
                        </p>
                      </div>
                      <!--/ Invoice Footer -->
                    </div>
                  </section>
                </div>

                <div class="tab-pane" id="profile-credito" role="tabpanel" aria-labelledby="profile-tab-justified">
                  <section class="invoice-print mb-1">
                  </section>
                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-content">
                          <div class="card-body card-dashboard">
                            <div class="table-responsive">
                              <table class="table table-striped table-bordered" id="credito">
                                <thead>
                                <tr>
                                  <th>Tipo</th>
                                  <th>Contrato</th>
                                  <th>Monto</th>
                                  <th>Forma de Pago</th>
                                  <th>Frecencia</th>
                                  <th>Plazo</th>
                                  <th>Amortización</th>
                                  <th>Iva</th>
                                  <th>Tasa</th>
                                  <th>Disposición</th>
                                  <th>Estado</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="tab-pane" id="profile-amortizacion" role="tabpanel" aria-labelledby="profile-tab-justified">
                  <section class="invoice-print mb-1">
                    <div class="row">
                      <fieldset class="col-12 col-md-5 mb-1 mb-md-0">
                      </fieldset>
                      <div class="col-12 col-md-7 d-flex flex-column flex-md-row justify-content-end">
                        <button class="btn btn-warning mb-1 mb-md-0"  data-toggle="modal" onclick="restaurar({{$id}});">  RESTAURAR AMORTIZACION</button>
                      </div>
                    </div>
                  </section>
                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-content">
                          <div class="card-body card-dashboard">
                            <div class="table-responsive">
                              <table class="table table-striped table-bordered" id="amortizacion">
                                <thead>
                                <tr>
                                  <th>Periodo</th>
                                  <th>Fechas</th>
                                  <th>Días</th>
                                  <th>Disposición</th>
                                  <th>Saldo Insoluto</th>
                                  <th>Comisión</th>
                                  <th>Amortización</th>
                                  <th>Intereses</th>
                                  <th>Moratorios</th>
                                  <th>IVA</th>
                                  <th>Flujo</th>
                                  <th>Saldo Pendiente</th>
                                  <th>Días de Mora</th>
                                  <th>Int Mora</th>
                                  <th>Iva Mora</th>
                                  <th>Gasto Cobranza</th>
                                  <th>Pagos</th>
                                  <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>

                            <div class="table-responsive">
                              <table class="table table-striped table-bordered" id="ttasas">
                                <thead>
                                <tr>
                                  <th>Tipo</th>
                                  <th>Forma de Pago</th>
                                  <th>Frecencia</th>
                                  <th>Plazo</th>
                                  <th>Amortización</th>
                                  <th>Iva</th>
                                  <th>Tasa</th>
                                  <th>Moratorio</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="tab-pane" id="profile-pagos" role="tabpanel" aria-labelledby="profile-tab-justified">
                  <section class="invoice-print mb-1">
                    <div class="row">
                      <fieldset class="col-12 col-md-5 mb-1 mb-md-0">
                      </fieldset>
                      <div class="col-12 col-md-7 d-flex flex-column flex-md-row justify-content-end">
                        <button class="btn btn-success mb-1 mb-md-0"  data-toggle="modal" data-target="#inlineForm"> <i class="feather icon-dollar-sign"></i> Agregar Pago</button>
                        <!-- <button class="btn btn-outline-primary  ml-0 ml-md-1"> <i class="feather icon-download"></i> Descargar</button> -->
                      </div>
                    </div>
                  </section>
                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-content">
                          <div class="card-body card-dashboard">
                            <div class="table-responsive">
                              <table class="table table-striped table-bordered" id="pagos">
                                <thead>
                                <tr>
                                  <th>Periodo</th>
                                  <th>Fecha de Pago</th>
                                  <th>Dias de Mora</th>
                                  <th>Interes Mora</th>
                                  <th>Condonación</th>
                                  <th>Iva</th>
                                  <th>Pago</th>
                                  <th>Comprobante</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  {{-- Nav Justified Ends --}}


  <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog"
       aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel33">Agregar Pago </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="/clientes/credito/pago"  enctype="multipart/form-data"  method="POST" class="steps-validation wizard-circle" id="formss" name="formss">
          @csrf
          <input type="hidden" name="id" value="{{$id}}">

          <div class="modal-body">
            <label>Monto: </label>
            <div class="form-group">
              <input type="number" name="monto" step="any" min="0" placeholder="$" class="form-control required" required>
            </div>

            <label>Moneda </label>
            <div class="form-group">
              <select class="form-control" id="moneda" name="moneda" onchange="cmoneda()">
                <option selected disabled>Seleccionar</option>
                <option value="Nacional">Nacional</option>
                <option value="0">Extranjera</option>
              </select>
            </div>

            <div class="form-group" style="display:none" id="cssmoneda">
              <input type='text' class="form-control " placeholder="Moneda" id="nmoneda"  name="nmoneda" />
            </div>

            <label>Forma de Pago </label>
            <div class="form-group">
              <select class="form-control" id="forma" name="forma" onchange="cforma()">
                <option selected disabled>Seleccionar</option>
                <option value="Efectivo">Efectivo</option>
                <option value="Transferencia">Transferencia</option>
                <option value="Cheques">Cheques</option>
                <option value="0">Otro</option>
              </select>
            </div>

            <div class="form-group" style="display:none" id="cssforma">
              <input type='text' class="form-control " placeholder="Forma de Pago" id="nforma"  name="nforma" />
            </div>

            <div  style="display:none" id="trforma">
              <label>Lugar de Pago </label>
              <div class="form-group">
                <select class="form-control" id="clforma" name="forma" onchange="lforma()">
                  <option selected disabled>Seleccionar</option>
                  <option value="Internacional">Internacional</option>
                  <option value="Nacional">Nacional</option>
                </select>
              </div>
            </div>

            <div  style="display:none" id="lnacional">
              <label>Nacional </label>
              <div class="form-group">
                <select class="form-control" id="clnacional" name="lnacional" >
                  <option selected disabled>Seleccionar</option>
                  <option value="En la plaza">En la plaza</option>
                  <option value="En otros estados de la república">En otros estados de la república</option>
                  <option value="En zona fronteriza">En zona fronteriza</option>
                </select>
              </div>
            </div>

            <div  style="display:none" id="linternacional">
              <label>Internacional </label>
              <div class="form-group">
                <select class="form-control" id="clinternacional" name="linternacional" >
                  <option selected disabled>Seleccionar</option>
                  <option value="Países no cooperantes">Países no cooperantes</option>
                  <option value="Paraísos fiscales">Paraísos fiscales</option>
                  <option value="Otros">Otros</option>
                </select>
              </div>
            </div>

            <label>Origen </label>
            <div class="form-group">
              <select class="form-control" id="origen" name="origen" onchange="corigen()">
                <option selected disabled>Seleccionar</option>
                <option value="Cuentas propias">Cuentas propias</option>
                <option value="En el caso de créditos de nómina, del empleador">En el caso de créditos de nómina, del empleador</option>
                <option value="Cuentas de terceros">Cuentas de terceros</option>
                <option value="No identificado">No identificado</option>
              </select>
            </div>

            <div style="display:none" id="cterceros">
              <label>Internacional </label>
              <div class="form-group">
                <select class="form-control" id="ccterceros" name="cterceros" onchange="cccterceros()">
                  <option selected disabled>Seleccionar</option>
                  <option value="Relacionados en listas negras">Relacionados en listas negras</option>
                  <option value="Otros">Otros</option>
                </select>
              </div>
            </div>

            <div style="display:none" id="coterceros">
              <label>Internacional </label>
              <div class="form-group">
                <select class="form-control" id="ccterceros" name="cterceros" >
                  <option selected disabled>Seleccionar</option>
                  <option value="Identificados">Identificados</option>
                  <option value="No identificados">No identificados</option>
                </select>
              </div>
            </div>


            <label>Comprobante </label>
            <div class="form-group">
              <input type="file"
                     placeholder=".jpg, .jpeg, .png"
                     class="form-control required" id="comprobante" name="comprobante" accept=".jpg, .jpeg, .png">
            </div>

            <label>Fecha de Pago </label>
            <div class="form-group">
              <input type='text' class="form-control pickadate-disable required" id="fecha" value="{{date('Y-m-d')}}"  name="fecha" required />
            </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success" >Aplicar</button>
          </div>
        </form>
      </div>
    </div>
  </div>




  <div class="modal fade text-left" id="vpagos" tabindex="-1" role="dialog"
       aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel33">Pagos Aplicados </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="pagosAplicados">
              <thead>
              <tr>
                <th>Fecha de pago</th>
                <th>Monto aplicado</th>
                <th>Saldo restante</th>
                <th>Monto pagado</th>
                <th>Descripción</th>
                <th>Saldo a aplicarse en otro periodo</th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade text-left" id="vflujos" tabindex="-1" role="dialog"
       aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel33">Historial de Flujo </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="historialdeflujo">
              <thead>
              <tr>
                <th>Fecha </th>
                <th>Monto</th>
                <th>Monto Cambiado</th>
                <th>Descripción</th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('vendor-script')
  <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>


  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/nouislider.min.js')) }}"></script>

@endsection

@section('page-script')
  <!-- Page js files -->
  <script src="{{ asset(mix('js/scripts/pages/invoice.js')) }}"></script>

  <script>

    $(document).ready(function(){

      @if (session('pago'))
      Swal.fire({
        title: "Bien!",
        text: "Pago aplicado correctamente!",
        type: "success",
        confirmButtonClass: 'btn btn-primary',
        buttonsStyling: false,
        animation: false,
        customClass: 'animated tada'
      });
      @endif

      table();
      pagos();
      credito();
      tasas();

      $('.pickadate-disable').pickadate({
        disable: [
          1,
          [2019,3,6],
          [2019,3,20]
        ],
        format: 'yyyy-mm-dd',
        formatSubmit: 'yyyy-mm-dd',
        monthsFull: [ 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' ],
        monthsShort: [ 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic' ],
        weekdaysShort: [ 'Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab' ],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'

      });

    });

    function  restaurar(id){
      $.post('/clientes/amortizacion/restaurar', {
        id: id,
        _token: token
      }, function(data) {
        location.reload();

      });

    }

    function cmoneda()
    {
      var moneda = $('#moneda').val();
      if(moneda == 0)
      {
        $("#cssmoneda").css("display", "block");
      }else{
        $("#cssmoneda").css("display", "none");
      }
    }

    function verpagos(id)
    {
      pagosAplicadoss(id);
      $('#vpagos').modal('toggle');
    }

    function verflujos(id)
    {
      historialFlujo(id);
      $('#vflujos').modal('toggle');
    }

    function cforma()
    {
      var forma = $('#forma').val();
      if(forma == 0)
      {
        $("#cssforma").css("display", "block");
      }else if(forma == 'Transferencia'){
        $("#trforma").css("display", "block");
        $("#cssforma").css("display", "none");
      } else {
        $("#trforma").css("display", "none");
        $("#cssforma").css("display", "none");
      }
    }

    function lforma()
    {
      var forma = $('#clforma').val();
      if(forma == 'Nacional')
      {
        $("#lnacional").css("display", "block");
        $("#linternacional").css("display", "none");
      }else if(forma == 'Internacional'){
        $("#linternacional").css("display", "block");
        $("#lnacional").css("display", "none");
      } else {
        $("#lnacional").css("display", "none");
        $("#linternacional").css("display", "none");
      }
    }

    function corigen()
    {
      var forma = $('#origen').val();
      if(forma == 'Cuentas de terceros')
      {
        $("#cterceros").css("display", "block");
      }  else {
        $("#cterceros").css("display", "none");
        $("#coterceros").css("display", "none");
      }
    }

    function cccterceros()
    {
      var forma = $('#ccterceros').val();
      if(forma == 'Otros')
      {
        $("#coterceros").css("display", "block");
      }  else {
        $("#coterceros").css("display", "none");
      }
    }







    function table(data=null)
    {
      $('#amortizacion').DataTable( {
        dom: 'Bfrtip',
        searching: false,
        paging: false,
        ordering: false,
        destroy: true,
        processing:true,
        responsive: true,
        columnDefs: [
          {
            targets: [ 17, 18 ],
            visible: false,
            searchable: false
          },
        ],
        buttons: [
          {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            title: 'Amortización',
            text: 'Pdf'
          },
          {
            extend: 'print',
            text: 'Imprimir',
            pageSize: 'LEGAL',
            title: 'Amortización'
          }
        ],
        language: {
          "decimal": "",
          "emptyTable": "No hay información",
          "info": "",
          "infoEmpty": "",
          "infoFiltered": "(Filtrado de _MAX_ total entradas)",
          "infoPostFix": "",
          "thousands": ",",
          "lengthMenu": "Mostrar _MENU_ Entradas",
          "loadingRecords": "Cargando...",
          "processing": "Procesando...",
          "search": "Buscar:",
          "zeroRecords": "Sin resultados encontrados",
          "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
          }
        },
        columns: [
          {
            data: 'periodo',
            name: 'periodo'
          },
          {
            data: 'fechas',
            name: 'fechas'
          },
          {
            data: 'dias',
            name: 'dias'
          },
          {
            data: 'disposicion',
            name: 'disposicion'
          },
          {
            data: 'saldo_insoluto',
            name: 'saldo_insoluto'
          },
          {
            data: 'comision',
            name: 'comision'
          },
          {
            data: 'amortizacion',
            name: 'amortizacion'
          },
          {
            data: 'intereses',
            name: 'intereses'
          },
          {
            data: 'moratorios',
            name: 'moratorios'
          },
          {
            data: 'iva',
            name: 'iva'
          },
          {
            data: 'flujos',
            name: 'flujo'
          },
          {
            data: 'saldo_pendiente',
            name: 'saldo_pendiente'
          },
          {
            data: 'dias_mora',
            name: 'dias_mora'
          },
          {
            data: 'int_mora',
            name: 'int_mora'
          },
          {
            data: 'iva_mora',
            name: 'iva_mora'
          },
          {
            data: 'gcobranza',
            name: 'gcobranza'
          },
          {
            data: 'pagos',
            name: 'pagos'
          },
          {
            data: 'cflujos',
            name: 'cflujos'
          },
          {
            data: 'cstatus',
            name: 'cstatus'
          }
        ],
        ajax: {
          url: "/clientes/info/amortizacion/{{$id}}",
          data: {
            "data": data
          }
        },
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
          if( aData['saldo_pendiente'] != 0.00 && aData['saldo_pendiente'] != aData['cflujos'] ) {
            $('td', nRow).css('background-color', '#FFFC8E' );
            $('td', nRow).css('color', 'black' );
          } else if ( aData['cstatus'] == 1 )
          {
            $('td', nRow).css('background-color', '#7EAD74' );
            $('td', nRow).css('color', 'white' );
          } else if( aData['cstatus'] == 2 ) {
            $('td', nRow).css('background-color', '#DA8742' );
            $('td', nRow).css('color', 'white' );
          } else if( aData['cstatus'] == 3 ) {
            $('td', nRow).css('background-color', '#C1705E' );
            $('td', nRow).css('color', 'white' );
          }


          return nRow;
        }
      });

    }



    function historialFlujo(id)
    {
      $('#historialdeflujo').DataTable({
        dom: 'Bfrtip',
        searching: false,
        paging: false,
        ordering: false,
        destroy: true,
        processing: true,
        responsive: true,
        buttons: [
          {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            title: 'Amortización',
            text: 'Pdf'
          },
          {
            extend: 'print',
            text: 'Imprimir',
            pageSize: 'LEGAL',
            title: 'Amortización'
          }
        ],
        language: {
          "decimal": "",
          "emptyTable": "No hay información",
          "info": "",
          "infoEmpty": "",
          "infoFiltered": "(Filtrado de _MAX_ total entradas)",
          "infoPostFix": "",
          "thousands": ",",
          "lengthMenu": "Mostrar _MENU_ Entradas",
          "loadingRecords": "Cargando...",
          "processing": "Procesando...",
          "search": "Buscar:",
          "zeroRecords": "Sin resultados encontrados",
          "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
          }
        },
        columns: [
          {
            data: 'fecha',
            name: 'created_at'
          },
          {
            data: 'vmonto',
            name: 'monto'
          },
          {
            data: 'vcambio',
            name: 'cambio'
          },
          {
            data: 'descripcion',
            name: 'descripcion'
          },
        ],
        ajax: {
          url: "/morales/info/historial/flujo/" + id
        }
      });

      }


    function pagosAplicadoss(id)
    {
      $('#pagosAplicados').DataTable( {
        dom: 'Bfrtip',
        searching: false,
        paging: false,
        ordering: false,
        destroy: true,
        processing:true,
        responsive: true,
        buttons: [
          {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            title: 'Amortización',
            text: 'Pdf'
          },
          {
            extend: 'print',
            text: 'Imprimir',
            pageSize: 'LEGAL',
            title: 'Amortización'
          }
        ],
        language: {
          "decimal": "",
          "emptyTable": "No hay información",
          "info": "",
          "infoEmpty": "",
          "infoFiltered": "(Filtrado de _MAX_ total entradas)",
          "infoPostFix": "",
          "thousands": ",",
          "lengthMenu": "Mostrar _MENU_ Entradas",
          "loadingRecords": "Cargando...",
          "processing": "Procesando...",
          "search": "Buscar:",
          "zeroRecords": "Sin resultados encontrados",
          "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
          }
        },
        columns: [
          {
            data: 'fecha',
            name: 'fecha_pago'
          },
          {
            data: 'vmonto',
            name: 'monto'
          },
          {
            data: 'monto_restante',
            name: 'restante'
          },
          {
            data: 'vmonto_total',
            name: 'monto_total'
          },
          {
            data: 'descripcion',
            name: 'descripcion'
          },
          {
            data: 'vpago_restante',
            name: 'pago_restante'
          }
        ],
        ajax: {
          url: "/clientes/info/pagos/aplicados/"+id
        }
      });

    }


    function pagos(data=null)
    {
      $('#pagos').DataTable( {
        dom: 'Bfrtip',
        searching: false,
        paging: false,
        ordering: false,
        destroy: true,
        processing:true,
        responsive: true,
        buttons: [
          {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            title: 'Amortización',
            text: 'Pdf'
          },
          {
            extend: 'print',
            text: 'Imprimir',
            pageSize: 'LEGAL',
            title: 'Amortización'
          }
        ],
        language: {
          "decimal": "",
          "emptyTable": "No hay información",
          "info": "",
          "infoEmpty": "",
          "infoFiltered": "(Filtrado de _MAX_ total entradas)",
          "infoPostFix": "",
          "thousands": ",",
          "lengthMenu": "Mostrar _MENU_ Entradas",
          "loadingRecords": "Cargando...",
          "processing": "Procesando...",
          "search": "Buscar:",
          "zeroRecords": "Sin resultados encontrados",
          "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
          }
        },
        columns: [
          {
            data: 'periodo',
            name: 'periodo'
          },
          {
            data: 'fpago',
            name: 'fpago'
          },
          {
            data: 'mora',
            name: 'mora'
          },
          {
            data: 'imora',
            name: 'imora'
          },
          {
            data: 'condonacion',
            name: 'condonacion'
          },
          {
            data: 'iva',
            name: 'iva'
          },
          {
            data: 'pago',
            name: 'pago'
          },
          {
            data: 'comprobante',
            name: 'comprobante'
          }
        ],
        ajax: {
          url: "/clientes/info/pagos/{{$id}}",
          data: {
            "data": data
          }
        }
      });

    }

    function tasas(data=null)
    {
      $('#ttasas').DataTable( {
        dom: 'Bfrtip',
        searching: false,
        paging: false,
        ordering: false,
        destroy: true,
        processing:true,
        responsive: true,
        buttons: [
          {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            title: 'Amortización',
            text: 'Pdf'
          },
          {
            extend: 'print',
            text: 'Imprimir',
            pageSize: 'LEGAL',
            title: 'Amortización'
          }
        ],
        language: {
          "decimal": "",
          "emptyTable": "No hay información",
          "info": "",
          "infoEmpty": "",
          "infoFiltered": "(Filtrado de _MAX_ total entradas)",
          "infoPostFix": "",
          "thousands": ",",
          "lengthMenu": "Mostrar _MENU_ Entradas",
          "loadingRecords": "Cargando...",
          "processing": "Procesando...",
          "search": "Buscar:",
          "zeroRecords": "Sin resultados encontrados",
          "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
          }
        },

        columns: [
          {
            data: 'tcredito',
            name: 'tcredito'
          },
          {
            data: 'fpago',
            name: 'fpago'
          },
          {
            data: 'frecuencia',
            name: 'frecuencia'
          },
          {
            data: 'plazo',
            name: 'plazo'
          },
          {
            data: 'amortizacion',
            name: 'amortizacion'
          },
          {
            data: 'iva',
            name: 'iva'
          },
          {
            data: 'tasa',
            name: 'tasa'
          },
          {
            data: 'moratorio',
            name: 'moratorio'
          }
        ],
        ajax: {
          url: "/clientes/info/tasas/{{$id}}",
          data: {
            "data": data
          }
        }
      });

    }


    function credito(data=null)
    {
      $('#credito').DataTable( {
        dom: 'Bfrtip',
        searching: false,
        paging: false,
        ordering: false,
        destroy: true,
        processing:true,
        responsive: true,
        buttons: [
          {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            title: 'Amortización',
            text: 'Pdf'
          },
          {
            extend: 'print',
            text: 'Imprimir',
            pageSize: 'LEGAL',
            title: 'Amortización'
          }
        ],
        language: {
          "decimal": "",
          "emptyTable": "No hay información",
          "info": "",
          "infoEmpty": "",
          "infoFiltered": "(Filtrado de _MAX_ total entradas)",
          "infoPostFix": "",
          "thousands": ",",
          "lengthMenu": "Mostrar _MENU_ Entradas",
          "loadingRecords": "Cargando...",
          "processing": "Procesando...",
          "search": "Buscar:",
          "zeroRecords": "Sin resultados encontrados",
          "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
          }
        },
        columns: [
          {
            data: 'tcredito',
            name: 'tcredito'
          },
          {
            data: 'contrato',
            name: 'contrato'
          },

          {
            data: 'monto',
            name: 'monto'
          },
          {
            data: 'fpago',
            name: 'fpago'
          },
          {
            data: 'frecuencia',
            name: 'frecuencia'
          },
          {
            data: 'plazo',
            name: 'plazo'
          },
          {
            data: 'amortizacion',
            name: 'amortizacion'
          },
          {
            data: 'iva',
            name: 'iva'
          },
          {
            data: 'tasa',
            name: 'tasa'
          },
          {
            data: 'disposicion',
            name: 'disposicion'
          },
          {
            data: 'status',
            name: 'status'
          }
        ],
        ajax: {
          url: "/clientes/info/credito/{{$id}}",
          data: {
            "data": data
          }
        }
      });

    }

  </script>
@endsection
