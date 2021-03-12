@extends('layouts/contentLayoutMaster')

@section('title', $pageConfigs['pageName'] )

@section('page-style')
  <!-- Page css files -->
  link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/wizard.css')) }}">
  <link rel="stylesheet" href="{{ asset('datepicker/datepicker.css') }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
  <link rel="stylesheet" href="https://unpkg.com/vue-form-wizard/dist/vue-form-wizard.min.css">
  <style>

    #WindowLoad {
      position: fixed;
      top: 0px;
      left: 0px;
      z-index: 3200;
      filter: alpha(opacity=85);
      -moz-opacity: 85;
      opacity: 0.85;
      background: #ededed;
    }

    span.error {
      color: #e74c3c;
      font-size: 20px;
      display: flex;
      justify-content: center;
    }

    @media (min-width: 720px) {
      #map {
        width: 300%;
      }

    }
  </style>

@endsection
@section('content')


  {{-- Nav Justified Starts --}}
  <section id="nav-justified">
    <div class="row">
      <div class="col-sm-12">
        <div class="card overflow-hidden">
          <div class="card-content">
            <div class="card-body">
              <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab-justified" data-toggle="tab" href="#home-just" role="tab"
                     aria-controls="home-just" aria-selected="true">DATOS PERSONALES</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-justified" data-toggle="tab" href="#profile-just" role="tab"
                     aria-controls="profile-just" aria-selected="true">PERFIL TRANSACIONAL</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="messages-tab-justified" data-toggle="tab" href="#messages-just" role="tab"
                     aria-controls="messages-just" aria-selected="false">GRADO DE RIESGO</a>
                </li>
              </ul>

              {{-- Tab panes --}}
              <div class="tab-content pt-1">
                <div class="tab-pane active" id="home-just" role="tabpanel" aria-labelledby="home-tab-justified">
                  <div class="row">
                    <div class="col-12">
                      <div class="card">
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
                                              <div class="col-md-4">
                                                <div class="form-group" id="div-1">
                                                  <label for="nombre">
                                                    Nombre de la empresa
                                                  </label>
                                                  <input type="text" class="form-control required" name="nombre"
                                                         id="nombreEmpresa" required
                                                         data-validation-required-message="This First Name field is required">
                                                </div>
                                              </div>
                                              <div class="col-md-6">
                                                <div class="form-group" id="div-1">
                                                  <label for="nombre_administrador">
                                                    Nombre (s) completo del administrador (es), Director, Gerente
                                                    General o Apoderado Legal que, con su firma, puedan obligar a la
                                                    persona moral para efectos de la celebración de un contrato o
                                                    realización de la Operación que de trate
                                                  </label>
                                                  <input type="text" class="form-control required"
                                                         name="nombre_administrador" id="nombre_administrador" required
                                                         data-validation-required-message="This Name field is required">
                                                </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <h3>Integrantes</h3>
                                              <div class="col-md-12">
                                                <hr>
                                              </div>
                                            </div>
                                            <div id="integrantes" v-for="(integrante, index) in integrantes">
                                              <div v-if="index > 0 ">
                                                <div class="row">
                                                  <div class="col-12 mt-1 mb-1">
                                                    <div class="alert alert-info">
                                                      <p><i class="feather icon-info mr-1 align-middle"></i> Siguiente
                                                        Socio.</p>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="row">
                                                <div class="col-md-6">
                                                  <div class="form-group">
                                                    <label for="lastName3">
                                                      INE FRONTAL
                                                    </label>
                                                    <input type="file" @change="validateine(index)"
                                                           data-toggle="tooltip" data-placement="top"
                                                           title="Solo se permiten imagenes JPG, JPEG, PNG orientadas horizontalmente"
                                                           class="form-control required" :id="'inefront'+index"
                                                           :name="'personasMorales['+index+'][inefront]'"
                                                           accept=".jpg, .jpeg, .png">
                                                  </div>
                                                </div>
                                                <div class="col-md-6">
                                                  <div class="form-group">
                                                    <label for="lastName3">
                                                      INE TRASERA
                                                    </label>
                                                    <input type="file" @change="validateine(index)"
                                                           data-toggle="tooltip" data-placement="top"
                                                           title="Solo se permiten imagenes JPG, JPEG, PNG orientadas horizontalmente"
                                                           class="form-control required" :id="'ineback'+index"
                                                           :name="'personasMorales['+index+'][ineback]'"
                                                           accept=".jpg, .jpeg, .png">
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="row">
                                                <div class="col-md-4 offset-md-4">
                                                  <div class="form-group">
                                                    <label for="curp">
                                                      CURP
                                                    </label>
                                                    <input type="text" class="form-control required"
                                                           :name="'personasMorales['+index+'][curp]'"
                                                           :id="'curp'+index" @change="checkcurp(index)">
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="row" id="firstRow">
                                                <div class="col-md-4">
                                                  <div class="form-group">
                                                    <label for="firstName3">
                                                      Nombre(s)
                                                    </label>
                                                    <input type="text" class="form-control required"
                                                           :id="'nombre'+index"
                                                           :name="'personasMorales['+index+'][name]'">
                                                  </div>
                                                </div>
                                                <div class="col-md-4">
                                                  <div class="form-group">
                                                    <label for="lastName3">
                                                      Apellido Paterno
                                                    </label>
                                                    <input type="text" class="form-control required"
                                                           :id="'apellidop'+index"
                                                           :name="'personasMorales['+index+'][lastname]'">
                                                  </div>
                                                </div>
                                                <div class="col-md-4">
                                                  <div class="form-group">
                                                    <label for="lastName3">
                                                      Apellido Materno
                                                    </label>
                                                    <input type="text" class="form-control required"
                                                           :id="'apellidom'+index"
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
                                                    <select class="form-control"
                                                            :name="'personasMorales['+index+'][gender]'"
                                                            :id="'genero'+index">
                                                      <option selected disabled>Seleccionar</option>
                                                      <option value="H">Masculino</option>
                                                      <option value="M">Femenino</option>
                                                    </select>
                                                  </div>
                                                </div>

                                                <div class="col-md-4">
                                                  <div class="form-group">
                                                    <label for="lastName3">
                                                      Fecha de Nacimiento
                                                    </label>
                                                    <input type='text' class="form-control" data-toggle="datepicker"
                                                           :name="'personasMorales['+index+'][date_birth]'"
                                                           :id="'nacimiento'+index">

                                                  </div>
                                                </div>

                                                <div class="col-md-4">
                                                  <div class="form-group">
                                                    <label for="firstName3">
                                                      País de Nacimiento
                                                    </label>
                                                    <select class="form-control" :id="'pais_nacimiento'+index"
                                                            :name="'personasMorales['+index+'][country_birth]'">
                                                      <option selected disabled>Seleccionar</option>
                                                      @foreach($paises as $dd)
                                                        <option value="{{ $dd->code }}">{{ $dd->pais }}</option>
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
                                                    <select class="form-control" :id="'lnacimiento'+index"
                                                            :name="'personasMorales['+index+'][place_birth]'">
                                                      <option selected disabled>Seleccionar</option>
                                                      @foreach($entidad as $dd)
                                                        <option value="{{ $dd->code }}">{{ $dd->entity }}</option>
                                                      @endforeach
                                                    </select>
                                                  </div>
                                                </div>


                                                <div class="col-md-4">
                                                  <div class="form-group">
                                                    <label for="firstName3">
                                                      Nacionalidad
                                                    </label>
                                                    <select class="form-control" :id="'nacionalidad'+index"
                                                            :name="'personasMorales['+index+'][nationality]'">
                                                      <option selected disabled>Seleccionar</option>
                                                      @foreach($nacionalidades as $dd)
                                                        <option value="{{ $dd->code }}">{{ $dd->country }}</option>
                                                      @endforeach
                                                    </select>
                                                  </div>
                                                </div>
                                                <div class="col-md-4">
                                                  <div class="form-group">
                                                    <label for="lastName3">
                                                      Ocupación
                                                    </label>
                                                    <input type="text" class="form-control required"
                                                           :name="'personasMorales['+index+'][job]'">
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="row">
                                                <div class="col-md-6">
                                                  <button v-if="index == (integrantes.length -1)" type="button"
                                                          @click="agregarIntegrante"
                                                          class="btn btn-icon rounded-circle btn-outline-primary mr-1 mb-1 waves-effect waves-light">
                                                    <i
                                                      class="feather icon-plus"></i></button>
                                                </div>
                                                <div v-if="index > 1" class="col-md-6">
                                                  <button type="button" @click="removerIntegrante(index)"
                                                          class="btn btn-icon rounded-circle btn-outline-danger mr-1 mb-1 waves-effect waves-light float-right">
                                                    <i
                                                      class="feather icon-trash"></i></button>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </tab-content>

                                        <!-- Step 2 -->
                                        <tab-content title="Dirección" icon="step-icon feather icon-map-pin"
                                                     :before-change="()=>validateAsync('paso1')">
                                          <div id="paso1">
                                            <div class="row">
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="proposalTitle3">
                                                    Calle
                                                  </label>
                                                  <input type="text" class="form-control required" id="street"
                                                         name="street">
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="proposalTitle3">
                                                    # Exterior
                                                  </label>
                                                  <input type="text" class="form-control required" name="exterior"
                                                         id="exterior">
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="proposalTitle3">
                                                    # Interior
                                                  </label>
                                                  <input type="text" class="form-control " name="inside">
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="proposalTitle3">
                                                    Codigo Postal
                                                  </label>
                                                  <input type="text" class="form-control required" name="pc" id="cp"
                                                         @change="sepomex">
                                                </div>
                                              </div>

                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="firstName3">
                                                    Colonia
                                                  </label>
                                                  <select class="form-control" name="colony" id="colonia">
                                                    <option selected disabled>Seleccionar</option>
                                                  </select>
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="firstName3">
                                                    Alcaldia o Municipio
                                                  </label>
                                                  <select class="form-control" name="town" id="municipio">
                                                    <option selected disabled>Seleccionar</option>
                                                  </select>
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="firstName3">
                                                    Ciudad o Población
                                                  </label>
                                                  <select class="form-control" name="city" id="ciudad">
                                                    <option selected disabled>Seleccionar</option>
                                                  </select>
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="firstName3">
                                                    Entidad Federativa
                                                  </label>
                                                  <select class="form-control" id="entidad" name="ef" @change="initMap">
                                                    <option selected disabled>Seleccionar</option>
                                                    @foreach($entidad as $dd)
                                                      <option value="{{ $dd->code }}">{{ $dd->entity }}</option>
                                                    @endforeach
                                                  </select>
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="firstName3">
                                                    País
                                                  </label>
                                                  <select class="form-control" id="pais" name="country">
                                                    <option selected disabled>Seleccionar</option>
                                                    <option value="México">México</option>
                                                  </select>
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
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="firstName3">
                                                    Número Teléfonico 1
                                                  </label>
                                                  <input type="text" class="form-control" name="phone1">
                                                </div>
                                              </div>

                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="lastName3">
                                                    Número Teléfonico 2
                                                  </label>
                                                  <input type="text" class="form-control " name="phone2">
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="lastName3">
                                                    Email
                                                  </label>
                                                  <input type="text" class="form-control required" id="memail"
                                                         name="email"
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
                                                  <input type="text" class="form-control required" name="rfc" id="rfc">
                                                </div>
                                              </div>
                                            </div>
                                            {{-- <div class="row">
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <div class="vs-radio-con vs-radio-primary">
                                                  <input type="radio" name="vueradisize" value="false" onchange="conyuge()">
                                                  <span class="vs-radio vs-radio-lg">
                                                    <span class="vs-radio--border"></span>
                                                    <span class="vs-radio--circle"></span>
                                                  </span>
                                                  <span class="">Agregar Datos de Cónyuge</span>
                                                </div>
                                              </div>
                                            </div>
                                          </div>

                                          <div id="conyuge" style="display:none;">
                                            <div class="row">
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="firstName3">
                                                    Nombre(s)
                                                  </label>
                                                  <input type="text" class="form-control" name="cnombre">
                                                </div>
                                              </div>

                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="lastName3">
                                                    Apellido Paterno
                                                  </label>
                                                  <input type="text" class="form-control " name="capellidop">
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="lastName3">
                                                    Apellido Materno
                                                  </label>
                                                  <input type="text" class="form-control " name="capellidom">
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="lastName3">
                                                    Número Teléfonico
                                                  </label>
                                                  <input type="text" class="form-control " name="ctelefono">
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="lastName3">
                                                    Correo Eletrónico
                                                  </label>
                                                  <input type="text" class="form-control " name="cemail">
                                                </div>
                                              </div>
                                            </div>
                                          </div> --}}
                                          </div>
                                        </tab-content>
                                        <!-- Step 4 -->

                                        <tab-content title="Documentos Requeridos"
                                                     icon="step-icon feather icon-folder-plus"
                                                     :before-change="()=>validateAsync('paso3')">
                                          <div id="paso3">
                                            <div class="row">
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="eventName3">
                                                    Acta Constitutiva
                                                  </label>
                                                  <input type="file" class="form-control required" id="eventName3"
                                                         name="filecurp">
                                                </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="eventName3">
                                                    Comprobante de Domicilio
                                                  </label>
                                                  <input type="file" class="form-control required" id="eventName3"
                                                         name="filedom"
                                                         accept=".jpg, .jpeg, .png">
                                                </div>
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="eventName3">
                                                    RFC
                                                  </label>
                                                  <input type="file" class="form-control " id="eventName3"
                                                         name="filerfc"
                                                         accept=".jpg, .jpeg, .png">
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </tab-content>
                                        <tab-content title="Croquis" icon="step-icon feather icon-folder-plus"
                                                     :before-change="()=>validateAsync('paso4')">
                                          <div id="paso4">
                                            <div class="row">
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                  <label for="eventName3">
                                                    Croquis
                                                  </label>
                                                  <div title="maps" id="map" frameborder="0" allowfullscreen=""
                                                       style="position: initial !important; height: 400px"
                                                       aria-hidden="false" tabindex="0"></div>
                                                  <input aria-label="latitud" id="lat" name="lat" hidden>
                                                  <input aria-label="longitud" id="long" name="long" hidden>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </tab-content>
                                        <tab-content title="Documentos" icon="step-icon feather icon-folder-plus"
                                                     :before-change="()=>validateAsync('paso5')">
                                          <div id="paso5"></div>
                                          <div class="row">
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="giro">
                                                  Giro
                                                </label>
                                                <input id="giro" type="text" class="form-control required" name="giro">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="fecha_constitucion">
                                                  Fecha de constitucion
                                                </label>
                                                <input id="fecha_constitucion" type="date" class="form-control required"
                                                       name="fecha_constitucion">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="garantias">
                                                  Descripcion de las garantias
                                                </label>
                                                <textarea id="garantias" type="text" class="form-control required"
                                                          name="garantias">
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
                                                <input id="fotografia1" type="file" accept="image/png, image/jpeg"
                                                       class="form-control required" name="fotografia1">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="fotografia2">
                                                  Fotografia 2
                                                </label>
                                                <input id="fotografia2" type="file" accept="image/png, image/jpeg"
                                                       class="form-control required" name="fotografia2">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="numero_empleados">
                                                  Numero de empleados
                                                </label>
                                                <input id="numero_empleados" type="number" class="form-control required"
                                                       name="numero_empleados">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="entrevista">
                                                  Entrevista de Identificacion firmada con Declaración de que actua por
                                                  cuenta propia o de un tercero
                                                </label>
                                                <input id="entrevista" type="file" accept="application/pdf"
                                                       class="form-control required" name="entrevista">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="autorizacion_reporte_circulo_credito">
                                                  Autorización Reporte de Circulo de Crédito
                                                </label>
                                                <input id="autorizacion_reporte_circulo_credito" type="file"
                                                       accept="application/pdf" class="form-control required"
                                                       name="autorizacion_reporte_circulo_credito">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="reporte">
                                                  Reporte Visita Ocular
                                                </label>
                                                <input id="reporte" type="file" accept="application/pdf"
                                                       class="form-control required" name="reporte">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="ultima_declaracion_anual">
                                                  Última declaración anual de Impuestos
                                                </label>
                                                <input id="ultima_declaracion_anual" type="file"
                                                       accept="application/pdf" class="form-control required"
                                                       name="ultima_declaracion_anual">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="estados_financieros_anuales">
                                                  Estados Financieros Anuales del periodo anterior
                                                </label>
                                                <input id="estados_financieros_anuales" type="file"
                                                       accept="application/pdf" class="form-control required"
                                                       name="estados_financieros_anuales">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                <label for="estados_financieros_recientes">
                                                  Estados Financieros Recientes (no menos de 3 meses)
                                                </label>
                                                <input id="estados_financieros_recientes" type="file"
                                                       accept="application/pdf" class="form-control required"
                                                       name="estados_financieros_recientes">
                                              </div>
                                            </div>
                                          </div>
                                        </tab-content>
                                      </form-wizard>
                                    </form>
                                    <div v-if="errorMsg">
                                      <span class="error">@{{errorMsg}}</span>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </section>
                      </div>
                    </div>
                  </div>


                </div>
                <div class="tab-pane" id="profile-just" role="tabpanel" aria-labelledby="profile-tab-justified">


                  <form action="/clientes/fisicas/eperfil" method="POST" class="steps-validation wizard-circle"
                        id="formss" name="formss">
                    @csrf
                    <input type="hidden" class="form-control required" id="id" name="id">

                    <div class="form-body">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="firstName3">
                              Monto estimado de pagos a realizar en los próximos seis meses
                            </label>
                            <input type="number" value="@if(isset($datos->monto)){{$datos->monto}}@endif" step="any"
                                   class="form-control required" id="monto" name="monto">
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="lastName3">
                              Tipo de crédito que pretende utilizar en el siguiente semestre
                            </label>
                            <input type="text" value="@if(isset($datos->tcredito)){{$datos->tcredito}}@endif"
                                   class="form-control required" id="tcredito" name="tcredito">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="lastName3">
                              Frecuencia de los pagos que realizará en el siguiente semestre
                            </label>
                            <input type="text" value="@if(isset($datos->frecuencia)){{$datos->frecuencia}}@endif"
                                   class="form-control required" id="frecuencia" name="frecuencia">
                          </div>
                        </div>
                        <div class="col-12">
                          <h3>Origen de Recursos</h3>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="actividad"
                                     value="@if(isset($datos->actividad)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                                    <span class="vs-radio--border"></span>
                                    <span class="vs-radio--circle"></span>
                                  </span>
                              <span class="">Actividad propia del solicitante</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="propietario"
                                     value="@if(isset($datos->propietario)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                                  <span class="vs-radio--border"></span>
                                  <span class="vs-radio--circle"></span>
                                </span>
                              <span class="">Propietario Real</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="proovedor"
                                     value="@if(isset($datos->proovedor)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                                <span class="vs-radio--border"></span>
                                <span class="vs-radio--circle"></span>
                              </span>
                              <span class="">Proveedor de Recursos</span>
                            </div>
                          </div>
                        </div>

                        <div class="col-12">
                          <h3>Destino de los recursos </h3>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="dactividad"
                                     value="@if(isset($datos->dactividad)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                              <span class="vs-radio--border"></span>
                              <span class="vs-radio--circle"></span>
                            </span>
                              <span class="">Actividad propia del solicitante</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="dpasivos"
                                     value="@if(isset($datos->dpasivos)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                            <span class="vs-radio--border"></span>
                            <span class="vs-radio--circle"></span>
                          </span>
                              <span class="">Pago de pasivos</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="lastName3">
                              Otro
                            </label>
                            <input type="text" class="form-control required" id="dotro" name="dotro"
                                   value="@if(isset($datos->dotro)){{$datos->dotro}}@endif">
                          </div>
                        </div>


                        <div class="col-12">
                          <h3>Disponibilidad del cliente para la entrega de documentación </h3>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="total"
                                     value="@if(isset($datos->total)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                        <span class="vs-radio--border"></span>
                        <span class="vs-radio--circle"></span>
                      </span>
                              <span class="">Total</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="aceptable"
                                     value="@if(isset($datos->aceptable)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                      <span class="vs-radio--border"></span>
                      <span class="vs-radio--circle"></span>
                    </span>
                              <span class="">Aceptable</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="difisil"
                                     value="@if(isset($datos->difisil)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                    <span class="vs-radio--border"></span>
                    <span class="vs-radio--circle"></span>
                  </span>
                              <span class="">Difícil</span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="lastName3">
                              Especifique si observo alguna conducta inapropiada del cliente
                            </label>
                            <input type="text" class="form-control required" id="conducta" name="conducta"
                                   value="@if(isset($datos->conducta)){{$datos->conducta}}@endif">
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="lastName3">
                              Señale algún otro comentario sobre el cliente, que considere pueda incidir en la
                              definición de su perfil transaccional, como características específicas de su actividad,
                              antecedentes o proyectos
                            </label>
                            <input type="text" class="form-control required" id="comentario" name="comentario"
                                   value="@if(isset($datos->comentario)){{$datos->comentario}}@endif">
                          </div>
                        </div>
                        <div class="col-12">
                          <button type="submit" class="btn btn-primary mr-1 mb-1">Guardar</button>
                          <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Limpiar</button>
                          <a href="/clientes/fisica">
                            <button type="button" class="btn btn-outline-danger mr-1 mb-1">Cancelar</button>
                          </a>
                        </div>

                      </div>
                    </div>
                  </form>

                </div>
                <div class="tab-pane" id="messages-just" role="tabpanel" aria-labelledby="messages-tab-justified">


                  <form action="/clientes/fisicas/eperfil" method="POST" class="steps-validation wizard-circle"
                        id="formss" name="formss">
                    @csrf
                    <input type="hidden" class="form-control required" id="id" name="id">

                    <div class="form-body">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="firstName3">
                              Monto estimado de pagos a realizar en los próximos seis meses
                            </label>
                            <input type="number" value="@if(isset($datos->monto)){{$datos->monto}}@endif" step="any"
                                   class="form-control required" id="monto" name="monto">
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="lastName3">
                              Tipo de crédito que pretende utilizar en el siguiente semestre
                            </label>
                            <input type="text" value="@if(isset($datos->tcredito)){{$datos->tcredito}}@endif"
                                   class="form-control required" id="tcredito" name="tcredito">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="lastName3">
                              Frecuencia de los pagos que realizará en el siguiente semestre
                            </label>
                            <input type="text" value="@if(isset($datos->frecuencia)){{$datos->frecuencia}}@endif"
                                   class="form-control required" id="frecuencia" name="frecuencia">
                          </div>
                        </div>
                        <div class="col-12">
                          <h3>Origen de Recursos</h3>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="actividad"
                                     value="@if(isset($datos->actividad)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                                  <span class="vs-radio--border"></span>
                                  <span class="vs-radio--circle"></span>
                                </span>
                              <span class="">Actividad propia del solicitante</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="propietario"
                                     value="@if(isset($datos->propietario)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                                <span class="vs-radio--border"></span>
                                <span class="vs-radio--circle"></span>
                              </span>
                              <span class="">Propietario Real</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="proovedor"
                                     value="@if(isset($datos->proovedor)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                              <span class="vs-radio--border"></span>
                              <span class="vs-radio--circle"></span>
                            </span>
                              <span class="">Proveedor de Recursos</span>
                            </div>
                          </div>
                        </div>

                        <div class="col-12">
                          <h3>Destino de los recursos </h3>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="dactividad"
                                     value="@if(isset($datos->dactividad)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                            <span class="vs-radio--border"></span>
                            <span class="vs-radio--circle"></span>
                          </span>
                              <span class="">Actividad propia del solicitante</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="dpasivos"
                                     value="@if(isset($datos->dpasivos)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                          <span class="vs-radio--border"></span>
                          <span class="vs-radio--circle"></span>
                        </span>
                              <span class="">Pago de pasivos</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="lastName3">
                              Otro
                            </label>
                            <input type="text" class="form-control required" id="dotro" name="dotro"
                                   value="@if(isset($datos->dotro)){{$datos->dotro}}@endif">
                          </div>
                        </div>


                        <div class="col-12">
                          <h3>Disponibilidad del cliente para la entrega de documentación </h3>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="total"
                                     value="@if(isset($datos->total)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                      <span class="vs-radio--border"></span>
                      <span class="vs-radio--circle"></span>
                    </span>
                              <span class="">Total</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="aceptable"
                                     value="@if(isset($datos->aceptable)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                    <span class="vs-radio--border"></span>
                    <span class="vs-radio--circle"></span>
                  </span>
                              <span class="">Aceptable</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="vs-radio-con vs-radio-primary">
                              <input type="radio" name="difisil"
                                     value="@if(isset($datos->difisil)) true @else false @endif">
                              <span class="vs-radio vs-radio-lg">
                  <span class="vs-radio--border"></span>
                  <span class="vs-radio--circle"></span>
                </span>
                              <span class="">Difícil</span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="lastName3">
                              Especifique si observo alguna conducta inapropiada del cliente
                            </label>
                            <input type="text" class="form-control required" id="conducta" name="conducta"
                                   value="@if(isset($datos->conducta)){{$datos->conducta}}@endif">
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="lastName3">
                              Señale algún otro comentario sobre el cliente, que considere pueda incidir en la
                              definición de su perfil transaccional, como características específicas de su actividad,
                              antecedentes o proyectos
                            </label>
                            <input type="text" class="form-control required" id="comentario" name="comentario"
                                   value="@if(isset($datos->comentario)){{$datos->comentario}}@endif">
                          </div>
                        </div>
                        <div class="col-12">
                          <button type="submit" class="btn btn-primary mr-1 mb-1">Guardar</button>
                          <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Limpiar</button>
                          <a href="/clientes/fisica">
                            <button type="button" class="btn btn-outline-danger mr-1 mb-1">Cancelar</button>
                          </a>
                        </div>

                      </div>
                    </div>
                  </form>


                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  {{-- Nav Justified Ends --}}

@endsection

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/extensions/jquery.steps.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>

  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>

@endsection
@section('page-script')
  <!-- Page js files -->
  <script src="/js/scripts/vue.min.js"></script>
  <script src="https://unpkg.com/vue-form-wizard/dist/vue-form-wizard.js"></script>
  <script src="{{ asset('js/curp.js') }}?{{rand()}}"></script>
  <script src="{{ asset('datepicker/datepicker.js') }}?{{rand()}}"></script>
  <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC2KCt-r7yAiuktmaXWVtTaVjilIcCPFM&libraries=&v=weekly"
    async></script>
  <script src="{{ asset('js/scripts/maps.js') }}?{{rand()}}"></script>
  <script></script>
  <script>
    $(document).ready(function () {
      $('.pickadate-translations').pickadate({
        max: new Date({{date('Y')-18}}, 1, 1),
        selectYears: true,
        selectMonths: true,
        format: 'mm-dd-yyyy',
        formatSubmit: 'mm-dd-yyyy',
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'
      });


      //sepomex();
      //curp();
    });

    function conyuge() {
      $("#conyuge").css("display", "block");
    }

    function sepomex() {
      var cp = $('#cp').val();
      var municipio = [];
      var ciudad = [];

      $.get('https://api-sepomex.hckdrk.mx/query/info_cp/' + cp, {}, function (data) {
        for (i = 0; i < data.length; i++) {
          console.log(data[i]['response']['asentamiento']);
          $("#colonia").append(new Option(data[i]['response']['asentamiento'], data[i]['response']['asentamiento']));
          if (municipio.includes(data[i]['response']['municipio']) == false) {
            $("#municipio").append(new Option(data[i]['response']['municipio'], data[i]['response']['municipio']));
          }
          if (ciudad.includes(data[i]['response']['ciudad']) == false) {
            $("#ciudad").append(new Option(data[i]['response']['ciudad'], data[i]['response']['ciudad']));
          }
          municipio.push(data[i]['response']['municipio']);
          ciudad.push(data[i]['response']['ciudad']);
        }
      });

      getcurp();
    }

    function getcurp() {

      var nombre = $('#nombre').val();
      var apellidom = $('#apellidom').val();
      var apellidop = $('#apellidop').val();
      var nacimiento = $('#nacimiento').val();
      var genero = $('#genero').val();
      var entidad = $('#lnacimiento').val();

      var d = new Date(nacimiento);

      var date = d.getDate();
      var month = d.getMonth() + 1; // Since getMonth() returns month from 0-11 not 1-12
      var year = d.getFullYear();

      var curp = generaCurp({
        nombre: nombre,
        apellido_paterno: apellidop,
        apellido_materno: apellidom,
        sexo: genero,
        estado: entidad,
        fecha_nacimiento: [date, month, year]
      });
      var rfc = curp.substring(0, 10);

      $('#curp').val(curp);
      $('#rfc').val(rfc);
      console.log(curp);
    }

    function jsRemoveWindowLoad() {
      // eliminamos el div que bloquea pantalla
      $("#WindowLoad").remove();
    }

    function jsShowWindowLoad() {
      //si no enviamos message se pondra este por defecto
      message = '<img src="{{asset('images/loader.gif')}}" alt="Por Favor Espere...">';

      //centrar imagen gif
      height = 20;//El div del titulo, para que se vea mas arriba (H)
      var ancho = 0;
      var alto = 0;

      //obtenemos el ancho y alto de la ventana de nuestro navegador, compatible con todos los navegadores
      if (window.innerWidth == undefined) ancho = window.screen.width;
      else ancho = window.innerWidth;
      if (window.innerHeight == undefined) alto = window.screen.height;
      else alto = window.innerHeight;

      //operación necesaria para centrar el div que muestra el message
      var heightdivsito = alto / 2 - parseInt(height) / 2;//Se utiliza en el margen superior, para centrar

      //imagen que aparece mientras nuestro div es mostrado y da apariencia de cargando
      imgCentro = "<div style='text-align:center;height:" + alto + "px;'><div  style='color:#000;margin-top:" + heightdivsito + "px; font-size:20px;font-weight:bold'>" + message + "</div><div class='loader-bubble loader-bubble-primary m-5'></div></div>";

      //creamos el div que bloquea grande------------------------------------------
      div = document.createElement("div");
      div.id = "WindowLoad"
      div.style.width = ancho + "px";
      div.style.height = alto + "px";
      $("body").append(div);

      //creamos un input text para que el foco se plasme en este y el usuario no pueda escribir en nada de atras
      input = document.createElement("input");
      input.id = "focusInput";
      input.type = "text"

      //asignamos el div que bloquea
      $("#WindowLoad").append(input);

      //asignamos el foco y ocultamos el input text
      $("#focusInput").focus();
      $("#focusInput").hide();

      //centramos el div del texto
      $("#WindowLoad").html(imgCentro);

    }


  </script>
@endsection
