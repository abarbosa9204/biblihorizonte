/**@augments
 * Reiniciar formulario
 */
function showFormViewBook(idBook, process) {
  $(".btn-id-book-reserve").html("");
  var viewBook = bootbox.dialog({
    title: '<h6 class="text-primary">Se está procesando la solicitud.</h6>',
    message: '<p><i class="fas fa-spin fa-spinner"></i> Cargando...</p>',
    //centerVertical: true,
    closeButton: true,
  });
  viewBook.init(function () {
    $.ajax({
      url: "GetViewBookById",
      type: "post",
      dataType: "json",
      data: {
        id: idBook,
      },
      success: function (data) {
        if (data.Status == 200) {
          setTimeout(function () {
            $("#view-booksModal").modal("show");
            $(".html-content-view").html("");
            $("#id-book-reserve").val(idBook);
            $(".html-content-view").html(data.html);
            if (data.rsv.Status == 200 && process == "reserv") {
              $(".btn-id-book-reserve").html(data.rsv.btn);
            }
            Command: toastr["success"](data["Message"]);
            viewBook.modal("hide");
          }, 500);
        } else {
          setTimeout(function () {
            Command: toastr["error"](data["Message"]);
            viewBook.modal("hide");
          }, 500);
        }
      },
      error: function (data) {
        setTimeout(function () {
          viewBook.modal("hide");
          Command: toastr["error"](
            "No es posible procesar la solicitud, por favor comunicarse con el administrador"
          );
        }, 500);
      },
    });
  });
}

function resetFormViewBook(action) {
  switch (action) {
    case "cancel":
      $("#view-booksModal").modal("hide").data("bs.modal", null);
      $(".html-content-view").html("");
      break;
    case "reset":
      $("#view-booksModal").modal("hide").data("bs.modal", null);
      $(".html-content-view").html("");
      break;
  }
}

function reserveBookModal(rowId) {
  $.ajax({
    url: "../Admin/GetUserAuth",
    type: "post",
    dataType: "json",
    data: {},
    success: function (response) {
      if (response.Status == 200) {
        $("#view-booksModal").modal("hide").data("bs.modal", null);
        setTimeout(function () {
          $("#reserv-book-modal").modal("show");
          $("#row-id-book-reserv").val(rowId);
          $("#reserv-name").val(
            response.data.Nombre + " " + response.data.Apellido
          );
          $("#reserv-dni").val(response.data.Cedula);
          $("#reserv-email").val(response.data.Correo);
          $("#reserv-phone").val(response.data.Telefono);
          Command: toastr["success"](response["Message"]);
        }, 500);
      } else {
        setTimeout(function () {
          $("#row-id-book-reserv").val("");
          Command: toastr["error"](response["Message"]);
        }, 500);
      }
    },
    error: function (data) {
      setTimeout(function () {
        Command: toastr["error"](
          "No es posible procesar la solicitud, por favor comunicarse con el administrador"
        );
      }, 500);
    },
  });
}
