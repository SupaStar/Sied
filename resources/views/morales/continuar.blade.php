@extends('layouts/contentLayoutMaster')

@section('title', $pageConfigs['pageName'] )


@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/wizard.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/nouislider.min.css')) }}">

  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">


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
              <form action="/morales/continuar/credito/{{$id}}" enctype="multipart/form-data" method="POST" class="steps-validation wizard-circle" id="formss" name="formss">
                @csrf
                <H6>Tipo de Crédito</H6>
                <fieldset>
                  <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <select class="form-control required"  name="tcredito" id="tcredito" onchange="getncontract()" >
                          <option selected disabled>Seleccionar</option>
                          <option value="PYME" >PYME</option>
                          <option value="GRUPAL" >GRUPAL</option>
                          <option value="INDIVIDUAL" >INDIVIDUAL</option>
                          <option value="NOMINA" >NOMINA</option>
                        </select>
                        <hr>
                        <label class="form-control">Limite de crédito</label>
                        @if($moral->limite_credito == "" || $moral->limite_credito == null)
                          <input id="limite" name="limite" class="form-control required" placeholder="Escribe el limite de crédito">
                        @else
                          <input id="limite" name="" class="form-control required" value="{{$moral->limite_credito}}" readonly>
                        @endif
                      </div>
                    </div>
                  </div>
                </fieldset>
                <H6># Contrato</H6>
                <fieldset>
                  <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <input type="text" class="form-control required" name="ncontrato" id="ncontrato" autocomplete="off" >
                      </div>
                    </div>
                  </div>
                </fieldset>
                <H6>Monto del Crédito</H6>
                <fieldset>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr" id="sliderUi"></div>
                        <input type="number" class="form-control required" name="sliderInput" id="sliderInput" step="1" min=0 max=100000>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <H6>Forma de Pago</H6>
                <fieldset>
                  <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <select class="form-control required"  name="fpago" id="fpago" >
                          <option selected disabled>Seleccionar</option>
                          <option value="VENCIMIENTO" >Un solo vencimiento</option>
                          <option value="AMORTIZACIONES" >Amortizaciones</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <H6>Frecuencia de Pagos</H6>
                <fieldset>
                  <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <select class="form-control required"  name="frecuencia" id="frecuencia" >
                          <option selected disabled>Seleccionar</option>
                          <option value="semanales" >Semanales</option>
                          <option value="quincenales" >Quincenales</option>
                          <option value="menusales" >Menusales</option>
                          <option value="trimestrales" >Trimestrales</option>
                          <option value="semestrales">Semestrales</option>
                          <option value="anuales" >Anuales</option>
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
                        <input type="number" class="form-control required" name="numero_plazo" id="plazoSliderInput" step="1" min=0
                               max=120>
                      </div>
                    </div>
                  </div>
                </fieldset>

                <H6>Amortizaciones</H6>
                <fieldset>
                  <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <select class="form-control required"  name="amortizaciones" id="amortizaciones" >
                          <option selected disabled>Seleccionar</option>
                          <option value="Pagos iguales" >Pagos iguales</option>
                          <option value="Amortizaciones iguales" >Amortizaciones iguales</option>
                          <option value="A la medida" >A la medida</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <H6>IVA</H6>
                <fieldset>
                  <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <select class="form-control required"  name="iva" id="iva" >
                          <option selected disabled>Seleccionar</option>
                          <option value="SI" >SI</option>
                          <option value="NO" >NO</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <H6>Tasa de Interes</H6>
                <fieldset>
                  <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <input type='number' step="any" min="0" name="tinteres" id="tinteres"  class="form-control required" />
                      </div>
                    </div>
                  </div>
                </fieldset>
                <H6>Fecha de Disposición</H6>
                <fieldset>
                  <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <input required type='text' class="form-control pickadate-disable" id="disposicion" onchange="calculate()" name="disposicion" />
                        <br>
                        <input required type="text" class="form-control required" name="titular" id="titular" placeholder="Nombre del titular de la cuenta" autocomplete="off" >
                        <br>
                        <select required class="form-control required"  name="tipo_cuenta" id="tipo_cuenta" >
                          <option selected disabled>Seleccione su tipo de cuenta</option>
                          <option value="1" >Numero de Tarjeta</option>
                          <option value="2" >Clabe Interbancaria</option>
                        </select>
                        <br>
                        <input minlength="15" required disabled type="number" class="form-control required" name="numero_cuenta_clabe" id="numero_cuenta_clabe" placeholder="Numero de cuenta o tarjeta" autocomplete="off" >
                        <br>
                        <select class="form-control required" id="recurso" name="recurso">
                          <option selected disabled>Seleccionar destino recursos</option>
                          @foreach($destino as $data)
                            @if(isset($datos->destino_recursos))
                              @if($datos->destino_recursos == $data->id)
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
                </fieldset>
                <H6>Amortización</H6>
                <fieldset>
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

  <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>

@endsection
@section('page-script')
  <!-- Page js files -->
  <script src="{{ asset(mix('js/scripts/forms/wizard-steps.js')) }}?{{rand()}}"></script>
  <script src="{{ asset('js/curp.js') }}?{{rand()}}"></script>



  <script>

    var token = '{{csrf_token()}}';

    $(document).ready(function(){

      var slcchange = document.getElementById("tipo_cuenta");
      slcchange.addEventListener("change", function() {
        if(slcchange.value==1)
        {
          var inputcuenta=document.getElementById("numero_cuenta_clabe")
          inputcuenta.removeAttribute("disabled")
          inputcuenta.setAttribute("maxlenght",18);
        } else
        {
          var inputcuenta=document.getElementById("numero_cuenta_clabe")
          inputcuenta.removeAttribute("disabled")
          inputcuenta.setAttribute("maxlenght",16);
        }

      });
      table();
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


    });

    function getncontract()
    {
      var ncontrato = $('#tcredito').val();
      var form_data = new FormData();
      form_data.append('ncontrato', ncontrato);
      form_data.append('id', {{$id}});
      form_data.append('_token', token);

      $.ajax({
        url: '/util/generateContract',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(res){
          console.log(res);
          pars = JSON.parse(res);
          if(pars.code == '200'){
            $('#ncontrato').val(pars.message);
          }
        }
      });

    }

    function calculate()
    {
      var midata = {
        tcredito: $('#tcredito').val(),
        ncontrato: $('#ncontrato').val(),
        sliderInput: $('#sliderInput').val(),
        fpago: $('#fpago').val(),
        frecuencia: $('#frecuencia').val(),
        plazoSliderInput: $('#plazoSliderInput').val(),
        amortizaciones: $('#amortizaciones').val(),
        iva: $('#iva').val(),
        tinteres: $('#tinteres').val(),
        disposicion: $('#disposicion').val()
      };

      table(midata);
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
            data: 'fecha',
            name: 'fecha'
          },
          {
            data: 'dias',
            name: 'dias'
          },
          {
            data: 'disposición',
            name: 'disposición'
          },
          {
            data: 'saldo',
            name: 'saldo'
          },
          {
            data: 'comisión',
            name: 'comisión'
          },
          {
            data: 'amortización',
            name: 'amortización'
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
            data: 'flujo',
            name: 'flujo'
          }
        ],
        ajax: {
          url: "/clientes/amortizacion",
          data: {
            "data": data
          }
        }
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
