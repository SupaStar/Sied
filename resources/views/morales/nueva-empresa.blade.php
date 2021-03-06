
@extends('layouts/contentLayoutMaster')

@section('title', $pageConfigs['pageName'] )

@section('page-style')
        <!-- Page css files -->
        <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/wizard.css')) }}">
        <link rel="stylesheet" href="{{ asset('datepicker/datepicker.css') }}">
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
        <link rel="stylesheet" href="https://unpkg.com/vue-form-wizard/dist/vue-form-wizard.min.css">

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

            span.error{
              color:#e74c3c;
              font-size:20px;
              display:flex;
              justify-content:center;
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
            <form action="/morales/crear" enctype="multipart/form-data" method="POST"
                class="steps-validation wizard-circle" id="formss" name="formss">
              <form-wizard color="#7367f0" title="" subtitle="" next-button-text="Siguiente" next-button-text="Siguiente"
                back-button-text="Anterior" finish-button-text="Finalizar" @on-complete="onComplete" @on-error="handleErrorMessage">
                @csrf
                <!-- Step 1 -->
                <tab-content title="Sociedad" icon="step-icon feather icon-user" :before-change="()=>validateAsync('paso0')" >
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
                          <input type="text" class="form-control required" name="nombre" id="nombreEmpresa" required data-validation-required-message="This First Name field is required">
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
                              <p> <i class="feather icon-info mr-1 align-middle"></i> Siguiente Socio.</p>
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
                            <input type="file" @change="validateine(index)" data-toggle="tooltip" data-placement="top"
                              title="Solo se permiten imagenes JPG, JPEG, PNG orientadas horizontalmente"
                              class="form-control required" :id="'inefront'+index"
                              :name="'personasMorales['+index+'][inefront]'" accept=".jpg, .jpeg, .png">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="lastName3">
                              INE TRASERA
                            </label>
                            <input type="file" @change="validateine(index)" data-toggle="tooltip" data-placement="top"
                              title="Solo se permiten imagenes JPG, JPEG, PNG orientadas horizontalmente"
                              class="form-control required" :id="'ineback'+index"
                              :name="'personasMorales['+index+'][ineback]'" accept=".jpg, .jpeg, .png">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 offset-md-4">
                          <div class="form-group">
                            <label for="curp">
                              CURP
                            </label>
                            <input type="text" class="form-control required" :name="'personasMorales['+index+'][curp]'"
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
                            <input type="text" class="form-control required" :id="'nombre'+index"
                              :name="'personasMorales['+index+'][name]'">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="lastName3">
                              Apellido Paterno
                            </label>
                            <input type="text" class="form-control required" :id="'apellidop'+index"
                              :name="'personasMorales['+index+'][lastname]'">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="lastName3">
                              Apellido Materno
                            </label>
                            <input type="text" class="form-control required" :id="'apellidom'+index"
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
                            <select class="form-control" :name="'personasMorales['+index+'][gender]'"
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
                              :name="'personasMorales['+index+'][date_birth]'" :id="'nacimiento'+index">

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
                          <button v-if="index == (integrantes.length -1)" type="button" @click="agregarIntegrante"
                            class="btn btn-icon rounded-circle btn-outline-primary mr-1 mb-1 waves-effect waves-light"><i
                              class="feather icon-plus"></i></button>
                        </div>
                        <div v-if="index > 1" class="col-md-6">
                          <button type="button" @click="removerIntegrante(index)"
                            class="btn btn-icon rounded-circle btn-outline-danger mr-1 mb-1 waves-effect waves-light float-right"><i
                              class="feather icon-trash"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </tab-content>

                <!-- Step 2 -->
                <tab-content title="Dirección" icon="step-icon feather icon-map-pin" :before-change="()=>validateAsync('paso1')">
                  <div id="paso1">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="proposalTitle3">
                            Calle
                          </label>
                          <input type="text" class="form-control required" id="street" name="street">
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
                          <input type="text" class="form-control " name="incide">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="proposalTitle3">
                            Codigo Postal
                          </label>
                          <input type="text" class="form-control required" name="pc" id="cp" @change="sepomex">
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
                          <select class="form-control" id="entidad" name="ef">
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
                <tab-content title="Datos Adicionales" icon="step-icon feather icon-file-plus" :before-change="()=>validateAsync('paso2')">
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
                          <input type="text" class="form-control required" id="memail" name="email"
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

                <tab-content title="Documentos Requeridos" icon="step-icon feather icon-folder-plus" :before-change="()=>validateAsync('paso3')">
                  <div id="paso3">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="eventName3">
                          Acta Constitutiva
                        </label>
                        <input type="file" class="form-control required" id="eventName3" name="filecurp">
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
                          RFC
                        </label>
                        <input type="file" class="form-control " id="eventName3" name="filerfc"
                          accept=".jpg, .jpeg, .png">
                      </div>
                    </div>
                  </div>
                </div>
                </tab-content>
                <tab-content title="Croquis" icon="step-icon feather icon-folder-plus" :before-change="()=>validateAsync('paso4')">
                  <div id="paso4">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="eventName3">
                            Croquis
                          </label>
                          <div id="map"></div>
                          <input aria-label="latitud" id="lat" name="lat" hidden>
                          <input aria-label="longitud" id="long" name="long" hidden>
                        </div>
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


@endsection
@section('page-script')
        <!-- Page js files -->

        {{-- <script src="{{ asset(mix('js/scripts/forms/wizard-steps.js')) }}?{{rand()}}"></script> --}}
        <script src="/js/scripts/vue.min.js"></script>
        <script src="https://unpkg.com/vue-form-wizard/dist/vue-form-wizard.js"></script>
        <script src="{{ asset('js/curp.js') }}?{{rand()}}"></script>
        <script src="{{ asset('datepicker/datepicker.js') }}?{{rand()}}"></script>
        <script
          src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC2KCt-r7yAiuktmaXWVtTaVjilIcCPFM&libraries=&v=weekly"
          async></script>
        <script src="{{ asset('js/scripts/maps.js') }}?{{rand()}}"></script>
<script>
Vue.use(VueFormWizard);
var app = new Vue({
  el: '#validation',
  data: {
    message: 'Hello Vue!',
    integrantes: [1,1],
    tabla: {},
    errorMsg: null,
    token : '{{csrf_token()}}',
    count:0
  },
  mounted() {
    (function (global, factory) {
      typeof exports === 'object' && typeof module !== 'undefined' ? factory(require('jquery')) :
        typeof define === 'function' && define.amd ? define(['jquery'], factory) :
        (factory(global.jQuery));
    }(this, (function ($) {
      'use strict';

      jQuery.fn.datepicker.languages['es-ES'] = {
        format: 'dd/mm/yyyy',
        days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        daysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        daysMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        weekStart: 1,
        months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
      };
    })));




    @for($i = 0; $i <= 1; $i++)
    $("#nacimiento{{$i}}").datepicker({
      format: 'mm-dd-yyyy',
      language: 'es-ES'
    });

    @endfor

    $('.pickadate-translations').pickadate({
      max: new Date({{date('Y') - 18}}, 1, 1),
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
  },
  methods: {
    handleErrorMessage(errorMsg){
          this.errorMsg = errorMsg;
        },
    validateAsync(id){
      return new Promise((resolve,reject)=>{
        var elms = Array.from(document.getElementById(id).getElementsByClassName("required"));
        var label = document.createElement('label');
        label.innerText="Este campo es requerido."
        label.className="input-error danger";
        console.log(elms);
        var errorFlag=false
        elms.forEach((element,index) => {
          if(element.value == ""){
            errorFlag=true;
            element.parentNode.appendChild(label.cloneNode(true));

          }
        });
        if(errorFlag){
          reject('Complete los campos requeridos.')
        }else{
          resolve(true)
        }
      })
    },
    onComplete(){
      $('#formss').submit();
    },
    agregarIntegrante(){
      this.integrantes.push(1);
      var index= this.integrantes.length -1;
      Vue.nextTick()
      .then(function () {
        $("#nacimiento"+index).datepicker({
          format: 'mm-dd-yyyy',
          language: 'es-ES'
        });
      })
    },
    removerIntegrante(index){
      this.integrantes.splice(index,1);

    },
    checkemail() {
      var email = $('#memail').val();
      this.jsShowWindowLoad();
      var form_data = new FormData();
      form_data.append('_token', this.token);

      $.ajax({
        url: '/util/checkemail/' + email,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: (res) => {
          pars = JSON.parse(res);
          if (pars.message == 'exist') {
            $('#memail').val('');
            this.jsRemoveWindowLoad();
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
            this.jsRemoveWindowLoad();
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

    },
    jsRemoveWindowLoad() {
      // eliminamos el div que bloquea pantalla
      $("#WindowLoad").remove();
    },
    jsShowWindowLoad() {
      //si no enviamos message se pondra este por defecto
      message = '<img src="{{asset('images/loader.gif ')}}" alt="Por Favor Espere...">';

      //centrar imagen gif
      height = 20; //El div del titulo, para que se vea mas arriba (H)
      var ancho = 0;
      var alto = 0;

      //obtenemos el ancho y alto de la ventana de nuestro navegador, compatible con todos los navegadores
      if (window.innerWidth == undefined) ancho = window.screen.width;
      else ancho = window.innerWidth;
      if (window.innerHeight == undefined) alto = window.screen.height;
      else alto = window.innerHeight;

      //operación necesaria para centrar el div que muestra el message
      var heightdivsito = alto / 2 - parseInt(height) / 2; //Se utiliza en el margen superior, para centrar

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

    },
    checkcurp(id) {

        var curp = $("#curp" + id).val();
        this.jsShowWindowLoad();
        var myheaders = new Headers({
          dataType: 'text'
        });
        myheaders.append("X-CSRF-TOKEN", this.token);
        fetch("/util/checkcurp/" + curp, {
          method: 'POST',
          headers: myheaders
        }).then(async (response) => {
          res = await response.json();
          console.log(res)
          this.jsRemoveWindowLoad();
          if (res.estatus == "OK") {
            var estado = res.curp.substring(11, 13);
            $('#nombre' + id).val(res.nombre);
            $('#apellidop' + id).val(res.apellidoPaterno);
            $('#apellidom' + id).val(res.apellidoMaterno);
            $("#lnacimiento" + id).val(estado);
            Swal.fire({
              title: "¡Bien!",
              text: "CURP validada",
              type: "success",
              confirmButtonClass: 'btn btn-primary',
              buttonsStyling: false,
              animation: false,
              customClass: 'animated tada'
            });

          } else if (res.estatus == "EXISTE"){
            var estado = res.curp.substring(11, 13);
            $('#nombre' + id).val(res.nombre);
            $('#apellidop' + id).val(res.apellidoPaterno);
            $('#apellidom' + id).val(res.apellidoMaterno);
            $("#lnacimiento" + id).val(estado);
            Swal.fire({
              title: "Cliente!",
              text: "Este cliente se encuentra registrado como persona fisica",
              type: "warning",
              confirmButtonClass: 'btn btn-primary',
              buttonsStyling: false,
              animation: false,
              customClass: 'animated tada'
            });

          }
          else {
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


    },
    validateine(id) {

        var inefront = $('#inefront' + id).val();
        var ineback = $('#ineback' + id).val();

        if (inefront != '' && ineback != '') {
          this.jsShowWindowLoad();

          var finefront = $('#inefront' + id).prop('files')[0];
          var fineback = $('#ineback' + id).prop('files')[0];

          var form_data = new FormData();
          form_data.append('inefront', finefront);
          form_data.append('ineback', fineback);
          form_data.append('_token', this.token);

          $.ajax({
            url: '/util/imgto64',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: (res)=> {
              pars = JSON.parse(res);
              if (pars.message == 'fail') {
                this.jsRemoveWindowLoad();
                Swal.fire({
                  title: "Error!",
                  text: "Alguno de los documentos no es formato de imagen valido!",
                  type: "error",
                  confirmButtonClass: 'btn btn-primary',
                  buttonsStyling: false,
                  animation: false,
                  customClass: 'animated tada'
                });
              } else if (pars.message == 'ManualChecking') {
                this.jsRemoveWindowLoad();
                Swal.fire({
                  title: "No se pudo verificar!",
                  text: "Se tiene que verificar visualmente!",
                  type: "warning",
                  confirmButtonClass: 'btn btn-primary',
                  buttonsStyling: false,
                  animation: false,
                  customClass: 'animated tada'
                });
              } else if (pars.message == 'ControlListManualChecking') {
                this.jsRemoveWindowLoad();
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
                  if (pars.documentData[i].type == 'Name') {
                    $('#nombre' + id).val(pars.documentData[i].value);
                  }
                  if (pars.documentData[i].type == 'FatherSurname') {
                    $('#apellidop' + id).val(pars.documentData[i].value);
                  }
                  if (pars.documentData[i].type == 'MotherSurname') {
                    $('#apellidom' + id).val(pars.documentData[i].value);
                  }

                  if (pars.documentData[i].type == 'PersonalNumber') {
                    var curp = pars.documentData[i].value;
                    $('#curp' + id).val(curp);
                  }

                  if (pars.documentData[i].type == 'Sex') {
                    document.ready = document.getElementById("genero" + id).value = pars.documentData[i].value;
                  }

                  if (pars.documentData[i].type == 'Nationality') {
                    document.ready = document.getElementById("nacionalidad" + id).value = pars.documentData[i].value;
                  }

                  if (pars.documentData[i].type == 'DateOfBirth') {

                    var gdate = pars.documentData[i].value;
                    var gdatearray = gdate.split("/");
                    var gnewdate = gdatearray[1] + '/' + gdatearray[0] + '/' + gdatearray[2];

                    var stringDate = gnewdate;
                    var d = new Date(stringDate);
                    var date = ("0" + d.getDate()).slice(-2);
                    var month = ("0" + (d.getMonth() + 1)).slice(-2);
                    var year = d.getFullYear();
                    $('#nacimiento' + id).val(month + '-' + date + '-' + year);

                    $('[data-toggle="datepicker' + id + '"]').datepicker('setDate', month + '-' + date + '-' + year);

                  }

                }
                var listIndex = 0;
                for (i = 0; i < pars.documentVerifications.length; i++) {
                  if (pars.documentVerifications[i].category == "ControlList" && pars.documentVerifications[i].inputFields != null && pars.documentVerifications[i].name != "Sin coincidencias") {
                    console.log(pars.documentVerifications[i].inputFields[0].value);
                    var html = '<input name="listasNegras[' + listIndex + '][name]" type="hidden" value="' + pars.documentVerifications[i].name + '">';
                    html += '<input name="listasNegras[' + listIndex + '][value]" type="hidden" value="' + pars.documentVerifications[i].inputFields[0].value + '">';
                    $("#firstRow" + id).append(html)
                    listIndex++;
                  }
                }

                document.ready = document.getElementById("pais_nacimiento" + id).value = 303;


                this.jsRemoveWindowLoad();
                Swal.fire({
                  title: "Existente!",
                  text: "Ine verificada!",
                  type: "success",
                  confirmButtonClass: 'btn btn-primary',
                  buttonsStyling: false,
                  animation: false,
                  customClass: 'animated tada'
                }).then((result) => {
                  this.checkcurp(id)
                });
                console.log(pars);
              }
            }
          });

        }



    },
    nsocio(id) {
      var nid = id + 1;
      $("#otsocio" + nid).css("display", "block");
      $("#socio" + nid).css("display", "block");
      $("#nsocio" + nid).css("display", "block");
    },
    conyuge() {
      $("#conyuge").css("display", "block");
    },
    sepomex() {
      initMap();
      var cp = $('#cp').val();
      var municipio = [];
      var ciudad = [];

      $.get('https://api-sepomex.hckdrk.mx/query/info_cp/' + cp, {}, function (data) {


        var select = document.getElementById("colonia");
        var length = select.options.length;
        for (i = length - 1; i >= 0; i--) {
          select.options[i] = null;
        }

        var select = document.getElementById("municipio");
        var length = select.options.length;
        for (i = length - 1; i >= 0; i--) {
          select.options[i] = null;
        }

        var select = document.getElementById("ciudad");
        var length = select.options.length;
        for (i = length - 1; i >= 0; i--) {
          select.options[i] = null;
        }

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

    }
  }
})

</script>
@endsection
