$(document).ready(function () {
    $('#form-create-book').on('change keyup kedown blur', function () {
        validateFormCreateBook();
    });

    $('#create-book-program').select2({
        dropdownParent: $('#form-create-book'),
        language: {
            noResults: function () {
                return "No hay resultados para la busqueda";
            },
            searching: function () {
                return "Buscando..";
            },
            inputTooShort: function () {
                return 'Ingrese mínimo 1 caracter';
            }
        },
    }).on('change', function (e) {
        $(this).siblings('.edit-number-items').html('Ítems seleccionados: ' + $(this).val().length);
    }).on('select2:select', function (e) {
        $(this).siblings('.edit-number-items').html('Ítems seleccionados: ' + $(this).val().length);
    }).on("select2:unselecting", function (e) {
        $(this).siblings('.edit-number-items').html('Ítems seleccionados: ' + $(this).val().length);
    });

    $('#create-book-subject').select2({
        dropdownParent: $('#form-create-book'),
        language: {
            noResults: function () {
                return "No hay resultados para la busqueda";
            },
            searching: function () {
                return "Buscando..";
            },
            inputTooShort: function () {
                return 'Ingrese mínimo 1 caracter';
            }
        },
    }).on('change', function (e) {
        $(this).siblings('.edit-number-items').html('Ítems seleccionados: ' + $(this).val().length);
    }).on('select2:select', function (e) {
        $(this).siblings('.edit-number-items').html('Ítems seleccionados: ' + $(this).val().length);
    }).on("select2:unselecting", function (e) {
        $(this).siblings('.edit-number-items').html('Ítems seleccionados: ' + $(this).val().length);
    });

    $('#create-book-category').select2({
        dropdownParent: $('#form-create-book'),
        language: {
            noResults: function () {
                return "No hay resultados para la busqueda";
            },
            searching: function () {
                return "Buscando..";
            },
            inputTooShort: function () {
                return 'Ingrese mínimo 1 caracter';
            }
        },
    }).on('change', function (e) {
        $(this).siblings('.edit-number-items').html('Ítems seleccionados: ' + $(this).val().length);
    }).on('select2:select', function (e) {
        $(this).siblings('.edit-number-items').html('Ítems seleccionados: ' + $(this).val().length);
    }).on("select2:unselecting", function (e) {
        $(this).siblings('.edit-number-items').html('Ítems seleccionados: ' + $(this).val().length);
    });
    
    $('#create-book-language').select2({
        dropdownParent: $('#form-create-book'),
        language: {
            noResults: function () {
                return "No hay resultados para la busqueda";
            },
            searching: function () {
                return "Buscando..";
            },
            inputTooShort: function () {
                return 'Ingrese mínimo 1 caracter';
            }
        },
    }).on('change', function (e) {
        $(this).siblings('.edit-number-items').html('Ítems seleccionados: ' + $(this).val().length);
    }).on('select2:select', function (e) {
        $(this).siblings('.edit-number-items').html('Ítems seleccionados: ' + $(this).val().length);
    }).on("select2:unselecting", function (e) {
        $(this).siblings('.edit-number-items').html('Ítems seleccionados: ' + $(this).val().length);
    });

    $('#create-book-upload-file').on('change', function () {
        let ext = $(this).val().split('.').pop();
        if ($(this).val() != '') {
            let extensions = ["png, jpg, jpeg"];
            if (extensions.indexOf(ext.toLowerCase())) {
                $('#create-book-upload-file-description').html('<strong>Archivo a cargar: </strong>' + $(this).val().split('\\').pop());
                $('#create-book-upload-file-error').html('');
            } else {
                $('#create-book-upload-file-error').html('La extención del archivo ' + $(this).val().split('\\').pop() + ' no es permida. solo se permite cargar archivos png, jpg, jpeg');
                $(this).val('');
                $('#create-book-upload-fileDescription').html('');
                Command: toastr['warning']('La extención del archivo (' + ext + ') no es permida. solo se permite cargar archivos png, jpg, jpeg');
            }
        }
    });
});
/**@augments 
 * Reiniciar formulario 
 */
function showFormCreateBook() {
    $('#booksModal').modal('show')
    resetFormCreateBook('reset')
}

function resetFormCreateBook(action) {
    switch (action) {
        case 'cancel':
            var cancel = bootbox.dialog({
                title: '<span class="text-primary" style="font-size:18px;">¿Cancelar?</span>',
                message: "<p><h6>ESTÁ SEGURO DE CANCELAR LA CREACIÓN DEL LIBRO.</h6></p>",
                closeButton: false,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancelar',
                        className: 'btn-secondary btn-xs',
                        callback: function () {
                        }
                    },
                    ok: {
                        label: '<i class="fa fa-check"></i> Confirmar',
                        className: 'btn-primary btn-xs',
                        callback: function () {
                            $('#booksModal').modal('hide').data('bs.modal', null);
                            Command: toastr['error']('Proceso cancelado');
                        }
                    }
                }
            });
            break;
        case 'reset':
            $('#create-book-title, #create-book-author, #create-book-publisher, #create-book-description, #create-book-content, #create-book-first-edition, #create-book-first-mention, #create-book-series, #create-book-upload-file').val('');
            $('#create-book-upload-file-error, #create-book-upload-file-description').html('');
            $("#create-book-program").val(null).trigger('change');
            $("#create-book-subject").val(null).trigger('change');
            $("#create-book-category").val(null).trigger('change');
            $("#create-book-language").val(null).trigger('change');
            break;
    }
}

/**@augments 
 * submit del formulario
 */
function submitFormCreateBook() {
    if (validateFormCreateBook()) {
        var createBook = bootbox.dialog({
            title: '<h6 class="text-primary">Se está procesando la solicitud.</h6>',
            message: '<p><i class="fas fa-spin fa-spinner"></i> Creando...</p>',
            //centerVertical: true,
            closeButton: true
        });
        createBook.init(function () {
            let formData = new FormData(document.getElementById("form-create-book"));
            formData.append("formData", $('#form-create-book').serializeArray());
            $.ajax({
                url: "CreateBook",
                type: "post",
                dataType: "json",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data['Status'] == '200') {
                        setTimeout(function () {
                            createBook.find('.modal-title').html('');
                            createBook.find('.bootbox-body').html('El libro se ha creado satisfactoriamente');
                            $('#booksModal').modal('hide').data('bs.modal', null);
                            $("#list-books").DataTable().ajax.reload(null, false);
                            Command: toastr['success'](data['Message']);
                            createBook.modal('hide');
                        }, 1000);
                    } else {
                        setTimeout(function () {
                            createBook.modal('hide');
                            Command: toastr['error'](data['Message']);
                        }, 500);
                    }
                },
                error: function (data) {
                    setTimeout(function () {
                        createBook.modal('hide');
                        Command: toastr['error']('No es posible procesar la solicitud, por favor comunicarse con el administrador');
                    }, 500);
                }
            });
        });
    } else {
        Command: toastr['warning']('Por favor completar los campos obligatorios');
    }
}

function validateFormCreateBook() {
    let campos = $('#form-create-book').serializeArray();
    let validar = 0;
    if ($('#create-book-upload-file').val()) {
        $("#create-book-upload-file").parent().removeClass('v-form-error').addClass('v-form-success');
    } else {
        $("#create-book-upload-file").parent().removeClass('v-form-success').addClass('v-form-error');
    }
    $.each(campos, function (index, value) {
        if ($('#create-book-program') && $('#create-book-program').val().length != 0) {
            $('#create-book-program').siblings('span').find('.select2-selection--multiple').removeClass('v-form-error').addClass('v-form-success');
        } else {
            validar++;
            $('#create-book-program').siblings('span').find('.select2-selection--multiple').removeClass('v-form-success').addClass('v-form-error');
        }
        if ($('#create-book-subject') && $('#create-book-subject').val().length != 0) {
            $('#create-book-subject').siblings('span').find('.select2-selection--multiple').removeClass('v-form-error').addClass('v-form-success');
        } else {
            validar++;
            $('#create-book-subject').siblings('span').find('.select2-selection--multiple').removeClass('v-form-success').addClass('v-form-error');
        }
        if ($('#create-book-category') && $('#create-book-category').val().length != 0) {
            $('#create-book-category').siblings('span').find('.select2-selection--multiple').removeClass('v-form-error').addClass('v-form-success');
        } else {
            validar++;
            $('#create-book-category').siblings('span').find('.select2-selection--multiple').removeClass('v-form-success').addClass('v-form-error');
        }
        if ($('#create-book-language') && $('#create-book-language').val().length != 0) {
            $('#create-book-language').siblings('span').find('.select2-selection--multiple').removeClass('v-form-error').addClass('v-form-success');
        } else {
            validar++;
            $('#create-book-language').siblings('span').find('.select2-selection--multiple').removeClass('v-form-success').addClass('v-form-error');
        }
        try {
            if (value.value == '') {
                $('#' + value.name).removeClass('v-form-success').addClass('v-form-error');
                validar = validar + 1;
            } else {
                $('#' + value.name).removeClass('v-form-error').addClass('v-success');
            }
        } catch (error) { }

    });
    if (validar > 0) {
        return false;
    } else {
        return true;
    }
}