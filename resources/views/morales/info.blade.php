@extends('layouts/contentLayoutMaster')
@section('title', 'Información')
@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/pages/invoice.css')) }}">

@endsection
@section('content')

  <!-- invoice functionality start -->
  <section id="nav-justified">
    <div class="row">
      <div class="col-sm-12">
        <div class="card overflow-hidden">
          <div class="card-content">
            <div class="card-body">

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
                  <a class="nav-link" id="profile-tab-amortizacion" data-toggle="tab" href="#profile-amortizacion"
                     role="tab"
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
                        <button class="btn btn-primary btn-print mb-1 mb-md-0"><i class="feather icon-file-text"></i>
                          Imprimir
                        </button>
                        <!-- <button class="btn btn-outline-primary  ml-0 ml-md-1"> <i class="feather icon-download"></i> Descargar</button> -->
                      </div>
                    </div>
                  </section>
                  <section id="validation">
                    <div class="row">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-content">
                            <div class="card-body">
                              <form action="/morales/crear" enctype="multipart/form-data" method="POST"
                                    class="steps-validation wizard-circle" id="formss" name="formss">
                                <form-wizard color="#7367f0" title="" subtitle="" next-button-text="Siguiente"
                                             next-button-text="Siguiente"
                                             back-button-text="Anterior" finish-button-text="Finalizar"
                                             @on-complete="onComplete" @on-error="handleErrorMessage">
                                @csrf
                                <!-- Step 1 -->

                                  <tab-content title="Sociedad" icon="step-icon feather icon-user"
                                               :before-change="()=>validateAsync('paso0')">
                                    <div id="paso0">
                                      <div class="row">
                                        <h3>Empresa</h3>
                                        <div class="col-md-12">
                                          <hr>
                                        </div>
                                        <div class="row">
                                          <div class="form-group col" id="div-1">
                                            <label for="nombre">
                                              Nombre de la empresa
                                            </label>
                                            <input type="text" class="form-control required" disabled name="nombre"
                                                   id="nombreEmpresa" value="{{$datos->nombre}}">
                                          </div>
                                        </div>

                                        <div class="row">
                                          <div class="form-group col-md-6" id="div-1">
                                            <label for="nombre_administrador">
                                              Nombre (s) completo del administrador (es), Director, Gerente General o
                                              Apoderado Legal que, con su firma, puedan obligar a la persona moral para
                                              efectos de la celebración de un contrato o realización de la Operación que
                                              de trate
                                            </label>
                                            <input type="text" class="form-control required" name="nombre_administrador"
                                                   id="nombre_administrador" disabled
                                                   value="{{$datos->nombre_administrador}}">
                                          </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <h3>Integrantes</h3>
                                        <div class="col-md-12">
                                          <hr>
                                        </div>
                                      </div>
                                      @foreach($datos->personasmorales as $dato)
                                        <div>
                                          <h1> Socio: {{$dato->name}}</h1>
                                        </div>
                                        <div id="integrantes" v-for="(integrante, index) in integrantes">
                                          <div v-if="index > 0 ">
                                            <div class="row">
                                              <div class="col-12 mt-1 mb-1">

                                              </div>
                                            </div>
                                          </div>

                                          <div class="row">
                                            <div class="offset-6 col-3 text-center">
                                              <a
                                                href="{{ url('/uploads/personas-morales/ine/'.$dato->id.'-frontal.png') }}"
                                                target="_blank"> <img
                                                  src="{{ url('/uploads/personas-morales/ine/'.$dato->id.'-frontal.png') }}"
                                                  alt="INE" height="100"></a>

                                              <a
                                                href="{{ url('/uploads/personas-morales/ine/'.$dato->id.'-trasera.png') }}"
                                                target="_blank"> <img
                                                  src="{{ url('/uploads/personas-morales/ine/'.$dato->id.'-trasera.png') }}"
                                                  alt="INE" height="100"></a>
                                            </div>

                                          </div>
                                          <div class="row">
                                            <div class="col-md-4 offset-md-4">

                                            </div>
                                          </div>
                                          <div class="row" id="firstRow">
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="firstName3">
                                                  Nombre(s)
                                                </label>
                                                <input disabled value="{{$dato->name}}" type="text"
                                                       class="form-control required" :id="'nombre'+index"
                                                       :name="'personasMorales['+index+'][name]'">
                                              </div>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="lastName3">
                                                  Apellido Paterno
                                                </label>
                                                <input disabled value="{{$dato->lastname}}" type="text"
                                                       class="form-control required" :id="'apellidop'+index"
                                                       :name="'personasMorales['+index+'][lastname]'">
                                              </div>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="lastName3">
                                                  Apellido Materno
                                                </label>
                                                <input disabled value="{{$dato->o_lastname}}" type="text"
                                                       class="form-control required" :id="'apellidom'+index"
                                                       :name="'personasMorales['+index+'][o_lastname]'">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="firstName3">
                                                  Genero
                                                </label>
                                                <select disabled class="form-control"
                                                        :name="'personasMorales['+index+'][gender]'"
                                                        :id="'genero'+index">

                                                  @if($dato->gender=="H")
                                                    <option selected value="H">Masculino</option>
                                                  @else
                                                    <option selected value="M">Femenino</option>
                                                  @endif
                                                </select>
                                              </div>
                                            </div>

                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="lastName3">
                                                  Fecha de Nacimiento
                                                </label>
                                                <input disabled value="{{$dato->date_birth}}" type='text'
                                                       class="form-control" data-toggle="datepicker"
                                                       :name="'personasMorales['+index+'][date_birth]'"
                                                       :id="'nacimiento'+index">

                                              </div>
                                            </div>

                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="firstName3">
                                                  País de Nacimiento
                                                </label>
                                                <select disabled class="form-control" :id="'pais_nacimiento'+index"
                                                        :name="'personasMorales['+index+'][country_birth]'">
                                                  @foreach($paises as $pais)
                                                    @if($pais->code==$dato->country_birth)
                                                      <option>{{ $pais->pais }}</option>

                                                    @endif
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
                                                <select disabled class="form-control" :id="'lnacimiento'+index"
                                                        :name="'personasMorales['+index+'][place_birth]'">


                                                  @foreach($entidad as $entidades)
                                                    @if($entidades->code==$dato->place_birth)
                                                      <option value="">{{ $entidades->entity }}</option>
                                                    @endif
                                                  @endforeach
                                                </select>
                                              </div>
                                            </div>

                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="firstName3">
                                                  Nacionalidad
                                                </label>
                                                <select disabled class="form-control" :id="'nacionalidad'+index"
                                                        :name="'personasMorales['+index+'][nationality]'">

                                                  @foreach($nacionalidades as $nacionalidad)
                                                    @if($nacionalidad->code==$dato->nationality)
                                                      <option value="">{{ $nacionalidad->country }}</option>
                                                    @endif
                                                  @endforeach
                                                </select>
                                              </div>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="lastName3">
                                                  Ocupación
                                                </label>
                                                <input disabled value="{{$dato->job}}" type="text"
                                                       class="form-control required"
                                                       :name="'personasMorales['+index+'][job]'">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">

                                          </div>
                                        </div>
                                      @endforeach
                                    </div>

                                  </tab-content>

                                  <!-- Step 2 -->
                                  <tab-content title="Dirección" icon="step-icon feather icon-map-pin"
                                               :before-change="()=>validateAsync('paso1')">
                                    <div id="paso1">
                                      <div class="row">
                                        <div class="row">
                                          <h3>Dirección</h3>
                                          <div class="col-md-12">
                                            <hr>
                                          </div>
                                          <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="proposalTitle3">
                                                Calle
                                              </label>
                                              <input disabled value="{{$datos->street}}" type="text"
                                                     class="form-control required" id="street" name="street">
                                            </div>
                                          </div>
                                          <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="proposalTitle3">
                                                # Exterior
                                              </label>
                                              <input disabled value="{{$datos->exterior}}" type="text"
                                                     class="form-control required" name="exterior" id="exterior">
                                            </div>
                                          </div>
                                          <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="proposalTitle3">
                                                # Interior
                                              </label>
                                              <input disabled value="{{$datos->intern}}" type="text"
                                                     class="form-control " name="inside">
                                            </div>
                                          </div>
                                          <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="proposalTitle3">
                                                Codigo Postal
                                              </label>
                                              <input disabled value="{{$datos->pc}}" type="text"
                                                     class="form-control required" name="pc" id="cp" @change="sepomex">
                                            </div>
                                          </div>

                                          <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="firstName3">
                                                Colonia
                                              </label>
                                              <select disabled class="form-control" name="colony" id="colonia">
                                                <option selected disabled>{{$datos->colony}}</option>
                                              </select>
                                            </div>
                                          </div>
                                          <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="firstName3">
                                                Alcaldia o Municipio
                                              </label>
                                              <select disabled class="form-control" name="town" id="municipio">
                                                <option selected disabled>{{$datos->town}}</option>
                                              </select>
                                            </div>
                                          </div>
                                          <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="firstName3">
                                                Ciudad o Población
                                              </label>
                                              <select disabled class="form-control" name="city" id="ciudad">
                                                <option selected disabled>{{$datos->city}}</option>
                                              </select>
                                            </div>
                                          </div>
                                          <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="firstName3">
                                                Entidad Federativa
                                              </label>
                                              <select disabled class="form-control" id="entidad" name="ef"
                                                      @change="initMap">
                                                <option selected disabled>Seleccionar</option>
                                                @foreach($entidad as $dd)
                                                  @if($dd->code==$datos->ef)
                                                    <option selected value="">{{ $dd->entity }}</option>
                                                  @endif
                                                @endforeach
                                              </select>
                                            </div>
                                          </div>
                                          <div class="col-md-4">
                                            <div class="form-group">
                                              <label for="firstName3">
                                                País
                                              </label>
                                              <select disabled class="form-control" id="pais" name="country">
                                                <option disabled>Seleccionar</option>
                                                <option selected value="México">México</option>
                                              </select>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </tab-content>

                                  <!-- Step 3 -->
                                  <tab-content title="Datos Adicionales" icon="step-icon feather icon-file-plus"
                                               :before-change="()=>validateAsync('paso2')">
                                    <div id="paso2">
                                      <div class="row">
                                        <h3>Datos adicionales</h3>
                                        <div class="col-md-12">
                                          <hr>
                                        </div>
                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="firstName3">
                                              Número Teléfonico 1
                                            </label>
                                            <input disabled value="{{$datos->phone1}}" type="text" class="form-control"
                                                   name="phone1">
                                          </div>
                                        </div>

                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="lastName3">
                                              Número Teléfonico 2
                                            </label>
                                            <input disabled value="{{$datos->phone2}}" type="text" class="form-control "
                                                   name="phone2">
                                          </div>
                                        </div>
                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="lastName3">
                                              Email
                                            </label>
                                            <input disabled value="{{$datos->email}}" type="text"
                                                   class="form-control required" id="memail" name="email"
                                                   @change="checkemail">
                                          </div>
                                        </div>
                                      </div>
                                      <div class="row">


                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="lastName3">
                                              RFC
                                            </label>
                                            <input disabled value="{{$datos->rfc}}" type="text"
                                                   class="form-control required" name="rfc" id="rfc">
                                          </div>
                                        </div>
                                      </div>

                                    </div>
                                  </tab-content>
                                  <!-- Step 4 -->

                                  <tab-content title="Documentos Requeridos" icon="step-icon feather icon-folder-plus"
                                               :before-change="()=>validateAsync('paso3')">
                                    <div id="paso3">
                                      <div class="row">
                                        <h3>Documentos requeridos</h3>
                                        <div class="col-md-12">
                                          <hr>
                                        </div>
                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="eventName3">
                                              Acta Constitutiva
                                            </label>
                                            <br>
                                            <a href="{{ url('/uploads/morales/acta/'.$dato->id.'.jpeg') }}"
                                               target="_blank"> <img
                                                src="{{ url('/uploads/morales/acta/'.$dato->id.'.jpeg') }}"
                                                alt="Acta Constitutiva" height="100"></a></div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="eventName3">
                                              Comprobante de Domicilio
                                            </label>
                                            <br>
                                            <a href="{{ url('/uploads/morales/dom/'.$dato->id.'.jpeg') }}"
                                               target="_blank"> <img
                                                src="{{ url('/uploads/morales/dom/'.$dato->id.'.jpeg') }}"
                                                alt="Comprobante de Domicilio" height="100"></a>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="eventName3">
                                              RFC
                                            </label>
                                            <br>
                                            <a href="{{ url('/uploads/morales/rfc/'.$dato->id.'.jpeg') }}"
                                               target="_blank"> <img
                                                src="{{ url('/uploads/morales/rfc/'.$dato->id.'.jpeg') }}"
                                                alt="RFC" height="100"></a>
                                            <br>
                                            <h3>Croquis</h3>
                                            <hr>
                                            <div title="maps" id="map"
                                                 style="position: initial !important; height: 400px"></div>
                                            <input value="{{$datos->lat}}" aria-label="latitud" id="lat" name="lat"
                                                   hidden>
                                            <input value="{{$datos->long}}" aria-label="longitud" id="long" name="long"
                                                   hidden>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </tab-content>
                                  <tab-content title="Croquis" icon="step-icon feather icon-folder-plus"
                                               :before-change="()=>validateAsync('paso4')">
                                    <div id="paso4">
                                      <div class="row">

                                        <div class="col-md-12">
                                          <hr>
                                        </div>
                                        <div class="col-md-4">
                                          <div class="form-group">

                                            <input value="{{$datos->lat}}" aria-label="latitud" id="lat" name="lat"
                                                   hidden>
                                            <input value="{{$datos->long}}" aria-label="longitud" id="long" name="long"
                                                   hidden>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </tab-content>
                                  <tab-content title="Documentos" icon="step-icon feather icon-folder-plus"
                                               :before-change="()=>validateAsync('paso5')">
                                    <div id="paso5"></div>
                                    <div class="row">
                                      <h3>Documentos</h3>
                                      <div class="col-md-12">
                                        <hr>
                                      </div>
                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <label for="giro">
                                            Giro
                                          </label>
                                          <input disabled value="{{$datos->giro}}" id="giro" type="text"
                                                 class="form-control required" name="giro">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <label for="fecha_constitucion">
                                            Fecha de constitucion
                                          </label>
                                          <input value="{{$datos->fecha_constitucion}}" disabled id="fecha_constitucion"
                                                 type="date" class="form-control required" name="fecha_constitucion">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <label for="garantias">
                                            Descripcion de las garantias
                                          </label>
                                          <textarea disabled id="garantias" type="text" class="form-control required"
                                                    name="garantias">{{$datos->garantias}}
                        </textarea>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <label for="fotografia1">
                                            Fotografia 1
                                          </label>
                                          <br>
                                          <a href="{{ url('/uploads/morales/acta/'.$dato->id.'.jpeg') }}"
                                             target="_blank"> <img
                                              src="{{ url('/uploads/morales/acta/'.$dato->id.'.jpeg') }}"
                                              alt="Fotografia 1" height="100"></a></div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <label for="fotografia2">
                                            Fotografia 2
                                          </label>
                                          <br>
                                          <a href="{{ url('/uploads/morales/acta/'.$dato->id.'.jpeg') }}"
                                             target="_blank"> <img
                                              src="{{ url('/uploads/morales/acta/'.$dato->id.'.jpeg') }}"
                                              alt="Fotografia 2" height="100"></a></div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <label for="numero_empleados">
                                            Numero de empleados
                                          </label>
                                          <input disabled value="{{$datos->numero_empleados}}" id="numero_empleados"
                                                 type="number" class="form-control required" name="numero_empleados">
                                        </div>
                                      </div>
                                    </div>

                                  </tab-content>
                                </form-wizard>
                              </form>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </section>
                  <!-- invoice functionality end -->

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
                        <button class="btn btn-success mb-1 mb-md-0" data-toggle="modal" data-target="#inlineForm"><i
                            class="feather icon-dollar-sign"></i> Agregar Pago
                        </button>
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

  <!-- invoice functionality end -->


@endsection
@section('page-script')
  <!-- Page js files -->
  <script src="{{ asset(mix('js/scripts/pages/invoice.js')) }}"></script>
  <script src="/js/scripts/vue.min.js"></script>

  <script src="{{ asset('js/curp.js') }}?{{rand()}}"></script>
  <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC2KCt-r7yAiuktmaXWVtTaVjilIcCPFM&callback=initMap&libraries=&v=weekly"
    async
  ></script>
  <script src="/js/scripts/mapsedit.js"></script>
@endsection
