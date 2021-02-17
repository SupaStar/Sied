@extends('layouts/contentLayoutMaster')

@section('title', $pageConfigs['pageName'] )

@section('vendor-style')
  {{-- vendor files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/file-uploaders/dropzone.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')) }}">
@endsection
@section('page-style')
  <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/wizard.css')) }}">
@endsection

@section('content')
  {{-- Data list view starts --}}
  <section id="data-list-view" class="data-list-view-header">

    <div class="action-btns d-none">
      <div class="btn-dropdown mr-1 mb-1">
        <div class="btn-group dropdown actions-dropodown">
          <button type="button" class="btn btn-white px-1 py-1 dropdown-toggle waves-effect waves-light"
                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Filtro
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item" onclick="alertas('');">Todos</a>
            <a class="dropdown-item" onclick="alertas('Titulos');">Titulos</a>
            <a class="dropdown-item" onclick="alertas('Prioridad');">Prioridad</a>
          </div>
        </div>
      </div>
    </div>

    {{-- DataTable starts --}}
    <div class="table-responsive">
      <table class="table data-list-view">
        <thead>
        <tr>
          <th>ID</th>
          <th>Nombre Cliente</th>
          <th>Credito Id</th>
          <th>Contrato de credito</th>
          <th>Alerta</th>
          <th>Titulo</th>
          <th>Descripcion</th>
          <th>Estatus</th>
          <th>Observacion</th>
          <th>Prioridad</th>
          <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <!--


         --></tbody>
      </table>
    </div>


    <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel33" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel33">Modificar Alerta</h4>
           <div> <br><h4 id="nombre"> </h4><br><h4 id="alertah">  </h4></div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div id="containermodal" class="container">
            <form action="/alertas/editar" enctype="multipart/form-data" method="POST"
                  class="steps-validation wizard-circle" id="formss" name="formss">
              @csrf
              <input type="hidden" id="inid" value="" name='id'>
              <h6>Recabando sustento</h6>
              <fieldset id="field1">
                <div class="modal-body">
                  <div id="lblsustento" hidden class="alert alert-primary"  role="alert">
                    Información ya registrada
                  </div> <br>
                  <label>Sustento: </label>
                  <div class="form-group">
                    <textarea type="text" name="sustento" id="sustento" placeholder="Sustento"
                              class="form-control"></textarea>
                    <label>Documento Sustento</label>
                    <br>
                    <strong id="sustentoSub"></strong>
                    <input required type="file" data-toggle="tooltip" data-placement="top"
                           title="Solo se permiten archivos PDF, cargue por lo menos un pdf"
                           class="form-control" id="Fsustento" name="Fsustento" accept="application/pdf">
                    <div id="linkArSus"></div>
                  </div>
                </div>
              </fieldset>
              <h6>Dictamen</h6>
              <fieldset class="row setup-content" id="step-2">
                <div class="modal-body">

                  <div id="lbldictamen" hidden class="alert alert-primary"  role="alert">
                    Información ya registrada
                  </div> <br>
                  <label>Dictamen: </label>
                  <div class="form-group">
                    <textarea type="text" name="dictamen" id="dictamen" placeholder="Dictamen"
                              class="form-control"></textarea>
                    <label>
                      Documento dictamen:
                    </label>
                    <br>
                    <strong id="sustentoDic"></strong>
                    <input required type="file" data-toggle="tooltip" data-placement="top"
                           title="Solo se permiten archivos PDF, cargue por lo menos un pdf"
                           class="form-control" id="Fdictamen" name="Fdictamen" accept="application/pdf">
                    <div id="linkArDic"></div>
                  </div>
                </div>
              </fieldset>
              <h6>Acuse</h6>
              <fieldset class="row setup-content" id="step-3">
                <div class="modal-body">
                  <div id="lblacuse" hidden class="alert alert-primary"  role="alert">
                    Información ya registrada
                  </div>
                  <br>
                  <label>Acuse: </label>
                  <div class="form-group">
                    <textarea type="text" name="acuse" id="acuse" placeholder="acuse"
                              class="form-control"></textarea>
                    <label>
                      Documento Acuse
                    </label>
                    <strong id="sustentoAcus"></strong>
                    <input required type="file" data-toggle="tooltip" data-placement="top"
                           title="Solo se permiten archivos PDF, cargue por lo menos un pdf"
                           class="form-control" id="Facuse" name="Facuse" accept="application/pdf">
                    <div id="linkArAcus"></div>
                  </div>
                </div>
              </fieldset>
              <h6>Observaciones</h6>
              <fieldset class="row setup-content" id="step-4">
                <div class="modal-body">
                  <label>Observación: </label>
                  <div class="form-group">
                    <textarea type="text" name="observacion" id="observacion" placeholder="Observacion"
                              class="form-control required" required></textarea>
                  </div>
                  <label>estatus: </label>
                  <div class="form-group">
                    <select class="form-control" id="estatus" name="estatus" required>
                      <option selected disabled>Seleccione el estatus</option>
                      <option value="1">Concluido</option>
                    </select>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    {{-- add new sidebar ends --}}
  </section>
  {{-- Data list view end --}}

  <input value="{{route('editar_alerta_api')}}" id="ruta_api" aria-label="ruta api editar" hidden>
  <input hidden value="{{route('encontrar_alerta_api')}}" id="ruta_api_encontrar" aria-label="ruta api encontrar">


  {{-- Modal --}}
  <div class="modal fade text-left" id="myfiles" tabindex="-1" role="dialog"
       aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header bg-info white">
          <h5 class="modal-title" id="myModalLabel130">Archivos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
              <tr>
                <th>Archivo</th>
                <th>Tipo</th>
                <th>Subido</th>
                <th>Ver</th>
                <th>Descargar</th>
              </tr>
              </thead>
              <tbody id="seemyfiles">

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
  {{-- vendor js files --}}
  <script src="{{ asset(mix('vendors/js/extensions/dropzone.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.select.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/modal/components-modal.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/jquery.steps.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset('js/scripts/forms/wizard-steps2.js') }}?{{rand()}}"></script>
@endsection
@section('page-script')
  {{-- Page js files --}}
  <script src="{{ asset('js/scripts/ui/data-alertas.js') }}?{{rand()}}"></script>

  <script>
    $(document).ready(function () {
      $(function () {
        $(document).on('click', 'button[id="btnedita"]', function (event) {
          let id = this.name;
          let id2 = this.ariaLabel;
          let id3 = this.value;



          let rutaApi = $("#ruta_api_encontrar").val() + "/" + id;
          $.ajax({
            type: "get",
            url: rutaApi,
            datatype: "json",
            success: function (response) {
              $("#sustento").val(response.sustento);
              $("#acuse").val(response.acuse);
              $("#dictamen").val(response.dictamen);
              $("#nombre").text("Cliente: "+response.cliente.name);
              $("#alertah").text("Tipo de alerta: "+response.tipo_alerta)
              if (response.archivo_sustento !== ""&&response.archivo_sustento !=null) {
                $("#sustentoSub").html("Ya se tiene un archivo guardado, si deseas reemplazarlo sube otro");
                $("#linkArSus").html("<a class='btn btn-info' href='/uploads/"+response.archivo_sustento+"' aria-label='archivo sustento' target='_blank'>Ver archivo</a>");
                $("#Fsustento").removeAttr("required");
                $("#lblsustento").removeAttr("hidden");

              } else {
                $("#sustentoSub").html("");
                $("#linkArSus").html("");
                $("#Fsustento").attr("required",true);
                $("#lblsustento").attr("hidden",true)
              }
              if (response.archivo_dictamen !== ""&&response.archivo_dictamen !=null) {
                $("#sustentoDic").html("Ya se tiene un archivo guardado, si deseas reemplazarlo sube otro");
                $("#linkArDic").html("<a class='btn btn-info' href='/uploads/"+response.archivo_dictamen+"' aria-label='archivo dictamen' target='_blank'>Ver archivo</a>");
                $("#Fdictamen").removeAttr("required");
                $("#lbldictamen").removeAttr("hidden")
              } else {
                $("#sustentoDic").html("");
                $("#linkArDic").html("");
                $("#Fdictamen").attr("required",true);
                $("#lbldictamen").attr("hidden",true)
              }
              if (response.archivo_acuse !== ""&&response.archivo_acuse !=null) {
                $("#sustentoAcus").html("Ya se tiene un archivo guardado, si deseas reemplazarlo sube otro");
                $("#linkArAcus").html("<a class='btn btn-info' href='/uploads/"+response.archivo_acuse+"' aria-label='archivo acuse' target='_blank'>Ver archivo</a>");
                $("#Facuse").removeAttr("required");
                $("#lblacuse").removeAttr("hidden")

              } else {
                $("#sustentoAcus").html("");
                $("#linkArAcus").html("");
                $("#Facuse").attr("required",true);
                $("#lblacuse").attr("hidden",true)
              }
              let actual = $(".current")[0].children[0].children[1].innerHTML;
              for (var llegar = 1; llegar < actual; llegar++) {
                $(".actions")[2].children[0].children[0].children[0].click();
              }
              let lista = $(".steps")[0].children[0];
              for (var i = 1; i < lista.children.length; i++) {
                lista.children[i].className = "disabled";
              }




            }
          });
          var value = id;
          var value2 = id2;
          var value3 = id3;

          if (value3 == "Nuevo") {
            $('#estatus').val(1);
          } else {
            $('#estatus').val(2);
          }
          $('#inid').val(value);
          $('#observacion').val(value2);

        });
      });
      @if (session('message'))
      Swal.fire({
        title: "Bien!",
        text: "Observacion editada correctamente!",
        type: "success",
        confirmButtonClass: 'btn btn-primary',
        buttonsStyling: false,
        animation: false,
        customClass: 'animated tada'
      });
      @endif


      $('div.setup-panel div a.btn-primary').trigger('click');
    });

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
