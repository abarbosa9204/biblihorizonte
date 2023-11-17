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
      { data: "Gestion" },
    ],
    columnDefs: [
      {
        targets: [0, 3, 4, 5, 6, 7],
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
    initComplete: function () {
      var data = table.ajax.json();
      if (!data.columnManage) {
        table.column(7).nodes().to$().addClass("d-none");
      }
    },
  });
}

function manageBook(idBook, method) {
  let modalMessage = "";
  let modalLabel = "";
  let modalTitle = "";
  switch (method) {
    case "deliver":
      modalMessage = "¿Está seguro de realizar la entrega del libro?";
      modalLabel = "Cancelar";
      modalTitle = "Confirmación de Entrega";
      break;
    case "cancel":
      modalMessage = "¿Está seguro de realizar la cancelación de la reserva?";
      modalLabel = "Cancelar";
      modalTitle = "Confirmación de Cancelación";
      break;
    case "receive":
      modalMessage = "¿Está seguro de realizar la recibir libro?";
      modalLabel = "Cancelar";
      modalTitle = "Confirmación Recibir Libro";
      break;
  }

  bootbox.dialog({
    message: modalMessage,
    title: modalTitle,
    buttons: {
      cancel: {
        label: modalLabel,
        className: "btn-default",
        callback: function () {
          // Cerrar el cuadro de diálogo
        },
      },
      confirm: {
        label: "Confirmar",
        className: "btn-primary",
        callback: function () {
          processManageBook(idBook, method);
        },
      },
    },
  });
}

function processManageBook(idBook, method) {
  var viewBook = bootbox.dialog({
    title: '<h6 class="text-primary">Se está procesando la solicitud.</h6>',
    message: '<p><i class="fas fa-spin fa-spinner"></i> Cargando...</p>',
    closeButton: true,
  });
  viewBook.init(function () {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "ProcessManageBook",
      data: {
        id: idBook,
        method: method,
      },
      success: function (response) {
        if (response.Status == 200) {
          setTimeout(function () {
            $("#list-books").DataTable().ajax.reload(null, false);
            toastr["success"](response.Message);
            viewBook.modal("hide");
          }, 500);
        } else {
          setTimeout(function () {
            toastr["warning"](response.Message);
            viewBook.modal("hide");
          }, 500);
        }
      },
      error: function () {
        setTimeout(function () {
          viewBook.modal("hide");
          toastr["error"](
            "Error procesando la solicitud, por favor contactar al administrador."
          );
        }, 500);
      },
    });
  });
}
