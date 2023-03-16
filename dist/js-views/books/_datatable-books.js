$(document).ready(function () {

    /**@augments 
     * Datatable paginación ajax
     */
    var table = $('#list-books').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        pageLength: 10,
        lengthMenu: [10, 25, 50, 75, 100],
        sort: true,
        ajax: {
            url: 'GetListBooks',
            /*continue: 'post',
            success: function(data) {
                console.log(data);
            }*/
        },
        columns: [
            { data: 'Libro' },
            { data: 'Descripcion' },
        ],
        columnDefs: [{
            targets: [1],
            sortable: false,
        },
            //{targets: [1,2],className:'text-center' },
        ],
        language: {
            emptyTable: 'No hay datos para mostrar',
            zeroRecords: 'No se encontraron resultados',
            thousands: ',',
            processing: 'Procesando...',
            loadingRecords: 'Cargando...',
            info: 'Mostrando de _START_ a _END_ de un total de _TOTAL_',
            infoEmpty: ' 0 registros',
            infoFiltered: '(filtrado de _MAX_ registros)',
            infoPostFix: '',
            lengthMenu: 'Registros _MENU_',
            search: 'Buscar:',
            paginate: {
                first: 'Primero',
                last: 'Último',
                next: 'Siguiente',
                previous: 'Anterior'
            },
            aria: {
                sortAscending: ' Activar para ordenar la columna de manera ascendente',
                sortDescending: ' Activar para ordenar la columna de manera descendente'
            },
            //scrollY: "200px",
            scrollX: true,
            scrollCollapse: true,
            paging: true,
        },
        // responsive: {
        //     pagingType: "simple_numbers"
        // }
    });
});
