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
  <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/wizard.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/pages/data-list-view.css')) }}">
@endsection

@section('content')
  <section id="nav-justified">
    <div class="row">
      <div class="col-sm-12">
        <div class="card overflow-hidden">
          <div class="card-content">
            <div class="card-body">

              <br>
              <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab-justified" data-toggle="tab" href="#home-just" role="tab"
                     aria-controls="home-just" aria-selected="true">Alertas Internas</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-credito" data-toggle="tab" href="#profile-credito" role="tab"
                     aria-controls="profile-just" aria-selected="true">Alertas Internas Concluidas</a>
                </li>

              </ul>

              {{-- Tab panes --}}
              <div class="tab-content pt-1">
                <div class="tab-pane active" id="home-just" role="tabpanel" aria-labelledby="home-tab-justified">
                  <!-- invoice functionality start -->
                  <section class="invoice-print mb-1">
                    <div class="row">
                      <fieldset class="col-12 col-md-5 mb-1 mb-md-0">
                      </fieldset>

                    </div>
                  </section>
                  <!-- invoice functionality end -->
                  <section class="card invoice-page">  {{-- Data list view end --}}

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
                            <th>Tipo de Alerta</th>
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



                      {{-- add new sidebar ends --}}
                    </section>
                  </section>
                </div>

                <div class="tab-pane" id="profile-credito" role="tabpanel" aria-labelledby="profile-tab-justified">
                  <section class="invoice-print mb-1">
                    <div class="row">
                      <fieldset class="col-12 col-md-5 mb-1 mb-md-0">
                      </fieldset>

                    </div>
                  </section>
                  <!-- invoice functionality end -->
                  <section class="card invoice-page">
                    <section id="data-list-view1" class="data-list-view-header">

                      <div class="action-btns d-none">
                        <div class="btn-dropdown mr-1 mb-1">
                          <div class="btn-group dropdown actions-dropodown">

                            <div class="dropdown-menu">
                              <a class="dropdown-item" onclick="alertas2('');">Todos</a>
                              <a class="dropdown-item" onclick="alertas2('Prioridad');">Prioridad</a>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="table-responsive">
                        <table class="table data-list-view1" style="min-width: 1500px; !important;">
                          <thead>
                          <tr>
                            <th>ID</th>
                            <th>Titulo</th>
                            <th>Tipo de Alerta</th>
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



                      {{-- add new sidebar ends --}}
                    </section>
                    {{-- Data list view end --}}



                  </section>

                </div>



              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  {{-- Data list view starts --}}

  {{-- Data list view end --}}

  <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog"
       aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel33">Modificar estatus de Alerta Interna </h4>
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
            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" >Aplicar</button>
          </div>
        </form>
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
