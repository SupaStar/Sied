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
            <h4 class="modal-title" id="myModalLabel33">Agregar Pago </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="/clientes/credito/pago"  enctype="multipart/form-data"  method="POST" class="steps-validation wizard-circle" id="formss" name="formss">
            @csrf

            <input value='id'  name='id'>

            <div class="modal-body">
              <label>Monto: </label>
              <div class="form-group">
                <input type="number" name="monto" step="any" min="0" placeholder="$" class="form-control required" required>
              </div>

              <label>Moneda </label>
              <div class="form-group">
                <select class="form-control" id="moneda" name="moneda" onchange="cmoneda()">
                  <option selected disabled>Seleccionar</option>
                  <option value="Nacional">Nacional</option>
                  <option value="0">Extranjera</option>
                </select>
              </div>

              <div class="form-group" style="display:none" id="cssmoneda">
                <input type='text' class="form-control " placeholder="Moneda" id="nmoneda"  name="nmoneda" />
              </div>

              <label>Forma de Pago </label>
              <div class="form-group">
                <select class="form-control" id="forma" name="forma" onchange="cforma()">
                  <option selected disabled>Seleccionar</option>
                  <option value="Efectivo">Efectivo</option>
                  <option value="Transferencia">Transferencia</option>
                  <option value="Cheques">Cheques</option>
                  <option value="0">Otro</option>
                </select>
              </div>

              <div class="form-group" style="display:none" id="cssforma">
                <input type='text' class="form-control " placeholder="Forma de Pago" id="nforma"  name="nforma" />
              </div>

              <div  style="display:none" id="trforma">
                <label>Lugar de Pago </label>
                <div class="form-group">
                  <select class="form-control" id="clforma" name="forma" onchange="lforma()">
                    <option selected disabled>Seleccionar</option>
                    <option value="Internacional">Internacional</option>
                    <option value="Nacional">Nacional</option>
                  </select>
                </div>
              </div>

              <div  style="display:none" id="lnacional">
                <label>Nacional </label>
                <div class="form-group">
                  <select class="form-control" id="clnacional" name="lnacional" >
                    <option selected disabled>Seleccionar</option>
                    <option value="En la plaza">En la plaza</option>
                    <option value="En otros estados de la república">En otros estados de la república</option>
                    <option value="En zona fronteriza">En zona fronteriza</option>
                  </select>
                </div>
              </div>

              <div  style="display:none" id="linternacional">
                <label>Internacional </label>
                <div class="form-group">
                  <select class="form-control" id="clinternacional" name="linternacional" >
                    <option selected disabled>Seleccionar</option>
                    <option value="Países no cooperantes">Países no cooperantes</option>
                    <option value="Paraísos fiscales">Paraísos fiscales</option>
                    <option value="Otros">Otros</option>
                  </select>
                </div>
              </div>

              <label>Origen </label>
              <div class="form-group">
                <select class="form-control" id="origen" name="origen" onchange="corigen()">
                  <option selected disabled>Seleccionar</option>
                  <option value="Cuentas propias">Cuentas propias</option>
                  <option value="En el caso de créditos de nómina, del empleador">En el caso de créditos de nómina, del empleador</option>
                  <option value="Cuentas de terceros">Cuentas de terceros</option>
                  <option value="No identificado">No identificado</option>
                </select>
              </div>

              <div style="display:none" id="cterceros">
                <label>Internacional </label>
                <div class="form-group">
                  <select class="form-control" id="ccterceros" name="cterceros" onchange="cccterceros()">
                    <option selected disabled>Seleccionar</option>
                    <option value="Relacionados en listas negras">Relacionados en listas negras</option>
                    <option value="Otros">Otros</option>
                  </select>
                </div>
              </div>

              <div style="display:none" id="coterceros">
                <label>Internacional </label>
                <div class="form-group">
                  <select class="form-control" id="ccterceros" name="cterceros" >
                    <option selected disabled>Seleccionar</option>
                    <option value="Identificados">Identificados</option>
                    <option value="No identificados">No identificados</option>
                  </select>
                </div>
              </div>


              <label>Comprobante </label>
              <div class="form-group">
                <input type="file"
                       placeholder=".jpg, .jpeg, .png"
                       class="form-control required" id="comprobante" name="comprobante" accept=".jpg, .jpeg, .png">
              </div>

              <label>Fecha de Pago </label>
              <div class="form-group">
                <input type='text' class="form-control pickadate-disable required" id="fecha" value="{{date('Y-m-d')}}"  name="fecha" required />
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

    });



    function edit(id){
    }
  </script>
@endsection
