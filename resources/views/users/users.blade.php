
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
          <a class="dropdown-item" onclick="users('');">Todos Activos</a>
          <a class="dropdown-item" onclick="users('1');">Administradores</a>
          <a class="dropdown-item" onclick="users('2');">Analistas</a>
          <a class="dropdown-item" onclick="users('5');">Clientes</a>
          <a class="dropdown-item" onclick="users('1000');">Pendientes</a>
          <a class="dropdown-item" onclick="users('3');">Desactivados</a>
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
            <th>Nombre(s)</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Correo Electrónico</th>
            <th>Estatus</th>
            <th>Rol o Perdil</th>
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
@endsection
@section('page-script')
        {{-- Page js files --}}
        <script src="{{ asset('js/scripts/ui/data-users.js') }}?{{rand()}}"></script>
        <script>
$(document).ready(function () {
  @if (session('message'))
                      Swal.fire({
                                title: "Bien!",
                                text: "Usuario creado correctamente!",
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
