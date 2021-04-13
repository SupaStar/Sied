
@extends('layouts/contentLayoutMaster')

@section('title', $pageConfigs['pageName'] )

@section('page-style')
        <!-- Page css files -->
        <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/wizard.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
        <style>
          #WindowLoad
            {
                position:fixed;
                top:0px;
                left:0px;
                z-index:3200;
                filter:alpha(opacity=85);
               -moz-opacity:85;
                opacity:0.85;
                background:#ededed;
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
                    aria-controls="messages-just" aria-selected="false">CRITERIOS DE RIESGO</a>
                </li>
              </ul>

              {{-- Tab panes --}}
              <div class="tab-content pt-1">
                <div class="tab-pane active" id="home-just" role="tabpanel" aria-labelledby="home-tab-justified">
                  <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                <form action="/clientes/fisicas/editado" enctype="multipart/form-data" method="POST" class="steps-validation wizard-circle" id="formss" name="formss">
                                      @csrf

                                      <input type="hidden" class="form-control required" id="id" name="id" value="@if(isset($datos2->id)){{$datos2->id}}@endif" >

                                        <!-- Step 1 -->
                                        <h6><i class="step-icon feather icon-user"></i> Datos Personales</h6>
                                        <fieldset>
                                          <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="firstName3">
                                                        Nombre(s)
                                                    </label>
                                                  <input type="text" class="form-control required" id="nombre" name="nombre" value="@if(isset($datos2->name)) {{$datos2->name}} @endif" >
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                              <div class="form-group">
                                                  <label for="lastName3">
                                                      Apellido Paterno
                                                  </label>
                                                  <input type="text" class="form-control required" id="apellidop" name="apellidop" value="@if(isset($datos2->lastname)) {{$datos2->lastname}} @endif">
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="lastName3">
                                                    Apellido Materno
                                                </label>
                                                <input type="text" class="form-control required" id="apellidom" name="apellidom" value="@if(isset($datos2->o_lastname)) {{$datos2->o_lastname}} @endif">
                                            </div>
                                        </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="firstName3">
                                                Genero
                                            </label>
                                                  <select class="form-control"  name="genero" id="genero">
                                                    <option selected disabled>Seleccionar</option>
                                                    <option value="H" @if(isset($datos2->gender) && $datos2->gender  == 'H') selected @endif>Masculino</option>
                                                    <option value="M" @if(isset($datos2->gender) && $datos2->gender == 'M') selected @endif>Femenino</option>
                                                  </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="lastName3">
                                              Fecha de Nacimiento
                                          </label>
                                          <input type='text' class="form-control pickadate-translations" id="nacimiento" name="fnacimiento" value="@if(isset($datos2->date_birth)) {{$datos2->date_birth}} @endif" />
                                      </div>
                                </div>

                                <div class="col-md-4">
                                  <div class="form-group">
                                      <label for="firstName3">
                                          País de Nacimiento
                                      </label>
                                            <select class="form-control" id="basicSelect" name="pais_nacimiento">
                                              <option selected disabled>Seleccionar</option>
                                              @foreach ($paises as $dd)
                                                  <option @if(isset($datos2->country_birth) && $datos2->country_birth == $dd->code) selected @endif value="{{$dd->code}}">{{$dd->pais}}</option>
                                              @endforeach
                                            </select>
                                  </div>
                                </div> </div>

                                    <div class="row">
                                      <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="firstName3">
                                                Lugar de Nacimiento
                                            </label>
                                                  <select class="form-control" id="lnacimiento" name="lnacimiento">
                                                    <option selected disabled>Seleccionar</option>
                                                    @foreach ($entidad as $dd)
                                                        <option @if(isset($datos2->nationality) && $datos2->nationality == $dd->code) selected @endif value="{{$dd->code}}">{{$dd->entity}}</option>
                                                    @endforeach
                                                  </select>
                                        </div>
                                    </div>


                                      <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="firstName3">
                                                Nacionalidad
                                            </label>
                                                  <select class="form-control" id="basicSelect" name="nacionalidad">
                                                      <option selected disabled>Seleccionar</option>
                                                      @foreach ($nacionalidades as $dd)
                                                          <option @if(isset($datos2->place_birth) && $datos2->place_birth == $dd->code) selected @endif value="{{$dd->code}}">{{$dd->country}}</option>
                                                      @endforeach
                                                  </select>
                                                </div>
                                              </div>
                                                <div class="col-md-4">
                                                  <div class="form-group">
                                                      <label for="lastName3">
                                                          Ocupación
                                                      </label>
                                                      <input type="text" value="@if(isset($datos2->job)) {{$datos2->job}} @endif" class="form-control required"  name="ocupacion">
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
                                                    <input type="text" value="@if(isset($datos2->street)) {{$datos2->street}} @endif" class="form-control required"  name="calle">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                  <label for="proposalTitle3">
                                                      # Exterior
                                                  </label>
                                                  <input type="text" value="@if(isset($datos2->exterior)) {{$datos2->exterior}} @endif" class="form-control required"  name="exterior">
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="proposalTitle3">
                                                    # Interior
                                                </label>
                                                <input type="text" value="@if(isset($datos2->inside)) {{$datos2->inside}} @endif" class="form-control required"  name="interior">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                          <div class="form-group">
                                              <label for="proposalTitle3">
                                                  Codigo Postal
                                              </label>
                                              <input type="text" value="@if(isset($datos2->pc)) {{$datos2->pc}} @endif" class="form-control required"  name="cp" id="cp" onchange="sepomex();" >
                                          </div>
                                      </div>

                                      <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="firstName3">
                                                Colonia
                                            </label>
                                                  <select class="form-control"  name="colonia" id="colonia">
                                                    @if(isset($datos2->colony))
                                                    <option  disabled>Seleccionar</option>
                                                    <option selected value="{{$datos2->colony}}" >{{$datos2->colony}}</option>
                                                      @else
                                                      <option selected disabled>Seleccionar</option>
                                                      @endif
                                                  </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="firstName3">
                                              Alcaldia o Municipio
                                          </label>
                                                <select class="form-control"  name="municipio" id="municipio">


                                                  @if(isset($datos2->town))
                                                  <option  disabled>Seleccionar</option>
                                                  <option selected value="{{$datos2->town}}" >{{$datos2->town}}</option>
                                                    @else
                                                    <option selected disabled>Seleccionar</option>
                                                    @endif


                                                </select>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="firstName3">
                                            Ciudad o Población
                                        </label>
                                              <select class="form-control"  name="ciudad" id="ciudad">

                                                @if(isset($datos2->city))
                                                <option  disabled>Seleccionar</option>
                                                <option selected value="{{$datos2->city}}" >{{$datos2->city}}</option>
                                                  @else
                                                  <option selected disabled>Seleccionar</option>
                                                  @endif

                                              </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">
                                      <label for="firstName3">
                                          Entidad Federativa
                                      </label>
                                            <select  class="form-control required" id="entidad" name="entidad">
                                              <option selected disabled>Seleccionar</option>
                                              @foreach ($entidad as $dd)
                                                  <option @if(isset($datos2->ef) && $datos2->ef == $dd->code) selected @endif  value="{{$dd->code}}">{{$dd->entity}}</option>
                                            git add .  @endforeach
                                            </select>
                                  </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                    <label for="firstName3">
                                        País
                                    </label>
                                          <select class="form-control" id="pais" name="pais">
                                            @if(isset($datos2->country))
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
                                                    <input type="text" value="@if(isset($datos2->phone1)) {{$datos2->phone1}} @endif"  class="form-control"  name="telefono1" >
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                              <div class="form-group">
                                                  <label for="lastName3">
                                                    Número Teléfonico 2
                                                  </label>
                                                  <input type="text" value="@if(isset($datos2->phone2)) {{$datos2->phone2}} @endif"  class="form-control " name="telefono2">
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="lastName3">
                                                  Email
                                                </label>
                                                <input type="text" value="@if(isset($datos2->email)) {{$datos2->email}} @endif"  class="form-control " id="memail" name="memail">
                                            </div>
                                        </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="firstName3">
                                                        CURP
                                                    </label>
                                                    <input type="text" value="@if(isset($datos2->curp)) {{$datos2->curp}} @endif"  class="form-control required"  name="curp" id="curp" >
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                              <div class="form-group">
                                                  <label for="lastName3">
                                                    RFC
                                                  </label>
                                                  <input type="text" value="@if(isset($datos2->rfc)) {{$datos2->rfc}} @endif"  class="form-control required" name="rfc" id="rfc">
                                              </div>
                                          </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="vs-radio-con vs-radio-primary">
                                                      @if(isset($datos2->c_name) && $datos2->c_name != null)
                                                      <input type="radio" name="vueradisize" value="true" onchange="conyuge()">
                                                      @else
                                                      <input type="radio" name="vueradisize" value="false" onchange="conyuge()">
                                                      @endif
                                                      <span class="vs-radio vs-radio-lg">
                                                        <span class="vs-radio--border"></span>
                                                        <span class="vs-radio--circle"></span>
                                                      </span>
                                                      <span class="">Agregar Datos de Cónyuge</span>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>

                                            @if(isset($datos2->c_name) && $datos2->c_name != null)
                                            <div id="conyuge" style="display:block;" >
                                              @else
                                            <div id="conyuge" style="display:none;" >
                                              @endif
                                            <div class="row">
                                              <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="firstName3">
                                                        Nombre(s)
                                                    </label>
                                                    <input type="text" value="@if(isset($datos->c_name)) {{$datos->c_name}} @endif" class="form-control"  name="cnombre"  >
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                              <div class="form-group">
                                                  <label for="lastName3">
                                                    Apellido Paterno
                                                  </label>
                                                  <input type="text" value="@if(isset($datos->c_lastname)) {{$datos->c_lastname}} @endif" class="form-control " name="capellidop">
                                              </div>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                  <label for="lastName3">
                                                    Apellido Materno
                                                  </label>
                                                  <input type="text" value="@if(isset($datos->c_o_lastname)) {{$datos->c_o_lastname}} @endif" class="form-control " name="capellidom">
                                              </div>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                  <label for="lastName3">
                                                    Número Teléfonico
                                                  </label>
                                                  <input type="text" value="@if(isset($datos->c_phone)) {{$datos->c_phone}} @endif" class="form-control " name="ctelefono">
                                              </div>
                                            </div>
                                            <div class="col-md-4">
                                              <div class="form-group">
                                                  <label for="lastName3">
                                                    Correo Eletrónico
                                                  </label>
                                                  <input type="text" value="@if(isset($datos->c_email)) {{$datos->c_email}} @endif" class="form-control " name="cemail">
                                              </div>
                                            </div>
                                            </div>
                                          </div>
                                        </fieldset>
                                        <!-- Step 4 -->
                                        <h6><i class="step-icon feather icon-folder-plus"></i> Documentos Requeridos</h6>
                                        <fieldset>
                                          <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="eventName3">
                                                        INE
                                                    </label>
                                                    <input type="file" class="form-control " id="eventName3" name="fileine" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  <label for="eventName3">
                                                      CURP
                                                  </label>
                                                  <input type="file" class="form-control " id="eventName3" name="filecurp" >
                                              </div>
                                          </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="eventName3">
                                                    Comprobante de Domicilio
                                                </label>
                                                <input type="file" class="form-control " id="eventName3" name="filedom" >
                                            </div>
                                        </div>
                                    </div>
                                      <div class="row">
                                        <div class="col-md-4">
                                          <div class="form-group">
                                              <label for="eventName3">
                                                Comprobante de Ingresos 1
                                              </label>
                                              <input type="file" class="form-control " id="eventName3" name="filecom1" >
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="eventName3">
                                              Comprobante de Ingresos 2
                                            </label>
                                            <input type="file" class="form-control " id="eventName3" name="filecom2" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="eventName3">
                                            Comprobante de Ingresos 3
                                          </label>
                                          <input type="file" class="form-control " id="eventName3" name="filecom3" >
                                      </div>
                                  </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="eventName3">
                                                RFC(Si cuentas con el)
                                            </label>
                                            <input type="file" class="form-control " id="eventName3" name="filerfc" >
                                        </div>
                                    </div>

                                </div>
                                          <div class="row">
                                            <div class="col-md-6">
                                              <button hidden type="reset" class="btn btn-secondary mr-1 mb-1">Limpiar</button>
                                            </div>
                                            <div class="col-md-6 text-left">
                                              <button hidden type="submit" class="btn btn-primary float-right mr-1 mb-1">Guardar</button>
                                              <a href="/clientes/fisica"> <button hidden type="button"
                                                                                   class="btn btn-primary float-right mr-1 mb-1">Cancelar</button></a>
                                            </div>


                                          </div>
                                  </fieldset>
                                  </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                </div>
                <div class="tab-pane" id="profile-just" role="tabpanel" aria-labelledby="profile-tab-justified">


                    <form action="/clientes/fisicas/eperfil" method="POST" id="formss" name="formss">
                      @csrf
                      <input type="hidden" class="form-control required" id="id" name="id" value="@if(isset($datos->id)){{$datos->id}}@endif" >

                      <div class="form-body">
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="lastName3">
                                Frecuencia de los pagos que realizará en el siguiente semestre
                              </label>
                              <select class="form-control" id="frecuencia" name="frecuencia" @if(isset($datos))@endif>
                                @if(isset($datos->frecuencia))
                                  <option value="{{ $datos->frecuencia }}" selected>{{ $datos->frecuencia }}</option>
                                @else
                                  <option selected disabled>Seleccionar</option>
                                @endif
                                <option value="Semanal">Semanal</option>
                                <option value="Quincenal">Quincenal</option>
                                <option value="Mensual">Mensual</option>
                                <option value="Bimestral">Bimestral</option>
                                <option value="Trimestral">Trimestral</option>
                                <option value="Semestral">Semestral</option>
                                <option value="A la medida">A la medida</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="firstName3">
                                Monto estimado de pagos a realizar en los próximos seis meses
                              </label>
                              <input type="number" value="@if(isset($datos->monto)){{ $datos->monto }}@endif" @if(isset($datos)) @endif step="any"
                                     class="form-control required" id="monto" name="monto">
                            </div>
                          </div>

                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="lastName3">
                                Tipo de crédito que pretende utilizar en el siguiente semestre
                              </label>
                              <input type="text" value="@if(isset($datos->tcredito)){{ $datos->tcredito }}@endif"
                                     @if(isset($datos)) @endif class="form-control required" id="tcredito" name="tcredito">
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="lastName3">
                                Origen de Recursos
                              </label>
                              <select class="form-control required" id="orecursos" name="orecursos" required  @if(isset($datos)) @endif>
                                <option selected disabled>Seleccionar</option>
                                @foreach($origen as $data)
                                  @if(isset($datos->origen_recursos))
                                    @if($datos->origen_recursos == $data->id)
                                      <option value="{{$data->id}}" selected>{{$data->descripcion}}</option>
                                    @else
                                      <option value="{{$data->id}}">{{$data->descripcion}}</option>
                                    @endif
                                  @else
                                    <option value="{{$data->id}}">{{$data->descripcion}}</option>
                                  @endif

                                @endforeach

                              </select>
                            </div>
                          </div>

                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="lastName3">
                                Forma de Pago
                              </label>
                              <select class="form-control required" id="imonetario" name="imonetario" @if(isset($datos)) @endif>

                                <option selected disabled>Seleccionar</option>
                                @foreach($instrumento as $data)
                                  @if(isset($datos->instrumento_monetario))
                                    @if($datos->instrumento_monetario == $data->id)
                                      <option value="{{$data->id}}" selected>{{$data->descripcion}}</option>
                                    @else
                                      <option value="{{$data->id}}">{{$data->descripcion}}</option>
                                    @endif
                                  @else
                                    <option value="{{$data->id}}">{{$data->descripcion}}</option>
                                  @endif
                                @endforeach

                              </select>
                            </div>
                          </div>


                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="lastName3">
                                Divisa
                              </label>
                              <select class="form-control required" id="divisa" name="divisa" @if(isset($datos)) @endif>
                                <option selected disabled>Seleccionar</option>
                                @foreach($divisa as $data)
                                  @if(isset($datos->divisa))
                                    @if($datos->divisa == $data->id)
                                      <option value="{{$data->id}}" selected>{{$data->descripcion}}</option>
                                    @else
                                      <option value="{{$data->id}}">{{$data->descripcion}}</option>
                                    @endif
                                  @else
                                    <option value="{{$data->id}}">{{$data->descripcion}}</option>
                                  @endif
                                @endforeach

                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="row">

                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="lastName3">
                                Destino De Recursos
                              </label>
                              <select class="form-control required" id="drecursos" name="drecursos" @if(isset($datos)) @endif>
                                <option selected disabled>Seleccionar</option>
                                @foreach($destino as $data)
                                  <option value="{{$data->id}}">{{$data->descripcion}}</option>
                                @endforeach

                              </select>
                            </div>
                          </div>

                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="lastName3">
                                Disponibilidad del cliente para la entrega de documentación
                              </label>
                              <select class="form-control" id="disponibilidad" name="disponibilidad" @if(isset($datos)) @endif>

                                @if(isset($datos->frecuencia))
                                  <option value="{{ $datos->frecuencia }}" selected>{{ $datos->frecuencia }}</option>
                                @else
                                  <option selected disabled>Seleccionar</option>
                                @endif
                                <option value="total" @if(isset($datos->total)) @if($datos->total == 1) selected @endif @endif>Total</option>
                                <option value="aceptable" @if(isset($datos->aceptable)) @if($datos->aceptable == 1) selected @endif @endif>Aceptable</option>
                                <option value="difisil" @if(isset($datos->difisil)) @if($datos->difisil == 1) selected @endif @endif>Difícil</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="lastName3">
                                Ingreso Mensual Estimado
                              </label>
                              <input type="number" value="@if(isset($datos->ingreso)){{ $datos->ingreso }}@endif" @if(isset($datos)) @endif step="any"
                                     class="form-control required" id="ingreso" name="ingreso">
                            </div>
                          </div>

                        </div>
                        <div class="row">


                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="lastName3">
                                Especifique si observo alguna conducta inapropiada del cliente
                              </label>
                              <input type="text" class="form-control required" id="conducta" @if(isset($datos)) @endif name="conducta"
                                     value="@if(isset($datos->conducta)){{ $datos->conducta }}@endif">
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="lastName3">
                                Señale algún otro comentario sobre el cliente, que considere pueda incidir en la definición de
                                su perfil transaccional, como características específicas de su actividad, antecedentes o
                                proyectos
                              </label>
                              <input type="text" class="form-control required" id="comentario" @if(isset($datos))@endif name="comentario"
                                     value="@if(isset($datos->comentario)){{ $datos->comentario }}@endif">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <button type="reset" class="btn btn-secondary mr-1 mb-1" @if(isset($datos))disabled readonly hidden @endif>Limpiar</button>
                          </div>
                          <div class="col-md-6 text-left">
                            <button type="submit" class="btn btn-primary float-right mr-1 mb-1" @if(isset($datos)) @endif>Guardar</button>
                            <a href="/clientes/fisica"> <button type="button"
                                                                class="btn btn-primary float-right mr-1 mb-1" >Cancelar</button></a>
                          </div>

                        </div>
                      </div>

                      </form>

                </div>
                <div class="tab-pane" id="messages-just" role="tabpanel" aria-labelledby="messages-tab-justified">


                  <form action="/clientes/fisicas/eebr" method="POST" id="formss"
                        name="formss">
                    @csrf
                    <input type="hidden" class="form-control required" id="id" name="id" value="{{ $id }}">

                    <div class="form-body">

                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="lastName3">
                              Ocupación
                            </label>
                            <input type="text" class="form-control required" value="@if(isset($profesion)){{ $profesion }}@endif">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="lastName3">
                              Profesión
                            </label>
                            <select class="form-control required" id="profesion" name="profesion" required>

                              <option  selected disabled>Seleccionar</option>
                              @foreach($profesiones as $data)
                                @if(isset($datos))
                                  @if($datos->profesion == $data->id)
                                    <option value="{{$data->id}}" selected>{{$data->descripcion}}</option>
                                  @else
                                    <option value="{{$data->id}}">{{$data->descripcion}}</option>
                                  @endif
                                @else
                                  <option value="{{$data->id}}">{{$data->descripcion}}</option>
                                @endif
                              @endforeach

                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="lastName3">
                              Actividad ó Giro
                            </label>
                            <select  class="form-control required" id="actividad" name="actividad" required>
                              <option selected disabled>Seleccionar</option>
                              @foreach($origen as $data)
                                @if(isset($datos))
                                  @if($datos->actividad_giro == $data->id)
                                    <option value="{{$data->id}}" selected>{{$data->descripcion}}</option>
                                  @else
                                    <option value="{{$data->id}}">{{$data->descripcion}}</option>
                                  @endif
                                @else
                                  <option value="{{$data->id}}">{{$data->descripcion}}</option>
                                @endif
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>



                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="lastName3">
                              Entidad Federativa
                            </label>
                            <input type="text" class="form-control required" value="@if(isset($residencia)){{ $residencia }}@endif" >
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="lastName3">
                              Criterio
                            </label>
                            <select class="form-control required" id="efr" name="efr" required>
                              <option  selected disabled>Seleccionar</option>
                              @foreach($efresidencia as $data)
                                @if(isset($datos))
                                  @if($datos->efr == $data->id)
                                    <option value="{{$data->id}}" selected>{{$data->descripcion}}</option>
                                  @else
                                    <option value="{{$data->id}}">{{$data->descripcion}}</option>
                                  @endif
                                @else
                                  <option value="{{$data->id}}">{{$data->descripcion}}</option>
                                @endif
                              @endforeach

                            </select>
                          </div>
                        </div>

                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <button hidden type="reset" class="btn btn-secondary mr-1 mb-1">Limpiar</button>
                        </div>
                        <div class="col-md-6 text-left">
                          <button type="submit" class="btn btn-primary float-right mr-1 mb-1">Guardar</button>
                          <a href="/clientes/fisica"> <button  type="button"
                                                              class="btn btn-primary float-right mr-1 mb-1">Cancelar</button></a>
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
        <script src="{{ asset(mix('js/scripts/forms/wizard-steps.js')) }}?{{rand()}}"></script>
        <script src="{{ asset('js/curp.js') }}?{{rand()}}"></script>
<script>
$(document).ready(function () {
  $( '.pickadate-translations' ).pickadate({
        max: new Date({{date('Y')-18}},1,1),
        selectYears: true,
        selectMonths: true,
        format: 'mm-dd-yyyy',
        formatSubmit: 'mm-dd-yyyy',
        monthsFull: [ 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' ],
        monthsShort: [ 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic' ],
        weekdaysShort: [ 'Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab' ],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar'
    });


  //sepomex();
  //curp();
  });

function conyuge(){
  $("#conyuge").css("display", "block");
}

function sepomex(){
  var cp = $('#cp').val();
  var municipio = [];
  var ciudad = [];

  $.get('https://api-sepomex.hckdrk.mx/query/info_cp/'+cp+'?token={{env("TOKENSEPOMEX", "")}}', {
  }, function(data) {
    for (i=0; i<data.length; i++) {
          console.log(data[i]['response']['asentamiento']);
          $("#colonia").append(new Option(data[i]['response']['asentamiento'], data[i]['response']['asentamiento']));
          if(municipio.includes(data[i]['response']['municipio']) == false){
            $("#municipio").append(new Option(data[i]['response']['municipio'], data[i]['response']['municipio']));
          }
          if(ciudad.includes(data[i]['response']['ciudad']) == false){
            $("#ciudad").append(new Option(data[i]['response']['ciudad'], data[i]['response']['ciudad']));
          }
          municipio.push(data[i]['response']['municipio']);
          ciudad.push(data[i]['response']['ciudad']);
        }
  });

  getcurp();
}

function getcurp(){

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
        nombre            : nombre,
        apellido_paterno  : apellidop,
        apellido_materno  : apellidom,
        sexo              : genero,
        estado            : entidad,
        fecha_nacimiento  : [date, month, year]
      });
      var rfc =  curp.substring(0, 10);

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
    var heightdivsito = alto/2 - parseInt(height)/2;//Se utiliza en el margen superior, para centrar

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
