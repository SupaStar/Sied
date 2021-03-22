/*=========================================================================================
    File Name: data-list-view.js
    Description: List View
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/
    // To append actions dropdown before add new button
var actionDropdown = $(".actions-dropodown")
var filtro = '';
$(document).ready(function() {
  "use strict"
  // init list view datatable
  clients('');
  pendientes();
  // Scrollbar
  if ($(".data-items").length > 0) {
    new PerfectScrollbar(".data-items", { wheelPropagation: false })
  }
  // mac chrome checkbox fix
  if (navigator.userAgent.indexOf("Mac OS X") != -1) {
    $(".dt-checkboxes-cell input, .dt-checkboxes").addClass("mac-checkbox")
  }
});

function clients(chn){
  filtro = chn;
  var dataListView = $(".data-list-view").DataTable({
    responsive: false,
    columnDefs: [
      {
        orderable: true,
      //  targets: 0,
      //  checkboxes: { selectRow: true }
      },
    ],
    dom:
    '<"top"<"actions action-btns"B><"action-filters"lf>><"clear">rt<"bottom"<"actions">p>',
    buttons: [
      {
        text: "Nuevo Cliente",
        action: function() {
          window.location.href = "/clientes/nuevo/fisica";
        },
        className: "btn bg-gradient-primary waves-effect waves-light"
      }
    ],
    aLengthMenu: [[4, 10, 15, 20], [4, 10, 15, 20]],
    order: [[1, "asc"]],
    bInfo: false,
    destroy: true,
    processing: true,
    serverSide: true,
    pageLength: 10,
    columnDefs: [
      {
        "targets": [ 0 ],
        "visible": false,
        "searchable": false
      },{
      "targets": [ 2 ],
      "visible": false,
      "searchable": false
      },
    ],
    oLanguage: {
      "sProcessing":     "Procesando...",
      "sLengthMenu":     "_MENU_",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix":    "",
      "sSearch":         "",
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
    initComplete: function(settings, json) {
      $(".dt-buttons .btn").removeClass("btn-secondary")
    },
    columns: [
      {
          data: 'id',
          name: 'id'
      },
      {
          data: 'names',
          name: 'names'
      },
      {
          data: 'gender',
          name: 'gender'
      },
      {
        data: 'date_birth',
        name: 'date_birth'
      },
      {
        data: 'place_birth',
        name: 'place_birth'
      },
      {
        data: 'blacklist',
        name: 'blacklist'
      },
      {
        data: 'grupo',
        name: 'grupo'
      },
      {
        data: 'status',
        name: 'status'
      },
      {
        data: 'actions',
        name: 'actions'
      }
    ],
    ajax: {
      url: "/clientes/get",
      data: {
        "filtro": filtro
      }
    }

  });

  actionDropdown.insertBefore($(".top .actions .dt-buttons"))

}

function pendientes(){
  var dataListView = $(".data-list-view2").DataTable({
    responsive: true,
    columnDefs: [
      {
        orderable: true,
      //  targets: 0,
      //  checkboxes: { selectRow: true }
      },
    ],
    aLengthMenu: [[4, 10, 15, 20], [4, 10, 15, 20]],
    order: [[1, "asc"]],
    bInfo: false,
    destroy: true,
    processing: true,
    serverSide: true,
    pageLength: 10,
    oLanguage: {
      "sProcessing":     "Procesando...",
      "sLengthMenu":     "_MENU_",
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix":    "",
      "sSearch":         "",
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
    initComplete: function(settings, json) {
      $(".dt-buttons .btn").removeClass("btn-secondary")
    },
    columns: [
      {
          data: 'identificacion',
          name: 'identificacion'
      },
      {
          data: 'consulta_id',
          name: 'consulta_id'
      },
      {
          data: 'suma_id',
          name: 'suma_id'
      },
      {
        data: 'suma_estado',
        name: 'suma_estado'
      },
      {
        data: 'created_at',
        name: 'created_at'
      },
      {
        data: 'acciones',
        name: 'acciones'
      }      
    ],
    ajax: {
      url: "/clientes/pendientes"
    }

  });

  actionDropdown.insertBefore($(".top .actions .dt-buttons"))

}


function edit(id){
  alert('edit '+id);
}

function files(id){

  $.post('/clientes/fisicas/files/'+id, {
    id: id,
    _token: token
}, function(data) {
  $('#seemyfiles').html(data);

  console.log(data);
});

  $('#myfiles').modal('show');
}


function noblacklist(){
  Swal.fire({
    title: "Bien!",
    text: "El cliente no se entra en ninguna lista negra!",
    type: "success",
    confirmButtonClass: 'btn btn-primary',
    buttonsStyling: false,
    animation: false,
    customClass: 'animated tada'
  });
}

function del(id){
  Swal.fire({
    title: 'Atención',
    text: "Archivar registro de cliente?",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Archivar!',
    confirmButtonClass: 'btn btn-primary',
    cancelButtonClass: 'btn btn-danger ml-1',
    cancelButtonText: 'No, cancelar',
    buttonsStyling: false,
  }).then(function (result) {
    if (result.value) {
      $.post('/clientes/archivar', {
        id: id,
        _token: token
    }, function(data) {
      if(data == 'OK'){
        clients(filtro);
        pendientes();
        Swal.fire(
          {
            type: "success",
            title: 'Archivado!',
            text: 'Se archivo el registro de cliente.',
            confirmButtonClass: 'btn btn-success',
          }
        )
      }else{
        clients(filtro);
        Swal.fire(
          {
            type: "warnig",
            title: 'Error!',
            text: 'Error inesperado intenta nuevamente.',
            confirmButtonClass: 'btn btn-success',
          }
        )
      }
    });
    }
  })
}

function act(id){
  Swal.fire({
    title: 'Atención',
    text: "Activar registro de cliente?",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Activar!',
    confirmButtonClass: 'btn btn-primary',
    cancelButtonClass: 'btn btn-danger ml-1',
    cancelButtonText: 'No, cancelar',
    buttonsStyling: false,
  }).then(function (result) {
    if (result.value) {
      $.post('/clientes/activar', {
        id: id,
        _token: token
    }, function(data) {
      if(data == 'OK'){
        users(filtro);
        Swal.fire(
          {
            type: "success",
            title: 'Activado!',
            text: 'Se activo el registro del cliente.',
            confirmButtonClass: 'btn btn-success',
          }
        )
      }else{
        users(filtro);
        Swal.fire(
          {
            type: "warnig",
            title: 'Error!',
            text: 'Error inesperado intenta nuevamente.',
            confirmButtonClass: 'btn btn-success',
          }
        )
      }
    });
    }
  })
}


function descmodal(id){

  $.post('/clientes/data', {
    id: id,
    _token: token
}, function(data) {
  $('#nombre').html(data['nombre']);
  $('#genero').html(data['genero']);
  $('#fnac').html(data['fnac']);
  $('#pnac').html(data['pnac']);
  $('#lnac').html(data['lnac']);
  $('#ocupacion').html(data['ocupacion']);
  $('#direccion').html(data['direccion']);
  $('#telefonos').html(data['telefonos']);
  $('#email').html(data['email']);
  $('#curp').html(data['curp']);
  $('#rfc').html(data['rfc']);
  $('#cnombre').html(data['cnombre']);
  $('#ctelefono').html(data['ctelefono']);
  $('#cemail').html(data['cemail']);

  console.log(data);
});
/*











*/
  $('#DescModal').modal('show');
}
