@extends('layouts/contentLayoutMaster')

@section('title', $pageConfigs['pageName'] )

@section('page-style')
        <!-- Page css files -->
        <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/wizard.css')) }}">
        <link rel="stylesheet" href="{{ asset('datepicker/datepicker.css') }}">
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">

        <style>
          #WindowLoad
            {
                position:fixed;
                top:0px;
                left:0px;
                z-index:3200;
                filter:alpha(opacity=85);
               -moz-opacity:85;
                opacity:0.85;
                background:#ededed;
            }
          </style>
@endsection
@section('content')
<section id="multiple-column-form">
  <div class="row match-height">
    <div class="col-12">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
            <form @submit="checkForm" action="/grupos/store" method="POST" class="steps-validation wizard-circle" id="formss" name="formss">
              @csrf
              <div class="form-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lastName3">
                        Nombre del grupo
                      </label>
                      <input type="text" class="form-control required" name="nombre" id="nombre" required>
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
                            @foreach($clientes as $cliente)
                            <tr>
                                <td class="td-check">
                                    <button type="button"  class="btn btn-success"
                                        @click= "agregarIntegrante({'id':'{{$cliente->id}}', 'nombre':'{{$cliente->nombreCompleto}}', 'imagen':'{{ url('/uploads/fisicas/ine/'.$cliente->id.'-frontal.jpg') }}' })">
                                        Agregar
                                    </button>
                                </td>

                                <td>
                                {{ $cliente->nombreCompleto }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <h3>Integrantes</h3>
                    <div class="col-md-12">
                        <hr>
                      </div>
                    <div class="col-md-12 table-responsive">
                        <table id="clientesTable" class="table data-list-view">
                        <thead>
                            <tr>
                            <th>Nombre</th>
                            <th>Responsable</th>
                            <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(cliente, index) in integrantes">
                                <td>@{{cliente.nombre}}</td>
                                <td>
                                    <input type="radio" name="responsable_id" :value="cliente.id" required>
                                    <input type="hidden" name="clientes[]" :value="cliente.id">
                                </td>
                                <td>
                                    <button type="button"  class="btn btn-danger" @click="eliminarIntegrante(index)">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <button type="reset" class="btn btn-secondary mr-1 mb-1">Limpiar</button>
                  </div>
                  <div class="col-md-6 text-left">
                    <button type="submit" class="btn btn-primary float-right mr-1 mb-1">Guardar</button>
                    <a href="/clientes/fisica"> <button type="button"
                        class="btn btn-danger float-right mr-1 mb-1">Cancelar</button></a>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('vendor-script')
<!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/extensions/jquery.steps.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.select.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
    <script src="/js/scripts/vue.min.js"></script>

@endsection
@section('page-script')
<script>
    var app = new Vue({
        el: '#multiple-column-form',
        data: {
            message: 'Hello Vue!',
            integrantes: [],
            tabla: {}
        },
        mounted(){
            var dataListView = $("#clientesTable").DataTable({
            responsive: true,
            select: true,
            columnDefs: [
            {
                orderable: true,
            //  targets: 0,
            //  checkboxes: { selectRow: true }
            }],
            oLanguage: {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "_MENU_",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad"
                }
            },
        });
        },
        methods:{
            agregarIntegrante(integrante){
                var flag=false;
                this.integrantes.forEach(cliente => {
                    if(integrante.id==cliente.id){
                        flag=true;
                    }
                });
                if(!flag){
                    this.integrantes.push(integrante);
                }

            },
            eliminarIntegrante(index){
                this.integrantes.splice(index,1)
            },
            checkForm(e){
                if(this.integrantes.length < 2){
                    Swal.fire({
                                title: "¡Error!",
                                text: "El grupo debe tener al menos 2 integrantes",
                                type: "error",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                                animation: false,
                                customClass: 'animated tada'
                              });
                }else{
                    return true;
                }

                e.preventDefault();
            }
        }
    })
</script>
@endsection
