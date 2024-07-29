<x-layout.app meta-title='Evaluaciónes' meta-description="Evaluación">
    <div class="row">
        <div class="col-12">
            <nav class="nav nav-pills flex-column flex-sm-row mt-2">
                <a class="flex-sm-fill text-sm-center nav-link"  href="{{ route('admin.evaluacion.index')}}">Evaluciones</a>
                <a class="flex-sm-fill text-sm-center nav-link active" href="{{ route('admin.pregunta.index')}}">Preguntas</a>
            </nav>
        </div>
        <div class="col-sm-12 ">
            <h1 class="m-0 mb-3 text-center">Listado de preguntas</h1>
            <div class="table-responsive">
                <div class="d-flex justify-content-end mb-2 mt-2">
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#createQuestion"><i class="fas fa-question-circle"></i>Crear pregunta</button>
                </div>
                <table id="questions" class="table table-bordered mt-2 mb-2" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Titulo</th>
                            <th>Estado</th>
                            <th>Acciones</th>

                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Filtrar</th>
                            <th>Filtrar</th>
                            <th>Filtrar</th>
                            <th>Filtrar</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal questions -->
    <div class="modal fade" id="createQuestion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5
                        class="modal-title ml-2" id="exampleModalLabel">
                        <i class="far fa-question-circle"></i>
                        Crear Pregunta
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createQuestionForm">
                        <div class="form-group">
                            <i class="fas fa-pencil-alt"></i>
                            <label for="question_title" class="col-form-label">Titulo de la pregunta</label>
                            <textarea class="form-control" id="question_title" name="question_title" required cols="30" rows="2" required></textarea>
                        </div>
                        <div class="form-group" id="divOptions">
                            <i class="fas fa-list-ul"></i>
                            <label for="question_title" class="col-form-label text-center">Opciones de respuesta para esta pregunta</label>
                            <br>
                        </div>
                        <button class="btn btn-sm btn-primary" type="button" onclick="addOption()">
                            <i class="fas fa-plus"></i> Agregar Opción
                        </button>
                        <div class="text-center mb-2 mt-2">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-outline-success">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

      <!-- Modal edit Evaluation -->
    <div class="modal fade" id="editQuestion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title ml-2" id="exampleModalLabel">Editar Pregunta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editQuestionForm">
                        <input type="hidden" id="evaluation_id" name="evaluation_id">
                        <div class="form-group">
                            <i class="fas fa-pencil-alt"></i>
                            <label for="title_evaluation" class="col-form-label">Titulo de la pregunta</label>
                            <input type="text" value="" class="form-control" id="title_evaluation" name="title_evaluation" required>
                        </div>
                        <div class="form-group">
                            <i class="far fa-comment-dots"></i>
                            <label for="description_evaluation" class="col-form-label">Descripción de la evaluación</label>
                            <input type="text" value="" class="form-control" id="description_evaluation" name="description_evaluation" required>
                        </div>
                        <div class="form-group">
                            <i class="fas fa-calendar-check"></i>
                            <label for="start_date_evaluation" class="col-form-label">Fecha en que inicia la evaluación</label>
                            <input type="datetime-local" value="" class="form-control" id="start_date_evaluation" name="start_date_evaluation" required>
                        </div>
                        <div class="form-group">
                            <i class="fas fa-calendar-times"></i>
                            <label for="end_date_evaluation" class="col-form-label">Fecha en que termina la evaluación</label>
                            <input type="datetime-local" value="" class="form-control" id="end_date_evaluation" name="end_date_evaluation" required>
                        </div>
                        <div class="form-group">
                            <i class="fas fa-toggle-on"></i>
                            <label for="end_date_evaluation" class="col-form-label">Estado</label>
                            <select class="form-control text-black" name="evaluation_status" id="evaluation_status"></select>
                        </div>
                        <div class="text-center mb-2 mt-2">
                            <button type="button" class="btn btn-outline-danger" onclick="" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-outline-success">Actualizar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="/dist/js/evaluations/questions.js"></script>
        <script src="/dist/js/evaluations/options.js"></script>
        <script>
            $(document).ready(function() {
                $('#questions').DataTable( {
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
                    ajax: '/admin/pregunta/questions',
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'question_title', name: 'question_title' },
                        { data: 'status', name: 'status', orderable: false, searchable: false },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    ],
                    initComplete: function () {
                        $('#evaluations tfoot tr').appendTo('#evaluations thead');
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
                } );
            } );
        </script>

    @endpush
</x-layout.app>
