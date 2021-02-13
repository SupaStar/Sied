var actionDropdown = $(".actions-dropodown")
var filtro = '';
$(document).ready(function() {
  "use strict"
  // init list view datatable
  alertas('');
  // Scrollbar
  if ($(".data-items").length > 0) {
    new PerfectScrollbar(".data-items", { wheelPropagation: false })
  }
  // mac chrome checkbox fix
  if (navigator.userAgent.indexOf("Mac OS X") != -1) {
    $(".dt-checkboxes-cell input, .dt-checkboxes").addClass("mac-checkbox")
  }
});
function alertas(chn){
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
        "targets": [ 0 ],
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
      {"render":
          function ( data,type,row ) {
            return row.usuario.name+" "+row.usuario.lastname+" "+ row.usuario.o_lastname;
            // donde, en teoría:
            // row[3] es 'primer_nombre'
            // row[4] es 'segundo_nombre'
            // row[5] es 'apellido_paterno'
            // row[6] es 'apellido_materno'
          }
      },
      {
        data: 'titulo',
        name: 'titulo'
      },
      {
        data: 'descripcion',
        name: 'descripcion'
      },
      {
        data: 'usuario.status',
        name: 'usuario.status'
      },
      {
        data: 'prioridad',
        name: 'prioridad'
      }
    ],

    ajax: {
      url: "/buzon/getbuzon",
      data: {
        "filtro": filtro
      }
    }

  });

  actionDropdown.insertBefore($(".top .actions .dt-buttons"))

}