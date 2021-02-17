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
            <a class="dropdown-item" onclick="alertas('Prioridad');">Prioridad</a>
          </div>
        </div>
      </div>
    </div>

    {{-- DataTable starts --}}
    <div  class="table-responsive">
      <table id="tbbuzon" class="table data-list-view">
        <thead>
        <tr>
          <th>ID</th>

          <th>Titulo</th>
          <th>Descripción</th>
          <th>Estatus</th>
          <th>Observación</th>
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
            <h4 class="modal-title" id="myModalLabel33">Modificar estatus </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="/buzon/editar"  enctype="multipart/form-data"  method="POST" class="steps-validation wizard-circle" id="formss" name="formss">
            @csrf

            <input type="hidden" id="inid" value="" name='id'>

            <div class="modal-body">
              <label>Observación: </label>
              <div class="form-group">
                    <textarea type="text" name="observacion" id="observacion" placeholder="Observacion"
                              class="form-control required" required></textarea>
              </div>

              <label>estatus: </label>
              <div class="form-group">
                <select class="form-control required" id="estatus" name="estatus" required>

                  <option selected disabled>Seleccione la prioridad</option>

                  <option value="2">En proceso</option>
                  <option value="3">Revisado</option>


                </select>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success" >Aplicar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    {{-- add new sidebar ends --}}
  </section>
  {{-- Data list view end --}}




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
  <script src="{{ asset('js/scripts/ui/data-buzon.js') }}?{{rand()}}"></script>

  <script>
    $(document).ready(function () {

        $(function() {
        $(document).on('click', 'button[type="button"]', function(event) {
          let id = this.id;
          let id2 = this.ariaLabel;
          let id3 = this.value;

          var value =id;
          var value2 =id2;
          var value3 =id3;
          if(value3=="Nuevo")
          {
            $('#estatus').val(1);
          }
          else if(value3=="En proceso"){
            $('#estatus').val(2);
          }
          else {
            $('#estatus').val(3);
          }
          $('#inid').val(value);
          $('#observacion').val(value2);

        });
      });
      @if (session('message'))
      Swal.fire({
        title: "Bien!",
        text: "Alerta Interna editada correctamente!",
        type: "success",
        confirmButtonClass: 'btn btn-primary',
        buttonsStyling: false,
        animation: false,
        customClass: 'animated tada'
      });
      @endif
    });
  </script>
@endsection
