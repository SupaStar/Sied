@extends('layouts/contentLayoutMaster')

@section('title', $pageConfigs['pageName'] )

@section('page-style')
<!-- Page css files -->
<link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/wizard.css')) }}">
<link rel="stylesheet" href="{{ asset('datepicker/datepicker.css') }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/nouislider.min.css')) }}">

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
                        <form action="/morales/morales" enctype="multipart/form-data" method="POST" class="steps-validation wizard-circle" id="formss" name="formss">
                            @csrf
                            <H6>Monto del Crédito</H6>
                            <fieldset>
                              <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr" id="sliderUi"></div>
                                        <input type="number" class="form-control" name="sliderInput" id="sliderInput" step="1" min=0 max=100000>
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
                                  <select class="form-control"  name="tcredito" id="tcredito" onchange="credito()">
                                    <option selected disabled>Seleccionar</option>
                                    <option value="NAT JURÍDICA" >NAT JURÍDICA</option>
                                    <option value="POR AMORTIZACIÓ DE CAPITAL" >POR AMORTIZACIÓ DE CAPITAL</option>
                                    <option value="POR LOS INTERESES" >POR LOS INTERESES</option>
                                    <option value="POR LO PAGOS" >POR LO PAGOS</option>
                                    <option value="POR SU FIN" >POR SU FIN</option>
                                  </select>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <H6>Pagos</H6>
                    <fieldset>
                      <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <select class="form-control"  name="pagos" id="pagos">
                                <option selected disabled>Seleccionar</option>
                              </select>
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
<script src="{{ asset(mix('vendors/js/extensions/nouislider.min.js')) }}"></script>

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



    });

    function credito()
    {
      var tcredito = $('#tcredito').val();

      var select = document.getElementById("pagos");
      var length = select.options.length;
      for (i = length-1; i >= 0; i--) {
        select.options[i] = null;
      }

      if(tcredito == 'NAT JURÍDICA')
      {
        $("#pagos").append(new Option('SIMPLE', 'SIMPLE'));
        $("#pagos").append(new Option('CUENTA CORRIENTE', 'CUENTA CORRIENTE'));
      } else if (tcredito == 'POR AMORTIZACIÓ DE CAPITAL'){
        $("#pagos").append(new Option('AL VENCIMIENTO', 'AL VENCIMIENTO'));
        $("#pagos").append(new Option('CON AMORTIZACIONES', 'CON AMORTIZACIONES'));
        $("#pagos").append(new Option('CON AMORTIZACIONES CON PERIODO DE GRACIA', 'CON AMORTIZACIONES CON PERIODO DE GRACIA'));
      } else if (tcredito == 'POR LOS INTERESES'){
        $("#pagos").append(new Option('SOBRE SALDOS INSOLUTOS', 'SOBRE SALDOS INSOLUTOS'));
        $("#pagos").append(new Option('INTERES GLOBAL', 'INTERES GLOBAL'));
        $("#pagos").append(new Option('INTERÉS FIJO', 'INTERÉS FIJO'));
        $("#pagos").append(new Option('INTERÉS VARIABLE', 'INTERÉS VARIABLE'));
      } else if (tcredito == 'POR LO PAGOS'){
        $("#pagos").append(new Option('PAGOS IGUALES', 'PAGOS IGUALES'));
        $("#pagos").append(new Option('AMORTIZACIONES DEC APITAL IGUALES', 'AMORTIZACIONES DEC APITAL IGUALES'));
        $("#pagos").append(new Option('A LA MEDIDA', 'A LA MEDIDA'));
      } else if (tcredito == 'POR SU FIN'){
        $("#pagos").append(new Option('NOMINA', 'NOMINA'));
        $("#pagos").append(new Option('GRUPAL', 'GRUPAL'));
        $("#pagos").append(new Option('INDIVIDUAL', 'INDIVIDUAL'));
        $("#pagos").append(new Option('PYME', 'PYME'));
        $("#pagos").append(new Option('REFACCIONARIO', 'REFACCIONARIO'));
        $("#pagos").append(new Option('HIPOTECARIO', 'HIPOTECARIO'));
        $("#pagos").append(new Option('CAPITAL DE TRABAJO', 'CAPITAL DE TRABAJO'));
        $("#pagos").append(new Option('AUTOMOTRIZ', 'AUTOMOTRIZ'));
        $("#pagos").append(new Option('AVIO', 'AVIO'));
      }
    }
</script>
@endsection
