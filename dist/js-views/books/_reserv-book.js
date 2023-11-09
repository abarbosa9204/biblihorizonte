$(document).ready(function () {
  // Obtén las referencias a los campos de entrada de fecha
  var dateInit = $("#reserv-date-init");
  var dateEnd = $("#reserv-date-end");

  // Establece el valor mínimo para la fecha inicial como el día actual
  dateInit.attr("min", getCurrentDate());

  // Deshabilita el campo de fecha final inicialmente
  dateEnd.prop("disabled", true);

  // Establece el evento de cambio en la fecha inicial para habilitar el campo de fecha final
  dateInit.on("change", function () {
    var selectedDate = $(this).val();
    var currentDate = getCurrentDate();
    var maxDate = new Date(selectedDate);
    var minDate = new Date(selectedDate);
    minDate.setDate(minDate.getDate() + 2);
    maxDate.setDate(maxDate.getDate() + 9); // Agrega 8 días como máximo    
    dateEnd.attr("min", formatDate(minDate));
    if (selectedDate < currentDate) {
      toastr["warning"](
        "La fecha de inicio no puede ser anterior a la fecha actual."
      );
      dateInit.val(""); // Limpia el campo de fecha inicial
    } else {
      dateEnd.prop("disabled", false);
      dateEnd.attr("min", formatDate(minDate)); // Establece la fecha mínima en el campo final como la fecha inicial
      dateEnd.attr("max", formatDate(maxDate)); // Establece la fecha máxima en el campo final
    }
  });

  // Establece el evento de cambio en la fecha final para asegurar que no exceda la fecha inicial
  dateEnd.on("change", function () {
    var startDate = new Date(dateInit.val());
    var endDate = new Date(dateEnd.val());
    var maxDate = new Date(startDate);
    maxDate.setDate(maxDate.getDate() + 9); // Agrega 8 días como máximo

    if (endDate < startDate || endDate > maxDate) {
      toastr["warning"](
        "La fecha de entrega no puede ser anterior a la fecha de inicio o exceder los 8 días a partir de la fecha de inicio."
      );
      dateEnd.val("");
    }
  });
});
// Función para obtener la fecha actual en formato YYYY-MM-DD
function getCurrentDate() {
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth() + 1; // Enero es 0
  var yyyy = today.getFullYear();

  if (dd < 10) {
    dd = "0" + dd;
  }
  if (mm < 10) {
    mm = "0" + mm;
  }

  return yyyy + "-" + mm + "-" + dd;
}
// Función para formatear una fecha como YYYY-MM-DD
function formatDate(date) {
  var dd = date.getDate();
  var mm = date.getMonth() + 1;
  var yyyy = date.getFullYear();

  if (dd < 10) {
    dd = "0" + dd;
  }
  if (mm < 10) {
    mm = "0" + mm;
  }

  return yyyy + "-" + mm + "-" + dd;
}

function resetFormReservBook(action) {
  switch (action) {
    case "cancel":
      let cancel = bootbox.dialog({
        title:
          '<span class="text-primary" style="font-size:18px;">¿Cancelar?</span>',
        message:
          "<p><h6>ESTÁ SEGURO DE CANCELAR LA CREACIÓN DEL LIBRO.</h6></p>",
        closeButton: false,
        buttons: {
          cancel: {
            label: '<i class="fa fa-times"></i> Cancelar',
            className: "btn-secondary btn-xs",
            callback: function () {},
          },
          ok: {
            label: '<i class="fa fa-check"></i> Confirmar',
            className: "btn-primary btn-xs",
            callback: function () {
              $("#reserv-book-modal").modal("hide").data("bs.modal", null);
              Command: toastr["error"]("Proceso cancelado");
            },
          },
        },
      });
      break;
    case "reset":
      $(
        "#reserv-name, #reserv-dni, #reserv-email, #reserv-phone, #reserv-date-init, #reserv-date-end"
      ).val("");
      $("#reserv-book-modal").modal("hide").data("bs.modal", null);
      break;
  }
}

function SaveFormReservBook() {
  // Obtén las referencias a los campos de entrada de fecha
  let dateInit = $("#reserv-date-init");
  let dateEnd = $("#reserv-date-end");

  // Valida que ambos campos de fecha estén llenos
  if (dateInit.val() === "" || dateEnd.val() === "") {
    Command: toastr["warning"]("Por favor, completa ambas fechas.");
    return;
  }

  // Valida que la fecha de inicio no sea menor que la fecha actual
  let selectedDate = new Date(dateInit.val());
  let currentDate = getCurrentDate();
  if (selectedDate < currentDate) {
    Command: toastr["warning"](
      "La fecha de inicio no puede ser anterior a la fecha actual."
    );
    return;
  }

  // Valida que la fecha de entrega no sea anterior a la fecha de inicio
  let startDate = new Date(dateInit.val());
  let endDate = new Date(dateEnd.val());
  let maxDate = new Date(startDate);
  maxDate.setDate(maxDate.getDate() + 8); // Agrega 8 días como máximo

  if (endDate < startDate || endDate > maxDate) {
    Command: toastr["warning"](
      "La fecha de entrega no es válida. Debe estar dentro de los 8 días a partir de la fecha de inicio."
    );
    return;
  }
  submitFormReserve();
  //   console.log($("#row-id-book-reserv").val());
  //   alert("Datos guardados con éxito.");
}

function submitFormReserve() {
  var viewBook = bootbox.dialog({
    title: '<h6 class="text-primary">Se está procesando la solicitud.</h6>',
    message: '<p><i class="fas fa-spin fa-spinner"></i> Cargando...</p>',
    closeButton: true,
  });

  viewBook.init(function () {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "GenerateReserve",
      data: $("#form-reserv-book").serializeArray(),
      success: function (response) {        
        if (response.Status == 200) {          
          setTimeout(function () {
            $("#list-books").DataTable().ajax.reload(null, false);
            toastr["success"](response.Message);
            viewBook.modal("hide");
            resetFormReservBook('reset');
            $("#reserv-book-modal").modal("hide");
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
            "Error al enviar el formulario. Por favor, inténtalo de nuevo."
          );
        }, 500);
      },
    });
  });
}
