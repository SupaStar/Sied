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
  users();
  // Scrollbar
  if ($(".data-items").length > 0) {
    new PerfectScrollbar(".data-items", { wheelPropagation: false })
  }
  // mac chrome checkbox fix
  if (navigator.userAgent.indexOf("Mac OS X") != -1) {
    $(".dt-checkboxes-cell input, .dt-checkboxes").addClass("mac-checkbox")
  }
});

function users(chn){
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
        text: "Nuevo Usuario",
        action: function() {
          window.location.href = "/usuarios/nuevo";
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
        data: 'name',
        name: 'name'
    },
    {
        data: 'lastname',
        name: 'lastname'
    },
    {
        data: 'o_lastname',
        name: 'o_lastname'
    },
    {
        data: 'email',
        name: 'email'
    },
    {
      data: 'estado',
      name: 'estado'
    },
    {
      data: 'rol',
      name: 'role'
    },
    {
      data: 'actions',
      name: 'actions'
    },
],
    ajax: {
      url: "/usuarios/get",
      data: {
        "filtro": filtro
      }
    }

  });

  actionDropdown.insertBefore($(".top .actions .dt-buttons"))

}

function del(id){
  Swal.fire({
    title: 'Atención',
    text: "¿Desactivar acceso a usuario?",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Desactivar!',
    confirmButtonClass: 'btn btn-primary',
    cancelButtonClass: 'btn btn-danger ml-1',
    cancelButtonText: 'No, cancelar',
    buttonsStyling: false,
  }).then(function (result) {
    if (result.value) {
      $.post('/usuarios/desactivar', {
        id: id,
        _token: token
    }, function(data) {
      if(data == 'OK'){
        users(filtro);
        Swal.fire(
          {
            type: "success",
            title: 'Desactivado!',
            text: 'Se desactivo el acceso al usuario.',
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

function act(id){
  Swal.fire({
    title: 'Atención',
    text: "Activar acceso a usuario?",
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
      $.post('/usuarios/activar', {
        id: id,
        _token: token
    }, function(data) {
      if(data == 'OK'){
        users(filtro);
        Swal.fire(
          {
            type: "success",
            title: 'Activado!',
            text: 'Se activo el acceso al usuario.',
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


function resend(id){
  Swal.fire({
    title: 'Activación',
    text: "Reenviar e-mail de activacion?",
    type: 'info',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Enviar!',
    confirmButtonClass: 'btn btn-primary',
    cancelButtonClass: 'btn btn-danger ml-1',
    cancelButtonText: 'No, cancelar',
    buttonsStyling: false,
  }).then(function (result) {
    if (result.value) {
      $.post('/usuarios/reactivar', {
        id: id,
        _token: token
    }, function(data) {
      if(data == 'OK'){
        users(filtro);
        Swal.fire(
          {
            type: "success",
            title: 'Activado!',
            text: 'Se reenvio email de activación.',
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
