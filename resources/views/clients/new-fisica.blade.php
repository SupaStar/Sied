
@extends('layouts/contentLayoutMaster')

@section('title', $pageConfigs['pageName'] )

@section('page-style')
        <!-- Page css files -->
        <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/wizard.css')) }}">
        <link rel="stylesheet" href="{{ asset('datepicker/datepicker.css') }}">
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

<!-- Form wizard with step validation section start -->
<section id="validation">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
            <form action="/clientes/crear" enctype="multipart/form-data" method="POST"
              class="steps-validation wizard-circle" id="formss" name="formss">
              @csrf
              <!-- Step 1 -->
              <h6>
                <i class="step-icon feather icon-user"></i> Datos Personales</h6>
              <fieldset>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="lastName3">
                        INE FRONTAL
                      </label>
                      <input type="file" onchange="validateine()" data-toggle="tooltip" data-placement="top"
                        title="Solo se permiten imagenes JPG, JPEG, PNG orientadas horizontalmente"
                        class="form-control required" id="inefront" name="inefront" accept=".jpg, .jpeg, .png">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="lastName3">
                        INE TRASERA
                      </label>
                      <input type="file" onchange="validateine()" data-toggle="tooltip" data-placement="top"
                        title="Solo se permiten imagenes JPG, JPEG, PNG orientadas horizontalmente"
                        class="form-control required" id="ineback" name="ineback" accept=".jpg, .jpeg, .png">
                    </div>
                  </div>
                </div>
                  <div class="row">
                    <div class="col-md-4 offset-md-4">
                      <div class="form-group">
                        <label for="curp">
                          CURP
                        </label>
                        <input type="text" class="form-control required" name="curp" id="curp" onchange="checkcurp()">
                      </div>
                    </div>
                  </div>
                <div class="row" id="firstRow">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="firstName3">
                        Nombre(s)
                      </label>
                      <input type="text" class="form-control required" id="nombre" name="nombre">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lastName3">
                        Apellido Paterno
                      </label>
                      <input type="text" class="form-control required" id="apellidop" name="apellidop">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lastName3">
                        Apellido Materno
                      </label>
                      <input type="text" class="form-control required" id="apellidom" name="apellidom">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="firstName3">
                        Genero
                      </label>
                      <select class="form-control" name="genero" id="genero">
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
                      <input type='text' class="form-control" data-toggle="datepicker" name="fnacimiento"
                        id="nacimiento">

                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="firstName3">
                        País de Nacimiento
                      </label>
                      <select class="form-control" id="pais_nacimiento" name="pais_nacimiento">
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
                      <select class="form-control" id="lnacimiento" name="lnacimiento">
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
                      <select class="form-control" id="nacionalidad" name="nacionalidad">
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
                      <input type="text" class="form-control required" name="ocupacion">
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
                      <input type="text" class="form-control required" id="calle" name="calle">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="proposalTitle3">
                        # Exterior
                      </label>
                      <input type="text" class="form-control required" name="exterior" id="exterior">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="proposalTitle3">
                        # Interior
                      </label>
                      <input type="text" class="form-control " name="interior">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="proposalTitle3">
                        Codigo Postal
                      </label>
                      <input type="text" class="form-control required" name="cp" id="cp" onchange="sepomex();">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="firstName3">
                        Colonia
                      </label>
                      <select class="form-control" name="colonia" id="colonia" onchange="addData('colonia');" >
                      <option selected disabled>Seleccionar</option>
                      <option value='Otro' >Otro</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="firstName3">
                        Alcaldia o Municipio
                      </label>
                      <select class="form-control" name="municipio" id="municipio" onchange="addData('municipio');">
                        <option selected disabled>Seleccionar</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="firstName3">
                        Ciudad o Población
                      </label>
                      <select class="form-control" name="ciudad" id="ciudad" onchange="addData('ciudad');">
                        <option selected disabled>Seleccionar</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="firstName3">
                        Entidad Federativa
                      </label>
                      <select class="form-control" id="entidad" name="entidad">
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
                      <select class="form-control" id="pais" name="pais">
                        <option selected disabled>Seleccionar</option>
                        <option value="México">México</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="firstName3">
                      Antigüedad en Domicilio
                      </label>
                      <select class="form-control" id="antiguedad" name="antiguedad">
                        <option selected disabled>Seleccionar</option>
                        @foreach($antiguedad as $data)
                              <option value="{{$data->id}}">{{$data->descripcion}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </fieldset>

              <!-- Step 3 -->
              <h6><i class="step-icon feather icon-file-plus"></i> Datos Adicionales</h6>
              <fieldset>
                <div class="row">
                  <div class="col-xl-4 col-md-6 col-sm-12 mb-2">
                    <label for="phone-number">Número Teléfonico 1</label>
                    <div class="input-group input-group-merge">
                      <div class="input-group-prepend">
                        <span class="input-group-text">MX (+52)</span>
                      </div>
                      <input
                        data-validation-regex-regex="([^a-z]*[A-Z]*)*"
                        data-validation-containsnumber-regex="([^0-9]*[0-9]+)+"
                        data-validation-required-message="The digits field must be numeric and exactly contain 10 digits"
                        type="text"
                        class="form-control phone-number-mask"
                        placeholder="1 234 567 8900"
                        id="telefono1"
                        name="telefono1"
                        maxlength="12"
                        minlength="12"
                      />
                    </div>
                  </div>

                  <div class="col-xl-4 col-md-6 col-sm-12 mb-2">
                    <label for="phone-number">Número Teléfonico 2</label>
                    <div class="input-group input-group-merge">
                      <div class="input-group-prepend">
                        <span class="input-group-text">MX (+52)</span>
                      </div>
                      <input
                        data-validation-regex-regex="([^a-z]*[A-Z]*)*"
                        data-validation-containsnumber-regex="([^0-9]*[0-9]+)+"
                        data-validation-required-message="The digits field must be numeric and exactly contain 10 digits"
                        type="text"
                        class="form-control phone-number-mask"
                        placeholder="1 234 567 8900"
                        id="telefono2"
                        name="telefono2"
                        maxlength="10"
                        minlength="10"
                      />
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lastName3">
                        Email
                      </label>
                      <input type="text" class="form-control required" id="memail" name="memail"
                        onchange="checkemail()">
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
                <div class="row">
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
                </div>
              </fieldset>
              <!-- Step 4 -->
              <h6><i class="step-icon feather icon-folder-plus"></i> Documentos Requeridos</h6>
              <fieldset>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="eventName3">
                        CURP
                      </label>
                      <input type="file" class="form-control required" id="eventName3" name="filecurp"
                        accept=".jpg, .jpeg, .png">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="eventName3">
                        Comprobante de Domicilio
                      </label>
                      <input type="file" class="form-control required" id="eventName3" name="filedom"
                        accept=".jpg, .jpeg, .png">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="eventName3">
                        Comprobante de Ingresos 1
                      </label>
                      <input type="file" class="form-control required" id="eventName3" name="filecom1"
                        accept=".jpg, .jpeg, .png">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="eventName3">
                        Comprobante de Ingresos 2
                      </label>
                      <input type="file" class="form-control " id="eventName3" name="filecom2"
                        accept=".jpg, .jpeg, .png">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="eventName3">
                        Comprobante de Ingresos 3
                      </label>
                      <input type="file" class="form-control " id="eventName3" name="filecom3"
                        accept=".jpg, .jpeg, .png">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="eventName3">
                        RFC(Si cuentas con el)
                      </label>
                      <input type="file" class="form-control " id="eventName3" name="filerfc"
                        accept=".jpg, .jpeg, .png">
                    </div>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Form wizard with step validation section end -->
@endsection

@section('vendor-script')
        <!-- vendor files -->
        <script src="{{ asset(mix('vendors/js/extensions/jquery.steps.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>

        <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>

        <script src="{{ asset('vendors/js/forms/cleave/cleave.min.js') }}"></script>
        <script src="{{ asset('vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>

@endsection
@section('page-script')
        <!-- Page js files -->
        <script src="{{ asset(mix('js/scripts/forms/wizard-steps.js')) }}?{{rand()}}"></script>
        <script src="{{ asset('js/curp.js') }}?{{rand()}}"></script>
        <script src="{{ asset('datepicker/datepicker.js') }}?{{rand()}}"></script>
        <script src="/js/scripts/vue.min.js"></script>
        <script src="{{ asset('js/scripts/forms/form-input-mask.js') }}"></script>

<script>

var token = '{{csrf_token()}}';

(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? factory(require('jquery')) :
  typeof define === 'function' && define.amd ? define(['jquery'], factory) :
  (factory(global.jQuery));
}(this, (function ($) {
  'use strict';

  $.fn.datepicker.languages['es-ES'] = {
    format: 'dd/mm/yyyy',
    days: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
    daysShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
    daysMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
    weekStart: 1,
    months: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
    monthsShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic']
  };
})));

$(document).ready(function () {

  $('[data-toggle="datepicker"]').datepicker({format: 'mm-dd-yyyy', language: 'es-ES'});


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


function checkemail(){
  var email = $('#memail').val();
  jsShowWindowLoad();
  var form_data = new FormData();
  form_data.append('_token', token);

  $.ajax({
        url: '/util/checkemail/'+email,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(res){
          pars = JSON.parse(res);
            if(pars.message == 'exist'){
              $('#memail').val('');
              jsRemoveWindowLoad();
                Swal.fire({
                         title: "Error!",
                         text: "Este email ya se encuentra registrado!",
                         type: "error",
                         confirmButtonClass: 'btn btn-primary',
                         buttonsStyling: false,
                         animation: false,
                         customClass: 'animated tada'
                       });
            } else {
              jsRemoveWindowLoad();
              Swal.fire({
                         title: "Bien!",
                         text: "Este email se puede registrar!",
                         type: "success",
                         confirmButtonClass: 'btn btn-primary',
                         buttonsStyling: false,
                         animation: false,
                         customClass: 'animated tada'
                       });
            }
        }
     });

}

function checkcurp(){
  var curp = $("#curp").val();
  jsShowWindowLoad();
  var myheaders= new Headers({
    dataType: 'text'
  });
  myheaders.append("X-CSRF-TOKEN", token);
  fetch("/util/checkcurp/"+curp,{
    method:'POST',
    headers:myheaders}).then(async (response) => {
      res = await response.json();
      console.log(res)
      jsRemoveWindowLoad();
      if(res.estatus=="OK"){
        var estado = res.curp.substring(11,13);
        var sexo = '';
        if(res.sexo == 'MUJER'){
          sexo = 'M';
        }
        if(res.sexo == 'HOMBRE'){
          sexo = 'H';
        }
        $('#genero').val(sexo);

        $('#nombre').val(res.nombre);
        $('#apellidop').val(res.apellidoPaterno);
        $('#apellidom').val(res.apellidoMaterno);
        $("#lnacimiento").val(estado);
        Swal.fire({
                title: "¡Bien!",
                text: "CURP validada",
                type: "success",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
                animation: false,
                customClass: 'animated tada'
              });

      } else if(res.estatus=="EXISTE"){
        $('#inefront').val(null);
        $('#ineback').val(null);
        $('#curp').val(null);
        $('#genero').val(null);
        $('#nombre').val(null);
        $('#apellidop').val(null);
        $('#apellidom').val(null);
        $("#lnacimiento").val(null);
        $('#calle').val(null);
        $('#exterior').val(null);
        $('#cp').val(null);
        var select = document.getElementById("colonia");
        var length = select.options.length;
        for (i = length-1; i >= 0; i--) {
          select.options[i] = null;
        }
        var select = document.getElementById("municipio");
        var length = select.options.length;
        for (i = length-1; i >= 0; i--) {
          select.options[i] = null;
        }
        var select = document.getElementById("ciudad");
        var length = select.options.length;
        for (i = length-1; i >= 0; i--) {
          select.options[i] = null;
        }
        $('#curp').val(null);
        $('#rfc').val(null);
        document.ready = document.getElementById("genero").value = null;
        document.ready = document.getElementById("nacionalidad").value = null;
        $('#nacimiento').val(null);

        Swal.fire({
                  title: "EXISTENTE!",
                  text: res.mensaje,
                  type: "error",
                  confirmButtonClass: 'btn btn-primary',
                  buttonsStyling: false,
                  animation: false,
                  customClass: 'animated tada'
                });

      }else{
      Swal.fire({
                title: "¡Error!",
                text: res.mensaje,
                type: "error",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
                animation: false,
                customClass: 'animated tada'
              });
      }
  });
}

function addData(type){
      switch (type) {
        case 'colonia':
            var colonia = $('#colonia').val();
            if(colonia == 'Otro')
            {
              Swal.mixin({
                          input: 'text',
                          confirmButtonText: 'Agregar &rarr;',
                          showCancelButton: true,
                          confirmButtonClass: 'btn btn-primary',
                          buttonsStyling: false,
                          cancelButtonClass: "btn btn-danger ml-1"
                        }).queue([
                          {
                            title: 'Colonia',
                            text: 'Nombre de la colonia'
                          }
                        ]).then(function (result) {
                          if (result.value) {
                            $("#colonia").append(new Option(result.value[0], result.value[0]));
                            document.ready = document.getElementById("colonia").value = result.value[0];

                            Swal.fire({
                              title: 'Bien!',
                              html:
                                'Se agrego: ' +
                                result.value[0],
                              confirmButtonText: 'Cerrar!'
                            })
                          }
                        });
            }

          break;
        case 'municipio':
          var municipio = $('#municipio').val();
            if(municipio == 'Otro')
            {
              Swal.mixin({
                          input: 'text',
                          confirmButtonText: 'Agregar &rarr;',
                          showCancelButton: true,
                          confirmButtonClass: 'btn btn-primary',
                          buttonsStyling: false,
                          cancelButtonClass: "btn btn-danger ml-1"
                        }).queue([
                          {
                            title: 'Municipio',
                            text: 'Nombre del municipio'
                          }
                        ]).then(function (result) {
                          if (result.value) {
                            $("#municipio").append(new Option(result.value[0], result.value[0]));
                            document.ready = document.getElementById("municipio").value = result.value[0];

                            Swal.fire({
                              title: 'Bien!',
                              html:
                                'Se agrego: ' +
                                result.value[0],
                              confirmButtonText: 'Cerrar!'
                            })
                          }
                        });
            }

          break;
        case 'ciudad':
          var colonia = $('#ciudad').val();
            if(colonia == 'Otro')
            {
              Swal.mixin({
                          input: 'text',
                          confirmButtonText: 'Agregar &rarr;',
                          showCancelButton: true,
                          confirmButtonClass: 'btn btn-primary',
                          buttonsStyling: false,
                          cancelButtonClass: "btn btn-danger ml-1"
                        }).queue([
                          {
                            title: 'Ciudad',
                            text: 'Nombre de la ciudad'
                          }
                        ]).then(function (result) {
                          if (result.value) {
                            $("#ciudad").append(new Option(result.value[0], result.value[0]));
                            document.ready = document.getElementById("ciudad").value = result.value[0];

                            Swal.fire({
                              title: 'Bien!',
                              html:
                                'Se agrego: ' +
                                result.value[0],
                              confirmButtonText: 'Cerrar!'
                            })
                          }
                        });
            }

          break;
        default:

          break;
        }
}

function validateine(){

  var inefront = $('#inefront').val();
  var ineback = $('#ineback').val();

  if(inefront != '' && ineback != ''){
    jsShowWindowLoad();

  var finefront = $('#inefront').prop('files')[0];
  var fineback = $('#ineback').prop('files')[0];

    var form_data = new FormData();
    form_data.append('inefront', finefront);
    form_data.append('ineback', fineback);
    form_data.append('_token', token);

    $.ajax({
        url: '/util/imgto64',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(res){
          pars = JSON.parse(res);
            if(pars.message == 'fail'){
              jsRemoveWindowLoad();
                Swal.fire({
                         title: "Error!",
                         text: "Alguno de los documentos no es formato de imagen valido!",
                         type: "error",
                         confirmButtonClass: 'btn btn-primary',
                         buttonsStyling: false,
                         animation: false,
                         customClass: 'animated tada'
                       });
            } else if(pars.message == 'ManualChecking'){
              jsRemoveWindowLoad();
                Swal.fire({
                         title: "No se pudo verificar!",
                         text: "Se tiene que verificar visualmente!",
                         type: "warning",
                         confirmButtonClass: 'btn btn-primary',
                         buttonsStyling: false,
                         animation: false,
                         customClass: 'animated tada'
                       });
            } else if(pars.message == 'ControlListManualChecking'){
              jsRemoveWindowLoad();
                Swal.fire({
                         title: "Advertencia!",
                         text: "Al parecer esta persona se encuentra en listas negras!",
                         type: "error",
                         confirmButtonClass: 'btn btn-primary',
                         buttonsStyling: false,
                         animation: false,
                         customClass: 'animated tada'
                       });
            } else {

              for (i = 0; i < pars.documentData.length; i++) {
                if(pars.documentData[i].type == 'Name'){
                  $('#nombre').val(pars.documentData[i].value);
                }
                if(pars.documentData[i].type == 'FatherSurname'){
                  $('#apellidop').val(pars.documentData[i].value);
                }
                if(pars.documentData[i].type == 'MotherSurname'){
                  $('#apellidom').val(pars.documentData[i].value);
                }
                if(pars.documentData[i].type == 'AddressStreet'){
                  $('#calle').val(pars.documentData[i].value);
                }
                if(pars.documentData[i].type == 'AddressStreetNumber'){
                  $('#exterior').val(pars.documentData[i].value);
                }
                if(pars.documentData[i].type == 'AddressPostalCode'){
                  $('#cp').val(pars.documentData[i].value);
                  sepomex();
                }
                if(pars.documentData[i].type == 'AddressArea'){
                  $("#colonia").append(new Option(pars.documentData[i].value, pars.documentData[i].value));
                  document.ready = document.getElementById("colonia").value = pars.documentData[i].value;
                }
                if(pars.documentData[i].type == 'AddressCity'){
                  $("#municipio").append(new Option(pars.documentData[i].value, pars.documentData[i].value));
                  document.ready = document.getElementById("municipio").value = pars.documentData[i].value;
                }
                if(pars.documentData[i].type == 'AddressCounty'){
                  $("#ciudad").append(new Option(pars.documentData[i].value, pars.documentData[i].value));
                  document.ready = document.getElementById("ciudad").value = pars.documentData[i].value;
                }

                if(pars.documentData[i].type == 'PersonalNumber'){
                      var curp = pars.documentData[i].value;

                      var rfc =  curp.substring(0, 10);
                      $('#curp').val(curp);
                      $('#rfc').val(rfc);
                }

                if(pars.documentData[i].type == 'Sex'){
                  document.ready = document.getElementById("genero").value = pars.documentData[i].value;
                }

                if(pars.documentData[i].type == 'Nationality'){
                  document.ready = document.getElementById("nacionalidad").value = pars.documentData[i].value;
                }

                if(pars.documentData[i].type == 'DateOfBirth'){

                    var gdate = pars.documentData[i].value;
                    var gdatearray = gdate.split("/");
                    var gnewdate = gdatearray[1] + '/' + gdatearray[0] + '/' + gdatearray[2];

                    var stringDate = gnewdate;
                    var d = new Date(stringDate);
                    var date = ("0" + d.getDate()).slice(-2);
                    var month = ("0" + (d.getMonth() + 1)).slice(-2);
                    var year = d.getFullYear();
                    $('#nacimiento').val(month+'-'+date+'-'+year);
                    $('[data-toggle="datepicker"]').datepicker( 'setDate', month+'-'+date+'-'+year );

                }

              }

              var listIndex=0;
              for (i = 0; i < pars.documentVerifications.length; i++) {
                if(pars.documentVerifications[i].category=="ControlList" && pars.documentVerifications[i].inputFields!=null &&pars.documentVerifications[i].name!="Sin coincidencias"){
                  console.log(pars.documentVerifications[i].inputFields[0].value);
                  var html='<input name="listasNegras['+listIndex+'][name]" type="hidden" value="'+pars.documentVerifications[i].name+'">';
                  html +='<input name="listasNegras['+listIndex+'][value]" type="hidden" value="'+pars.documentVerifications[i].inputFields[0].value+'">';
                  $("#firstRow").append(html)
                  listIndex++;
                }
              }

              document.ready = document.getElementById("pais").value = 'México';

              document.ready = document.getElementById("pais_nacimiento").value = 303;


              jsRemoveWindowLoad();
              Swal.fire({
                         title: "Existente!",
                         text: "Ine verificada!",
                         type: "success",
                         confirmButtonClass: 'btn btn-primary',
                         buttonsStyling: false,
                         animation: false,
                         customClass: 'animated tada'
                       }).then((result) => {checkcurp()});
                       console.log(pars);
            }
        }
     });

  }
}

function conyuge(){
  $("#conyuge").css("display", "block");
}

function sepomex(){
  var cp = $('#cp').val();
  var municipio = [];
  var ciudad = [];

  $.get('https://api-sepomex.hckdrk.mx/query/info_cp/'+cp+'?token={{env("TOKENSEPOMEX", "")}}', {
  }, function(data) {


    var select = document.getElementById("colonia");
    var length = select.options.length;
    for (i = length-1; i >= 0; i--) {
      select.options[i] = null;
    }

    var select = document.getElementById("municipio");
    var length = select.options.length;
    for (i = length-1; i >= 0; i--) {
      select.options[i] = null;
    }

    var select = document.getElementById("ciudad");
    var length = select.options.length;
    for (i = length-1; i >= 0; i--) {
      select.options[i] = null;
    }

    $("#colonia").append(new Option('Seleccionar', null));
    $("#municipio").append(new Option('Seleccionar', null));
    $("#ciudad").append(new Option('Seleccionar', null));
    var entidad = '';
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
          entidad = data[i]['response']['estado'];
        }

        var form_data = new FormData();
        form_data.append('_token', token);

        $.ajax({
        url: '/util/checkstate/'+entidad,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(res){
          console.log(res);
          pars = JSON.parse(res);
            if(pars.message == 'exist'){
              $('#entidad').val(pars.state);
            }
        }
     });

        $("#colonia").append(new Option('Otro', 'Otro'));
        $("#municipio").append(new Option('Otro', 'Otro'));
        $("#ciudad").append(new Option('Otro', 'Otro'));

  });

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
