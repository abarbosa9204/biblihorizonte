$(document).ready(function () {
    $('#form-edit-book').on('change keyup kedown blur', function () {
        validateFormEditBook();
    });

    $('#edit-book-program').select2({
        dropdownParent: $('#form-edit-book'),
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

    $('#edit-book-subject').select2({
        dropdownParent: $('#form-edit-book'),
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
    
    $('#edit-book-category').select2({
        dropdownParent: $('#form-edit-book'),
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

    $('#edit-book-language').select2({
        dropdownParent: $('#form-edit-book'),
        tags: true,
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

    $('#edit-book-upload-file').on('change', function () {
        let ext = $(this).val().split('.').pop();
        if ($(this).val() != '') {
            let extensions = ["png, jpg, jpeg"];
            if (extensions.indexOf(ext.toLowerCase())) {
                $('#edit-book-upload-file-description').html('<strong>Archivo a cargar: </strong>' + $(this).val().split('\\').pop());
                $('#edit-book-upload-file-error').html('');
            } else {
                $('#edit-book-upload-file-error').html('La extención del archivo ' + $(this).val().split('\\').pop() + ' no es permida. solo se permite cargar archivos png, jpg, jpeg');
                $(this).val('');
                $('#edit-book-upload-fileDescription').html('');
                Command: toastr['warning']('La extención del archivo (' + ext + ') no es permida. solo se permite cargar archivos png, jpg, jpeg');
            }
        }
    });
});
/**@augments 
 * Reiniciar formulario 
 */
function showFormEditBook(idBook) {
    var editBook = bootbox.dialog({
        title: '<h6 class="text-primary">Se está procesando la solicitud.</h6>',
        message: '<p><i class="fas fa-spin fa-spinner"></i> Cargando...</p>',
        //centerVertical: true,
        closeButton: true
    });
    editBook.init(function () {
        $.ajax({
            url: "GetBookById",
            type: "post",
            dataType: "json",
            data: {
                id: idBook
            },
            success: function (data) {
                if (data['Status'] == '200') {
                    setTimeout(function () {
                        $('#edit-booksModal').modal('show')
                        resetFormEditBook('reset')
                        $('#id-book-edit').val(data['data']['RowIdHash']);
                        $('#edit-book-title').val(data['data']['Titulo']);
                        $('#edit-book-author').val(data['data']['Autor']);
                        $('#edit-book-publisher').val(data['data']['Editorial']);
                        $('#edit-book-description').val(data['data']['Descripcion']);
                        $('#edit-book-content').val(data['data']['Contenido']);
                        $('#edit-book-first-edition').val(data['data']['PrimeraEdicion']);
                        $('#edit-book-first-mention').val(data['data']['MencionPrimera']);
                        $('#edit-book-series').val(data['data']['Serie']);
                        $('#edit-book-program').select2('val', [data['data']['programa']]);
                        $('#edit-book-subject').select2('val', [data['data']['materia']]);
                        $('#edit-book-category').select2('val', [data['data']['categoria']]);
                        $('#edit-book-language').select2('val', [data['data']['idioma']]);
                        $('#edit-book-status').val(data['data']['Estado']);
                        editBook.modal('hide');
                    }, 500);
                } else {
                    setTimeout(function () {
                        Command: toastr['error'](data['Message']);
                        editBook.modal('hide');
                    }, 500);
                }
            },
            error: function (data) {
                setTimeout(function () {
                    editBook.modal('hide');
                    Command: toastr['error']('No es posible procesar la solicitud, por favor comunicarse con el administrador');
                }, 500);
            }
        });
    });
}

function resetFormEditBook(action) {
    switch (action) {
        case 'cancel':
            var cancel = bootbox.dialog({
                title: '<span class="text-primary" style="font-size:18px;">¿Cancelar?</span>',
                message: "<p><h6>ESTÁ SEGURO DE CANCELAR LA EDICIÓN DEL LIBRO.</h6></p>",
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
                            $('#edit-booksModal').modal('hide').data('bs.modal', null);
                            Command: toastr['error']('Proceso cancelado');
                        }
                    }
                }
            });
            break;
        case 'reset':
            $('#edit-book-title, #edit-book-author, #edit-book-publisher, #edit-book-description, #edit-book-content, #edit-book-first-edition, #edit-book-first-mention, #edit-book-series, #edit-book-upload-file, #id-book-edit').val('');
            $('#edit-book-upload-file-error, #edit-book-upload-file-description').html('');
            $("#edit-book-program").val(null).trigger('change');
            $("#edit-book-subject").val(null).trigger('change');
            $("#edit-book-category").val(null).trigger('change');
            $("#edit-book-language").val(null).trigger('change');
            break;
    }
}

/**@augments 
 * submit del formulario
 */
function submitFormEditBook() {
    if (validateFormEditBook()) {
        var editBook = bootbox.dialog({
            title: '<h6 class="text-primary">Se está procesando la solicitud.</h6>',
            message: '<p><i class="fas fa-spin fa-spinner"></i> Creando...</p>',
            //centerVertical: true,
            closeButton: true
        });
        editBook.init(function () {
            let formData = new FormData(document.getElementById("form-edit-book"));
            formData.append("formData", $('#form-edit-book').serializeArray());
            $.ajax({
                url: "EditBook",
                type: "post",
                dataType: "json",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    //console.log(data);return false;
                    if (data['Status'] == '200') {
                        setTimeout(function () {
                            editBook.find('.modal-title').html('');
                            editBook.find('.bootbox-body').html('El libro se ha creado satisfactoriamente');
                            $('#edit-booksModal').modal('hide').data('bs.modal', null);
                            $("#list-books").DataTable().ajax.reload(null, false);
                            Command: toastr['success'](data['Message']);
                            editBook.modal('hide');
                        }, 1000);
                    } else {
                        setTimeout(function () {
                            editBook.modal('hide');
                            Command: toastr['error'](data['Message']);
                        }, 500);
                    }
                },
                error: function (data) {
                    setTimeout(function () {
                        editBook.modal('hide');
                        Command: toastr['error']('No es posible procesar la solicitud, por favor comunicarse con el administrador');
                    }, 500);
                }
            });
        });
    } else {
        Command: toastr['warning']('Por favor completar los campos obligatorios');
    }
}

function validateFormEditBook() {
    let campos = $('#form-edit-book').serializeArray();
    let validar = 0;
    /*if ($('#edit-book-upload-file').val()) {
        $("#edit-book-upload-file").parent().removeClass('v-form-error').addClass('v-form-success');
    } else {
        $("#edit-book-upload-file").parent().removeClass('v-form-success').addClass('v-form-error');
    }*/
    $.each(campos, function (index, value) {
        if ($('#edit-book-program') && $('#edit-book-program').val().length != 0) {
            $('#edit-book-program').siblings('span').find('.select2-selection--multiple').removeClass('v-form-error').addClass('v-form-success');
        } else {
            validar++;
            $('#edit-book-program').siblings('span').find('.select2-selection--multiple').removeClass('v-form-success').addClass('v-form-error');
        }
        if ($('#edit-book-subject') && $('#edit-book-subject').val().length != 0) {
            $('#edit-book-subject').siblings('span').find('.select2-selection--multiple').removeClass('v-form-error').addClass('v-form-success');
        } else {
            validar++;
            $('#edit-book-subject').siblings('span').find('.select2-selection--multiple').removeClass('v-form-success').addClass('v-form-error');
        }
        if ($('#edit-book-category') && $('#edit-book-category').val().length != 0) {
            $('#edit-book-category').siblings('span').find('.select2-selection--multiple').removeClass('v-form-error').addClass('v-form-success');
        } else {
            validar++;
            $('#edit-book-category').siblings('span').find('.select2-selection--multiple').removeClass('v-form-success').addClass('v-form-error');
        }
        if ($('#edit-book-language') && $('#edit-book-language').val().length != 0) {
            $('#edit-book-language').siblings('span').find('.select2-selection--multiple').removeClass('v-form-error').addClass('v-form-success');
        } else {
            validar++;
            $('#edit-book-language').siblings('span').find('.select2-selection--multiple').removeClass('v-form-success').addClass('v-form-error');
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