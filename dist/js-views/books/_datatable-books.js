$(document).ready(function () {
  initTable();
  $("#search-book-status")
    .select2({
      placeholder: "Estado",
      width: "100%",
      border: "1px solid #e4e5e7",
      allowClear: true,
      language: {
        noResults: function () {
          return "No hay resultados para la busqueda";
        },
        searching: function () {
          return "Buscando..";
        },
        inputTooShort: function () {
          return "Ingrese mínimo 1 caracter";
        },
      },
    })
    .on("select2:select", function (e) {
      let data = e.params.data;
      initTable(data.id);
      $("#list-books").DataTable().ajax.reload(null, false);
    })
    .on("select2:unselecting", function (e) {
      initTable();
      $("#list-books").DataTable().ajax.reload(null, false);
    });
});

function initTable(search = null) {
  /**@augments
   * Datatable paginación ajax
   */
  var table = $("#list-books").DataTable({
    destroy: true,
    processing: true,
    serverSide: true,
    serverMethod: "post",
    pageLength: 10,
    lengthMenu: [10, 25, 50, 75, 100],
    sort: true,
    ajax: {
      url: "GetListBooks",
      data: {
        searchStatus: search, // Agrega aquí tu variable personalizada
      },
    },
    columns: [
      { data: "Libro" },
      { data: "Titulo" },
      { data: "Autor" },
      { data: "Programa" },
      { data: "Materia" },
      { data: "EstadoNombre" },
      { data: "Acciones" },
    ],
    columnDefs: [
      {
        targets: [0, 3, 4, 5, 6],
        sortable: false,
      },
      //{targets: [1,2],className:'text-center' },
    ],
    language: {
      emptyTable: "No hay datos para mostrar",
      zeroRecords: "No se encontraron resultados",
      thousands: ",",
      processing: "Procesando...",
      loadingRecords: "Cargando...",
      info: "Mostrando de _START_ a _END_ de un total de _TOTAL_",
      infoEmpty: " 0 registros",
      infoFiltered: "(filtrado de _MAX_ registros)",
      infoPostFix: "",
      lengthMenu: "Registros _MENU_",
      search: "Buscar:",
      paginate: {
        first: "Primero",
        last: "Último",
        next: "Siguiente",
        previous: "Anterior",
      },
      aria: {
        sortAscending: " Activar para ordenar la columna de manera ascendente",
        sortDescending:
          " Activar para ordenar la columna de manera descendente",
      },
    },
    scrollX: true,
    scrollCollapse: true,
    paging: true,
  });
}
