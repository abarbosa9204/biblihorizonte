$(document).ready(function () {
    $('#form-edit-user').on('change keyup kedown blur', function () {
        validateFormEditUser();
    });
});
/**@augments 
 * Reiniciar formulario 
 */
function showFormEditUser(idUser) {
    var editUser = bootbox.dialog({
        title: '<h6 class="text-primary">Se está procesando la solicitud.</h6>',
        message: '<p><i class="fas fa-spin fa-spinner"></i> Cargando...</p>',
        //centerVertical: true,
        closeButton: false
    });
    editUser.init(function () {
        $.ajax({
            url: "GetUserById",
            type: "post",
            dataType: "json",
            data: {
                id: idUser
            },
            success: function (data) {                
                if (data['Status'] == '200') {
                    setTimeout(function () {
                        $('#edit-usersModal').modal('show')
                        resetFormEditUser('reset')
                        $('#id-user-edit').val(data['data']['RowIdHash']);
                        $('#edit-user-person-id').val(data['data']['Cedula']);
                        $('#edit-user-name').val(data['data']['Nombre']);
                        $('#edit-user-surname').val(data['data']['Apellido']);
                        $('#edit-user-email').val(data['data']['Correo']);
                        $('#edit-user-phone').val(data['data']['Telefono']);
                        $('#edit-user-program').val(data['data']['RowIdPrograma']);
                        $('#edit-user-role').val(data['data']['RowIdRole']);
                        $('#edit-user-status').val(data['data']['Estado']);
                        $('#edit-user-password').val('');
                        $('#edit-user-fcreacion').val(data['data']['FechaCreacion']);
                        editUser.modal('hide');
                    }, 500);                    
                } else {
                    setTimeout(function () {
                        Command: toastr['error'](data['Message']);
                        editUser.modal('hide');
                    }, 500);
                }
            },
            error: function (data) {
                setTimeout(function () {
                    editUser.modal('hide');
                    Command: toastr['error']('No es posible procesar la solicitud, por favor comunicarse con el administrador');
                }, 500);
            }
        });
    });
}

function resetFormEditUser(action) {
    switch (action) {
        case 'cancel':
            var cancel = bootbox.dialog({
                title: '<span class="text-primary" style="font-size:18px;">¿Cancelar?</span>',
                message: "<p><h6>ESTÁ SEGURO DE CANCELAR LA EDICIÓN DE USUARIO.</h6></p>",
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
                            $('#edit-usersModal').modal('hide').data('bs.modal', null);
                            Command: toastr['error']('Proceso cancelado');
                        }
                    }
                }
            });
            break;
        case 'reset':
            $('#edit-user-person-id, #edit-user-name, #edit-user-surname, #edit-user-email, #edit-user-phone, #edit-user-program, #edit-user-role, #edit-user-password, #id-user-edit').val('');
            break;
    }
}

/**@augments 
 * submit del formulario
 */
function submitFormEditUser() {
    if (validateFormEditUser()) {
        var editUser = bootbox.dialog({
            title: '<h6 class="text-primary">Se está procesando la solicitud.</h6>',
            message: '<p><i class="fas fa-spin fa-spinner"></i> Creando...</p>',
            //centerVertical: true,
            closeButton: false
        });
        editUser.init(function () {
            $.ajax({
                url: "EditUser",
                type: "post",
                dataType: "json",
                data: {
                    dataForm: $('#form-edit-user').serializeArray()
                },
                success: function (data) {
                    if (data['Status'] == '201'|| data['Status']=='200') {
                        setTimeout(function () {
                            editUser.find('.bootbox-body').html('El usuario se ha actualizado correctamente');
                            $('#edit-usersModal').modal('hide').data('bs.modal', null);
                            $("#list-users").DataTable().ajax.reload(null, false);
                            Command: toastr['success'](data['Message']);
                            editUser.modal('hide');
                        }, 1000);
                    } else {
                        setTimeout(function () {
                            editUser.modal('hide');
                            Command: toastr['error'](data['Message']);
                        }, 500);
                    }
                },
                error: function (data) {
                    setTimeout(function () {
                        editUser.modal('hide');
                        Command: toastr['error']('No es posible procesar la solicitud, por favor comunicarse con el administrador');
                    }, 500);
                }
            });
        });
    } else {
        Command: toastr['warning']('Por favor completar los campos obligatorios');
    }
}

function validateFormEditUser() {
    let campos = $('#form-edit-user').serializeArray();
    let validar = 0;
    $.each(campos, function (index, value) {
        if (value.value == '' && value.name != 'edit-user-password') {
            $('#' + value.name).removeClass('v-form-success').addClass('v-form-error');
            validar = validar + 1;
        } else {
            $('#' + value.name).removeClass('v-form-error').addClass('v-success');
        }
        if (value.name == 'edit-user-email') {
            let regex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
            if (!regex.test(value.value)) {
                $('#' + value.name).removeClass('v-form-success').addClass('v-form-error');
                validar = validar + 1;
            } else {
                $('#' + value.name).removeClass('v-form-error').addClass('v-success');
            }
        }
        if (value.name == 'edit-user-password') {
            let result = validatePassCreate(value.value);
            if (value.value != '') {
                if (result == 'Fuerte!') {
                    $('#edit-user-password-error').html('');
                    $('#' + value.name).removeClass('v-form-error').addClass('v-success');
                } else {
                    $('#' + value.name).removeClass('v-form-success').addClass('v-form-error');
                    if (result != 'La contraseña debe tener un mínimo de 8 caracteres.') {
                        $('#edit-user-password-error').html('Seguridad: ' + result);
                    } else {
                        $('#edit-user-password-error').html(result);
                    }
                    validar = validar + 1;
                }
            }
        }
    });
    if (validar > 0) {
        return false;
    } else {
        return true;
    }
}

function validatePassCreate(pass) {
    let strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
    let mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
    let enoughRegex = new RegExp("(?=.{8,}).*", "g");
    let result = '';
    if (false == enoughRegex.test(pass)) {
        result = 'La contraseña debe tener un mínimo de 8 caracteres.';
    } else if (strongRegex.test(pass) || pass.length > 20) {
        result = 'Fuerte!';
    } else if (mediumRegex.test(pass)) {
        result = 'Media!';
    } else {
        result = 'Débil!';
    }
    return result;
}