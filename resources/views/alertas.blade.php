@extends('layouts/contentLayoutMaster')

@section('title', $pageConfigs['pageName'] )

@section('vendor-style')
  {{-- vendor files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/file-uploaders/dropzone.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')) }}">
@endsection
@section('page-style')
  {{-- Page css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/plugins/file-uploaders/dropzone.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/pages/data-list-view.css')) }}">
  <style>
    body{
      margin-top:40px;
    }

    .stepwizard-step p {
      margin-top: 10px;
    }

    .stepwizard-row {
      display: table-row;
    }

    .stepwizard {
      display: table;
      width: 100%;
      position: relative;
    }

    .stepwizard-step button[disabled] {
      opacity: 1 !important;
      filter: alpha(opacity=100) !important;
    }

    .stepwizard-row:before {
      top: 14px;
      bottom: 0;
      position: absolute;
      content: " ";
      width: 100%;
      height: 1px;
      background-color: #ccc;
      z-order: 0;

    }

    .stepwizard-step {
      display: table-cell;
      text-align: center;
      position: relative;
    }

    .btn-circle {
      width: 30px;
      height: 30px;
      text-align: center;
      padding: 6px 0;
      font-size: 12px;
      line-height: 1.428571429;
      border-radius: 15px;
    }
  </style>
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
            <h4 class="modal-title" id="myModalLabel33">Modificar Alerta </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="container">
            <div class="stepwizard">
              <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step">
                  <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                  <p>Recabando sustento</p>
                </div>
                <div class="stepwizard-step">
                  <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
                  <p>Dictamen</p>
                </div>
                <div class="stepwizard-step">
                  <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
                  <p>Acuse</p>
                </div>
                <div class="stepwizard-step">
                  <a href="#step-4" type="button" class="btn btn-default btn-circle" disabled="disabled">4</a>
                  <p>Observaciones</p>
                </div>
              </div>
            </div>
            <form action="/alertas/editar"  enctype="multipart/form-data"  method="POST" class="steps-validation wizard-circle" id="formss" name="formss">
              @csrf
              <input type="hidden" id="inid" value="" name='id'>
              <div class="row setup-content" id="step-1">
                <div class="modal-body">
                  <label>Sustento:  </label>
                  <div class="form-group">
                    <textarea type="text" name="sustento" id="sustento"  placeholder="Observacion" class="form-control required"></textarea>
                    <label>
                      Imagen Acuse
                    </label>
                    <input type="file"  data-toggle="tooltip" data-placement="top"
                           title="Solo se permiten imagenes JPG, JPEG, PNG, cargue por lo menos una imagen"
                           class="form-control required" id="Fsustento" name="Fsustento" accept=".jpg, .jpeg, .png">
                  </div>

                  <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Siguiente</button>
                </div>
              </div>
              <div class="row setup-content" id="step-2">
                <div class="modal-body">
                  <label>Dictamen: </label>
                  <div class="form-group">

                    <textarea type="text" name="dictamen" id="dictamen"  placeholder="Observacion" class="form-control required"></textarea>
                    <label>
                      Imagen Acuse
                    </label>
                    <input type="file"  data-toggle="tooltip" data-placement="top"
                           title="Solo se permiten imagenes JPG, JPEG, PNG, cargue por lo menos una imagen"
                           class="form-control required" id="Fdictamen" name="Fdictamen" accept=".jpg, .jpeg, .png">
                  </div>

                  <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Siguiente</button>
                  <a class="btn btn-primary btn-lg pull-right" type="button" href="#step-1" >Atras</a>
                </div>
              </div>
              <div class="row setup-content" id="step-3">
                <div class="modal-body">
                  <label>Acuse: </label>
                  <div class="form-group">

                    <textarea type="text" name="acuse" id="acuse"  placeholder="Observacion" class="form-control required" required></textarea>
                    <label>
                      Imagen Acuse
                    </label>
                    <input required type="file"  data-toggle="tooltip" data-placement="top"
                           title="Solo se permiten imagenes JPG, JPEG, PNG, cargue por lo menos una imagen"
                           class="form-control required" id="Facuse" name="Facuse" accept=".jpg, .jpeg, .png">
                  </div>

                  <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Siguiente</button>
                </div>
              </div>

              <div class="row setup-content" id="step-4">
                <div class="modal-body">
                  <label>Observación: </label>
                  <div class="form-group">
                    <textarea type="text" name="observacion" id="observacion"  placeholder="Observacion" class="form-control required" required></textarea>
                  </div>
                  <label>estatus: </label>
                  <div class="form-group">
                    <select class="form-control" id="estatus" name="estatus" readonly>

                      <option selected disabled>Seleccione la prioridad</option>
                      <option value="1">Nuevo</option>
                      <option value="2">En proceso</option>


                    </select>
                  </div>
                  <button type="button" class="btn btn-primary btn-lg pull-right" data-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-primary btn-lg pull-right" >Aplicar</button>
                </div>


                </div>

            </form>
          </div>
        </div>
      </div>

    {{-- add new sidebar ends --}}
  </section>
  {{-- Data list view end --}}



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

@endsection
@section('page-script')
  {{-- Page js files --}}
  <script src="{{ asset('js/scripts/ui/data-alertas.js') }}?{{rand()}}"></script>

  <script>
    $(document).ready(function () {
      $(function() {
        $(document).on('click', 'button[id="btnedita"]', function(event) {
          let id = this.name;
          let id2 = this.ariaLabel;
          let id3 = this.value;

          var value =id;
          var value2 =id2;
          var value3 =id3;
          if(value3=="Nuevo")
          {
            $('#estatus').val(1);
          }
          else{
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
      var navListItems = $('div.setup-panel div a'),
        allWells = $('.setup-content'),
        allNextBtn = $('.nextBtn');

      allWells.hide();

      navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
          $item = $(this);

        if (!$item.hasClass('disabled')) {
          navListItems.removeClass('btn-primary').addClass('btn-default');
          $item.addClass('btn-primary');
          allWells.hide();
          $target.show();
          $target.find('input:eq(0)').focus();
        }
      });

      allNextBtn.click(function(){
        var curStep = $(this).closest(".setup-content"),
          curStepBtn = curStep.attr("id"),
          nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
          curInputs = curStep.find("input[type='text'],input[type='url']"),
          isValid = true;

        $(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
          if (!curInputs[i].validity.valid){
            isValid = false;
            $(curInputs[i]).closest(".form-group").addClass("has-error");
          }
        }

        if (isValid)
          nextStepWizard.removeAttr('disabled').trigger('click');
      });

      $('div.setup-panel div a.btn-primary').trigger('click');
    });
  </script>
@endsection
