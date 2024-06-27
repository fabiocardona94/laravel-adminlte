$(document).ready(function() {
    $('#solicitudes').DataTable( {
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
        },
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All']
        ],
        order: [[ 3, 'desc' ]],
        processing: true,
        serverSide: true,
        ajax: '/admin/solicitar/datatable',
        columns: [
            { data: 'user_name', name: 'user_name' },
            { data: 'tipo_solicitud', name: 'tipo_solicitud' },
            { data: 'observacion', name: 'observacion' },
            { data: 'created_at', name: 'created_at' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
        ],
        initComplete: function () {
            $('#solicitudes tfoot tr').appendTo('#solicitudes thead');
            this.api()
                .columns()
                .every(function () {
                    let column = this;
                    let title = column.footer().textContent;
    
                    // Create input element
                    let input = document.createElement('input');
                    input.classList.add('form-control', 'p-2');
                    input.placeholder = title;
                    column.footer().replaceChildren(input);
    
                    // Event listener for user input
                    input.addEventListener('keyup', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                    });
                });
        },
        layout: {
            topStart: {
                buttons: [
                    {
                        extend: 'copyHtml5',
                        titleAttr: 'Copiar',
                        text: '<i class="fas fa-copy"></i> Copiar',
                        className: 'btn btn-secondary'
                    }, 
                    {
                        extend: 'csv',
                        titleAttr: 'Exportar en formato CSV',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-info'
                    }, 
                    {
                        extend: 'excel',
                        titleAttr: 'Exportar en formato Excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success'
                    },
                    {
                        extend: 'pdf',
                        titleAttr: 'Exportar en formato PDF',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger'
                    },
                    {
                        extend: 'print',
                        titleAttr: 'Imprimir',
                        text: '<i class="fas fa-print"></i> Imprimir',
                        className: 'btn btn-primary'
                    },
                ]
            }
        },
    } );
} );