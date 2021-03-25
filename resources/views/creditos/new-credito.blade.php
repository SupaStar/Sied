@extends('layouts/contentLayoutMaster')

@section('title', $pageConfigs['pageName'] )

@section('page-style')
<!-- Page css files -->
<link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/wizard.css')) }}">
<link rel="stylesheet" href="{{ asset('datepicker/datepicker.css') }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/nouislider.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/file-uploaders/dropzone.min.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('css/pages/data-list-view.css')) }}">

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

    #input-number {
        padding: 7px;
        margin: 15px 5px 5px;
        width: 70px;
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
            <form action="/creditos/crear" enctype="multipart/form-data" method="POST"
              class="steps-validation wizard-circle" id="formss" name="formss">
              @csrf
              <H6>Grupos</H6>
              <fieldset>
                <div class="row">
                  <div class="col-md-4 offset-md-4">
                    <div class="form-group">
                      <label for="curp">
                        Identificador o Nombre del Credito
                      </label>
                      <input type="text" class="form-control required" name="nombre" id="curp">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 table-responsive">
                    <table id="clientesTable" class="table data-list-view">
                      <thead>
                        <tr>
                          <th>Seleccionar</th>
                          <th>Nombre</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($grupos as $grupo)
                          <tr>
                            <td>
                              <input type="radio" name="grupo_id" value="{{$grupo->id}}" required>
                            </td>
                            <td>
                              {{ $grupo->nombre }}
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </fieldset>
              <H6>Monto del Crédito</H6>
              <fieldset>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr" id="sliderUi"></div>
                      <input type="number" class="form-control" name="monto" id="sliderInput" step="1" min=0 max=100000>
                    </div>
                  </div>
                </div>
              </fieldset>
              <H6>Tipo de Crédito</H6>
              <fieldset>
                <div class="row">
                  <div class="col-md-4"></div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <select class="form-control" name="tipo" id="tcredito" onchange="credito()">
                        <option selected disabled>Seleccionar</option>
                        <option value="POR SU FIN">POR SU FIN</option>
                      </select>
                    </div>
                  </div>
                </div>
              </fieldset>
              <H6>Tipo de Crédito</H6>
              <fieldset>
                <div class="row">
                  <div class="col-md-4"></div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <select class="form-control" name="pagos" id="pagos">
                        <option selected disabled>Seleccionar</option>
                      </select>
                    </div>
                  </div>
                </div>
              </fieldset>
              <H6>Plazo de Credito</H6>
              <fieldset>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr" id="plazoSliderUi"></div>
                      <input type="number" class="form-control" name="numero_plazo" id="plazoSliderInput" step="1" min=0
                        max=120>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="unidad_plazo" value="dias" required>
                        <span class="vs-radio vs-radio-lg">
                          <span class="vs-radio--border"></span>
                          <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Dias</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="vs-radio-con vs-radio-primary">
                        <input type="radio" name="unidad_plazo" value="meses">
                        <span class="vs-radio vs-radio-lg">
                          <span class="vs-radio--border"></span>
                          <span class="vs-radio--circle"></span>
                        </span>
                        <span class="">Meses</span>
                      </div>
                    </div>
                  </div>
                </div>
          </fieldset>
          <H6>Amortización</H6>
          <fieldset>
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
<script src="{{ asset(mix('vendors/js/extensions/nouislider.min.js')) }}"></script>

<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.select.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>

@endsection
@section('page-script')
<!-- Page js files -->
<script src="{{ asset(mix('js/scripts/forms/wizard-steps.js')) }}?{{rand()}}"></script>
<script src="{{ asset('js/curp.js') }}?{{rand()}}"></script>
<script src="{{ asset('datepicker/datepicker.js') }}?{{rand()}}"></script>


<script>
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

    $(document).ready(function(){

        var html5Slider = document.getElementById('sliderUi');

        noUiSlider.create(html5Slider, {
            start: [10],
            connect: true,
            range: {
                'min': 0,
                'max': 100000
            }
        });

        var inputNumber = document.getElementById('sliderInput');

        html5Slider.noUiSlider.on('update', function (values, handle) {
            var value = values[handle];
                inputNumber.value = Math.round(value);
        });

        inputNumber.addEventListener('change', function () {
            html5Slider.noUiSlider.set([this.value, null]);
        });

        var plazoHtml5Slider = document.getElementById('plazoSliderUi');
        noUiSlider.create(plazoHtml5Slider, {
            start: [10],
            connect: true,
            range: {
                'min': 0,
                'max': 120
            }
        });

        var plazoInputNumber = document.getElementById('plazoSliderInput');

        plazoHtml5Slider.noUiSlider.on('update', function (values, handle) {
            var value = values[handle];
                plazoInputNumber.value = Math.round(value);
        });

        plazoInputNumber.addEventListener('change', function () {
            plazoHtml5Slider.noUiSlider.set([this.value, null]);
        });

        var dataListView = $("#clientesTable").DataTable({
            responsive: true,
            select: true,
            columnDefs: [
            {
                orderable: true,
            //  targets: 0,
            //  checkboxes: { selectRow: true }
            }]
        });


    });

    function credito()
    {
      var tcredito = $('#tcredito').val();

      var select = document.getElementById("pagos");
      var length = select.options.length;
      for (i = length-1; i >= 0; i--) {
        select.options[i] = null;
      }

      if (tcredito == 'POR SU FIN'){
        $("#pagos").append(new Option('GRUPAL', 'GRUPAL'));
      }
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
