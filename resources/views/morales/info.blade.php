@extends('layouts/contentLayoutMaster')
@section('title', 'Información')
@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/pages/invoice.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">

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
                     aria-controls="profile-just" aria-selected="true">AMORTIZACIÓN</a>
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
                                                href="{{ url('/uploads/personas-morales/ine/'.$dato->id.'-frontal.jpg') }}"
                                                target="_blank"> <img
                                                  src="{{ url('/uploads/personas-morales/ine/'.$dato->id.'-frontal.jpg') }}"
                                                  alt="INE" height="100"></a>

                                              <a
                                                href="{{ url('/uploads/personas-morales/ine/'.$dato->id.'-trasera.jpg') }}"
                                                target="_blank"> <img
                                                  src="{{ url('/uploads/personas-morales/ine/'.$dato->id.'-trasera.jpg') }}"
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
                                          <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="firstName3">
                                                Ciudad o Población
                                              </label>
                                              <select disabled class="form-control" name="city" id="ciudad">
                                                <option selected disabled>{{$datos->city}}</option>
                                              </select>
                                            </div>
                                          </div>
                                          <div class="col-md-6">
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
                                          <div class="col-md-6">
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
                                          <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="firstName3">
                                                Nacionalidad Antecedente
                                              </label>
                                              <select disabled class="form-control" name="nacionalidad_ante"
                                                      id="nacionalidad_ante">
                                                <option selected disabled>Seleccionar</option>
                                                @foreach($nacionantecedentes as $naciona)
                                                  <option
                                                    value="{{$naciona->descripcion}}">{{$naciona->descripcion}}</option>
                                                @endforeach
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
                                            <a href="{{ url('/uploads/morales/acta/'.$dato->id.'.jpg') }}"
                                               target="_blank"> <img
                                                src="{{ url('/uploads/morales/acta/'.$dato->id.'.jpg') }}"
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
                                            <a href="{{ url('/uploads/morales/dom/'.$dato->id.'.jpg') }}"
                                               target="_blank"> <img
                                                src="{{ url('/uploads/morales/dom/'.$dato->id.'.jpg') }}"
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
                                            <a href="{{ url('/uploads/morales/rfc/'.$dato->id.'.jpg') }}"
                                               target="_blank"> <img
                                                src="{{ url('/uploads/morales/rfc/'.$dato->id.'.jpg') }}"
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
                                          <a href="{{ url('/uploads/morales/acta/'.$dato->id.'.jpg') }}"
                                             target="_blank"> <img
                                              src="{{ url('/uploads/morales/acta/'.$dato->id.'.jpgg') }}"
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
                                          <a href="{{ url('/uploads/morales/acta/'.$dato->id.'.jpg') }}"
                                             target="_blank"> <img
                                              src="{{ url('/uploads/morales/acta/'.$dato->id.'.jpg') }}"
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
                    <div style="height: 420px;" class="col-12">
                      <label style="font-size: 18px" id="limite_c">Limite de Crédito : <label style="color: green;font-size: 18px">${{number_format($datos->limite_credito,2,'.',',')}}</label></label><br>
                      @if($datos->credito_disponible==null)
                      <label style="font-size: 18px" id="disponible">Crédito Disponible: <label style="color: #ecec00;font-size: 18px" >$0</label></label>
                      @else
                        <label style="font-size: 18px" id="disponible">Crédito Disponible: <label style="color: #ecec00;font-size: 18px">${{number_format($datos->credito_disponible,2,'.',',')}}</label></label>
                      @endif
                        <div class="card">
                        <div class="card-content">
                          <div class="row card-body card-dashboard">
                            <div class="col-8">
                              <button id="btnContrato" type="button" class="btn btn-outline-warning" data-toggle="collapse" data-target="#demo"></button>

                              <div id="demo" style="position: relative" class="collapse">
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
                            <div class="col-4">
                              <a class="btn btn-primary" href="/morales/continuar/{{$id}}">Nuevo Crédito</a>
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
        <form action="/morales/credito/pago" enctype="multipart/form-data" method="POST"
              class="steps-validation wizard-circle" id="formssp" name="formss">
          @csrf
          <input type="hidden" name="id" value="{{$id}}">

          <div class="modal-body">
            <label>Monto: </label>
            <div class="form-group">
              <input type="number" name="monto" step="any" min="0" placeholder="$" class="form-control required"
                     required>
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
              <input type='text' class="form-control " placeholder="Moneda" id="nmoneda" name="nmoneda"/>
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
              <input type='text' class="form-control " placeholder="Forma de Pago" id="nforma" name="nforma"/>
            </div>

            <div style="display:none" id="trforma">
              <label>Lugar de Pago </label>
              <div class="form-group">
                <select class="form-control" id="clforma" name="forma" onchange="lforma()">
                  <option selected disabled>Seleccionar</option>
                  <option value="Internacional">Internacional</option>
                  <option value="Nacional">Nacional</option>
                </select>
              </div>
            </div>

            <div style="display:none" id="lnacional">
              <label>Nacional </label>
              <div class="form-group">
                <select class="form-control" id="clnacional" name="lnacional">
                  <option selected disabled>Seleccionar</option>
                  <option value="En la plaza">En la plaza</option>
                  <option value="En otros estados de la república">En otros estados de la república</option>
                  <option value="En zona fronteriza">En zona fronteriza</option>
                </select>
              </div>
            </div>

            <div style="display:none" id="linternacional">
              <label>Internacional </label>
              <div class="form-group">
                <select class="form-control" id="clinternacional" name="linternacional">
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
                <option value="En el caso de créditos de nómina, del empleador">En el caso de créditos de nómina, del
                  empleador
                </option>
                <option value="Cuentas de terceros">Cuentas de terceros</option>
                <option value="No identificado">No identificado</option>
              </select>
            </div>

            <div style="display:none" id="cterceros">
              <label>Cuentas de terceros </label>
              <div class="form-group">
                <select class="form-control" id="ccterceros" name="cterceros" onchange="cccterceros()">
                  <option selected disabled>Seleccionar</option>
                  <option value="Relacionados en listas negras">Relacionados en listas negras</option>
                  <option value="Otros">Otros</option>
                </select>
              </div>
            </div>

            <div style="display:none" id="coterceros">
              <label>Otros Cuentas de terceros </label>
              <div class="form-group">
                <select class="form-control" id="ccterceros" name="cterceros">
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
              <input type='text' class="form-control pickadate-disable required" id="fecha" value="{{date('Y-m-d')}}"
                     name="fecha" required/>
            </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Aplicar</button>
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
                <th>Fecha</th>
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

  <div class="modal fade text-left" id="vcondonar" tabindex="-1" role="dialog"
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
            <table class="table table-striped table-bordered" id="condonarflujos">
              <thead>
              <tr>
                <th>Intereses</th>
                <th>Moratorios</th>
                <th>Gastos Cobranza</th>
                <th>Todo</th>
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
  <!-- invoice functionality end -->


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
  <script src="/js/scripts/vue.min.js"></script>

  <script src="{{ asset('js/curp.js') }}?{{rand()}}"></script>
  <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC2KCt-r7yAiuktmaXWVtTaVjilIcCPFM&callback=initMap&libraries=&v=weekly"
    async
  ></script>
  <script src="/js/scripts/mapsedit.js"></script>
  <script>

    $(document).ready(function () {
      $('#inlineForm').on('hidden.bs.modal', function () {
        $('#formssp')[0].reset()
      });
      $.ajax(
        {
          method:"get",
          url: "/morales/info/credito/{{$id}}",
          success:function (response)
          {

          }
        }
      )
      $.ajax({
        method: "get",
        url: "/morales/info/credito/{{$id}}",
        success: function (response) {
          $('#btnContrato').text("Contrato: " + response.data[0].contrato)
        }

      })

      function credito(data = null) {
        $('#credito').DataTable({
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
            url: "/morales/info/credito/{{$id}}",
            data: {
              "data": data
            }
          }
        });

      }

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
          [2019, 3, 6],
          [2019, 3, 20]
        ],
        format: 'yyyy-mm-dd',
        formatSubmit: 'yyyy-mm-dd',
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'

      });

    });

    function restaurar(id) {
      $.post('/morales/amortizacion/restaurar', {
        id: id,
        _token: token
      }, function (data) {
        location.reload();

      });

    }

    function cmoneda() {
      var moneda = $('#moneda').val();
      if (moneda == 0) {
        $("#cssmoneda").css("display", "block");
      } else {
        $("#cssmoneda").css("display", "none");
      }
    }

    function verpagos(id) {
      pagosAplicadoss(id);
      $('#vpagos').modal('toggle');
    }

    function verflujos(id) {
      historialFlujo(id);
      $('#vflujos').modal('toggle');
    }

    function condonar(id) {
      condonarFlujo(id);
      $('#vcondonar').modal('toggle');
    }


    function cforma() {
      var forma = $('#forma').val();
      if (forma == 0) {
        $("#cssforma").css("display", "block");
      } else if (forma == 'Transferencia') {
        $("#trforma").css("display", "block");
        $("#cssforma").css("display", "none");
      } else {
        $("#trforma").css("display", "none");
        $("#cssforma").css("display", "none");
      }
    }

    function lforma() {
      var forma = $('#clforma').val();
      if (forma == 'Nacional') {
        $("#lnacional").css("display", "block");
        $("#linternacional").css("display", "none");
      } else if (forma == 'Internacional') {
        $("#linternacional").css("display", "block");
        $("#lnacional").css("display", "none");
      } else {
        $("#lnacional").css("display", "none");
        $("#linternacional").css("display", "none");
      }
    }

    function corigen() {
      var forma = $('#origen').val();
      if (forma == 'Cuentas de terceros') {
        $("#cterceros").css("display", "block");
      } else {
        $("#cterceros").css("display", "none");
        $("#coterceros").css("display", "none");
      }
    }

    function cccterceros() {
      var forma = $('#ccterceros').val();
      if (forma == 'Otros') {
        $("#coterceros").css("display", "block");
      } else {
        $("#coterceros").css("display", "none");
      }
    }


    function table(data = null) {
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
          url: "/morales/info/amortizacion/{{$id}}",
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


    function historialFlujo(id) {
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

    function condonarFlujo(id) {
      $('#condonarflujos').DataTable({
        dom: 'Bfrtip',
        searching: false,
        paging: false,
        ordering: false,
        destroy: true,
        processing: true,
        responsive: false,
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
            data: 'dintereses',
            name: 'dintereses'
          },
          {
            data: 'dmoratorios',
            name: 'dmoratorios'
          },
          {
            data: 'dcobranza',
            name: 'dcobranza'
          },
          {
            data: 'dtodo',
            name: 'dtodo'
          },
        ],
        ajax: {
          url: "/morales/info/condonar/flujo/" + id
        }
      });

    }


    function pagosAplicadoss(id) {
      $('#pagosAplicados').DataTable({
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
          url: "/morales/info/pagos/aplicados/" + id
        }
      });

    }


    function pagos(data = null) {
      $('#pagos').DataTable({
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
          url: "/morales/info/pagos/{{$id}}",
          data: {
            "data": data
          }
        }
      });

    }

    function tasas(data = null) {
      $('#ttasas').DataTable({
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
          url: "/morales/info/tasas/{{$id}}",
          data: {
            "data": data
          }
        }
      });

    }


  </script>
@endsection
