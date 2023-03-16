function showText(idName, idBook) {
    console.log(idName, idBook);
    var showTextBook = bootbox.dialog({
        title: '<h6 class="text-primary">Se está procesando la solicitud.</h6>',
        message: '<p><i class="fas fa-spin fa-spinner"></i> Cargando...</p>',
        closeButton: false
    });
    showTextBook.init(function () {
        $.ajax({
            url: "GetTextBook",
            type: "post",
            dataType: "json",
            data: {
                name: idName,
                id: idBook
            },
            success: function (data) {
                if (data['Status'] == '200') {
                    setTimeout(function () {
                        $('#show-text-bookModal').modal('show');
                        closeModalText();
                        $('.text-description-book').html(data['data']['text']);
                        showTextBook.modal('hide');
                    }, 500);
                } else {
                    setTimeout(function () {
                        Command: toastr['error'](data['Message']);
                        showTextBook.modal('hide');
                    }, 500);
                }
            },
            error: function (data) {
                setTimeout(function () {
                    //showTextBook.modal('hide');
                    Command: toastr['error']('No es posible procesar la solicitud, por favor comunicarse con el administrador');
                }, 500);
            }
        });
    });
}
function closeModalText() {
    $('#show-text-bookModal').modal('hide').data('bs.modal', null);
    $('.text-description-book').html('');
}