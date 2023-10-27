/**@augments
 * Reiniciar formulario
 */
function showFormViewBook(idBook) {
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
            $(".html-content-view").html(data.html);
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
