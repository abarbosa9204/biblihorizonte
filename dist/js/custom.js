$(document).ready(function() {
    $('.validate-number').on('keyup change keydown keypress', function(event) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });


    $('.validate-text').on('keyup change keydown keypress', function(event) {
        $(this).val($(this).val().replace(/[^a-zA-Z0-9 ()-äÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙ#*/-_.,?¡¿<>$%"]/gi, ''));
    });


})