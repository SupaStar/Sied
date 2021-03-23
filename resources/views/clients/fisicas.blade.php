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
                     aria-controls="home-just" aria-selected="true">CLIENTES REGISTRADOS</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-credito" data-toggle="tab" href="#profile-credito" role="tab"
                     aria-controls="profile-just" aria-selected="true">CLIENTES PENDIENTES</a>
                </li>
              </ul>

              {{-- Tab panes --}}
              <div class="tab-content pt-1">
                <div class="tab-pane active" id="home-just" role="tabpanel" aria-labelledby="home-tab-justified">

                  <section id="data-list-view" class="data-list-view-header">
                    <div class="action-btns d-none">
                      <div class="btn-dropdown mr-1 mb-1">
                        <div class="btn-group dropdown actions-dropodown">
                          <button type="button" class="btn btn-white px-1 py-1 dropdown-toggle waves-effect waves-light"
                                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Filtro
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item" onclick="clients('');">Todos</a>
                            <a class="dropdown-item" onclick="clients('H');">Masculino</a>
                            <a class="dropdown-item" onclick="clients('M');">Femenino</a>
                            <a class="dropdown-item" onclick="clients('Archivados');">Archivados</a>
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
                          <th>Nombre</th>
                          <th>Genero</th>
                          <th>Fecha de Nacimiento</th>
                          <th>Lugar de Nacimiento</th>
                          <th>Listas Negras</th>
                          <th>Grupo</th>
                          <th>Estado</th>
                          <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>

                    {{-- add new sidebar ends --}}
                  </section>
                  {{-- Data list view end --}}


                </div>

                <div class="tab-pane" id="profile-credito" role="tabpanel" aria-labelledby="profile-tab-justified">

                  <section id="data-list-view2" class="data-list-view2-header">
                    <div class="action-btns d-none">
                      <div class="btn-dropdown mr-1 mb-1">
                        <div class="btn-group dropdown actions-dropodown">
                        </div>
                      </div>
                    </div>

                    {{-- DataTable starts --}}
                    <div class="table-responsive">
                      <table class="table data-list-view2">
                        <thead>
                        <tr>
                          <th>Identificación</th>
                          <th>ID Consulta</th>
                          <th>ID Suma</th>
                          <th>Estado</th>
                          <th>Fecha de Solicitud</th>
                          <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>


                    {{-- add new sidebar ends --}}
                  </section>


                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Modal --}}

  <div class="modal fade text-left" id="DescModal" tabindex="-1" role="dialog"
       aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel33">Datos Negocio </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="#">
          <div class="modal-body">
            <label>Nombre: </label>
            <div class="form-group" id="nombre">
            </div>
            <label>Genero: </label>
            <div class="form-group" id="genero">
            </div>
            <label>Fecha de Nacimiento: </label>
            <div class="form-group" id="fnac">
            </div>
            <label>País de Nacimiento: </label>
            <div class="form-group" id="pnac">
            </div>
            <label>Ocupación: </label>
            <div class="form-group" id="ocupacion">
            </div>
            <label>Dirección: </label>
            <div class="form-group" id="direccion">
            </div>
            <label>Telefonos: </label>
            <div class="form-group" id="telefonos">
            </div>
            <label>Email: </label>
            <div class="form-group" id="email">
            </div>
            <label>CURP: </label>
            <div class="form-group" id="curp">
            </div>
            <label>RFC: </label>
            <div class="form-group" id="rfc">
            </div>
            <h3>Datos de Cónyuge: </h3>
            <label>Nombre: </label>
            <div class="form-group" id="cnombre">
            </div>
            <label>Teléfono: </label>
            <div class="form-group" id="ctelefono">
            </div>
            <label>Correo Eletrónico: </label>
            <div class="form-group" id="cemail">
            </div>

            <label>Archivos: </label>
            <div class="form-group" id="imagenes">

            </div>

            <input type="hidden" class="form-control" id="uid">
          </div>
        </form>
      </div>
    </div>
  </div>


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
  <script src="{{ asset('js/scripts/ui/data-clients.js') }}?{{rand()}}"></script>
  <script>
    $(document).ready(function () {
      @if (session('message'))
      Swal.fire({
        title: "Bien!",
        text: "Cliente creado correctamente!",
        type: "success",
        confirmButtonClass: 'btn btn-primary',
        buttonsStyling: false,
        animation: false,
        customClass: 'animated tada'
      });
      @endif
      @if (session('perfil'))
      Swal.fire({
        title: "Bien!",
        text: "Perfil Transacional Actualizado Correctamente!",
        type: "success",
        confirmButtonClass: 'btn btn-primary',
        buttonsStyling: false,
        animation: false,
        customClass: 'animated tada'
      });
      @endif
      @if (session('ebr'))
      Swal.fire({
        title: "Bien!",
        text: "Criterio de Riesgo Actualizado Correctamente!",
        type: "success",
        confirmButtonClass: 'btn btn-primary',
        buttonsStyling: false,
        animation: false,
        customClass: 'animated tada'
      });
      @endif
      @if (session('credito'))
      Swal.fire({
        title: "Bien!",
        text: "Credito Aprobado correctamente!",
        type: "success",
        confirmButtonClass: 'btn btn-primary',
        buttonsStyling: false,
        animation: false,
        customClass: 'animated tada'
      });
      @endif
      @if (session('espera'))
      Swal.fire({
        title: "Bien!",
        text: "Cliente agregado a lista de espera!",
        type: "warning",
        confirmButtonClass: 'btn btn-primary',
        buttonsStyling: false,
        animation: false,
        customClass: 'animated tada'
      });
      @endif

  });



  function edit(id){
  }
        </script>
@endsection
