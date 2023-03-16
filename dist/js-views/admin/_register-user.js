$(document).ready(function () {
    $('#form-create-user').on('change keyup kedown blur', function () {
        validateFormCreateUser();
    });
});
/**@augments 
 * Reiniciar formulario 
 */
function showFormCreateUser() {
    $('#usersModal').modal('show')
    resetFormCreateUser('reset')
}

function resetFormCreateUser(action) {
    switch (action) {
        case 'cancel':
            var cancel = bootbox.dialog({
                title: '<span class="text-primary" style="font-size:18px;">¿Cancelar?</span>',
                message: "<p><h6>ESTÁ SEGURO DE CANCELAR LA CREACIÓN DE USUARIO.</h6></p>",
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
                            $('#usersModal').modal('hide').data('bs.modal', null);
                            Command: toastr['error']('Proceso cancelado');
                        }
                    }
                }
            });
            break;
        case 'reset':
            $('#create-user-person-id, #create-user-name, #create-user-surname, #create-user-email, #create-user-phone, #create-user-program, #create-user-role, #create-user-password').val('');
            break;
    }
}

/**@augments 
 * submit del formulario
 */
function submitFormCreateUser() {
    if (validateFormCreateUser()) {
        var createUser = bootbox.dialog({
            title: '<h6 class="text-primary">Se está procesando la solicitud.</h6>',
            message: '<p><i class="fas fa-spin fa-spinner"></i> Creando...</p>',
            //centerVertical: true,
            closeButton: false
        });
        createUser.init(function () {
            $.ajax({
                url: "CreateUser",
                type: "post",
                dataType: "json",
                data: {
                    dataForm: $('#form-create-user').serializeArray()
                },
                success: function (data) {
                    if (data['Status'] == '200') {
                        setTimeout(function () {
                            createUser.find('.bootbox-body').html('El usuario se ha creado satisfactoriamente');
                            $('#usersModal').modal('hide').data('bs.modal', null);
                            $("#list-users").DataTable().ajax.reload(null, false);
                            Command: toastr['success'](data['Message']);
                            createUser.modal('hide');
                        }, 1000);
                    } else {
                        setTimeout(function () {
                            createUser.modal('hide');
                            Command: toastr['error'](data['Message']);
                        }, 500);
                    }
                },
                error: function (data) {
                    setTimeout(function () {
                        createUser.modal('hide');
                        Command: toastr['error']('No es posible procesar la solicitud, por favor comunicarse con el administrador');
                    }, 500);
                }
            });
        });
    } else {
        Command: toastr['warning']('Por favor completar los campos obligatorios');
    }
}

function validateFormCreateUser() {
    let campos = $('#form-create-user').serializeArray();
    let validar = 0;
    $.each(campos, function (index, value) {
        if (value.value == '') {
            $('#' + value.name).removeClass('v-form-success').addClass('v-form-error');
            validar = validar + 1;
        } else {
            $('#' + value.name).removeClass('v-form-error').addClass('v-form-success');
        }
        if (value.name == 'create-user-email') {
            let regex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
            if (!regex.test(value.value)) {
                $('#' + value.name).removeClass('v-form-success').addClass('v-form-error');
                validar = validar + 1;
            } else {
                $('#' + value.name).removeClass('v-form-error').addClass('v-form-success');
            }
        }
        if (value.name == 'create-user-password') {
            let result = validatePassCreate(value.value);
            if (result == 'Fuerte!') {
                $('#create-user-password-error').html('');
                $('#' + value.name).removeClass('v-form-error').addClass('v-form-success');
            } else {
                $('#' + value.name).removeClass('v-form-success').addClass('v-form-error');
                if (result != 'La contraseña debe tener un mínimo de 8 caracteres.') {
                    $('#create-user-password-error').html('Seguridad: ' + result);
                } else {
                    $('#create-user-password-error').html(result);
                }
                validar = validar + 1;
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