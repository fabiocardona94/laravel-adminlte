<x-layout.app meta-title='Evaluación' meta-description="Evaluación">
    <div class="row">
        <div class="col-12">
            <nav class="nav nav-pills flex-column flex-sm-row mt-2">
                <a
                 class="flex-sm-fill text-sm-center nav-link {{ request()->routeIs(["admin.evaluacion.index"]) ? "active" : "" }}" href="{{ route('admin.evaluacion.index')}}">Evaluciones
                </a>
                <a class="flex-sm-fill text-sm-center nav-link" href="{{ route('admin.pregunta.index')}}">Preguntas</a>
            </nav>
        </div>
        <div class="col-sm-6">
            <h1 class="m-0">Listado Evaluaciones</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Evaluaciones</li>
            </ol>
        </div>
        <div class="col-sm-12 mt-2">
            <div class="table-responsive">
                <div class="d-flex justify-content-end mb-2 mt-2">
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#mdlCreateEvalution" title="Crear Evaluación"><i class="fas fa-edit"></i></i>Crear Evaluación</button>
                </div>
                <table id="evaluations" class="table table-bordered mb-2" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Titulo</th>
                            <th>Descripción</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
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
                            <th>Filtrar</th>
                            <th>Filtrar</th>
                            <th>Filtrar</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal create evaluation -->
    <div class="modal fade" id="mdlCreateEvalution" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="createEvaluationForm">
                    <div class="modal-header">
                        <h5 class="modal-title ml-2" id="exampleModalLabel">Crear Evaluación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <i class="fas fa-pencil-alt"></i>
                            <label for="titleEvaluation" class="col-form-label">Titulo de la evaluación</label>
                            <input type="text" value="" class="form-control" id="titleEvaluation" name="titleEvaluation" min="4" max="255" required>
                        </div>
                        <div class="form-group">
                            <i class="far fa-comment-dots"></i>
                            <label for="descriptionEvaluation" class="col-form-label">Descripción de la evaluación</label>
                            <input type="text" value="" class="form-control" id="descriptionEvaluation" name="descriptionEvaluation" required>
                        </div>
                        <div class="form-group">
                            <i class="fas fa-calendar-check"></i>
                            <label for="startDate" class="col-form-label">Fecha en que inicia la evaluación</label>
                            <input type="datetime-local" value="" class="form-control" id="startDate" name="startDate"
                                min="{{ date('Y-m-d\TH:i') }}"
                                value="{{ date('Y-m-d\TH:i') }}"
                                required
                            >
                        </div>
                        <div class="form-group">
                            <i class="fas fa-calendar-times"></i>
                            <label for="endDate" class="col-form-label">Fecha en que termina la evaluación</label>
                            <input type="datetime-local" value="" class="form-control" id="endDate" name="endDate"
                                min="{{ date('Y-m-d\TH:i') }}"
                                value="{{ date('Y-m-d\TH:i') }}"
                                required
                            >
                        </div>
                        <div class="text-center mb-2 mt-2">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                            <button type="submit" id="btnCreateEvaluation" class="btn btn-outline-success">Crear</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal edit Evaluation -->
    <div class="modal fade" id="mdlEditEvalution" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title ml-2" id="exampleModalLabel">Editar Evaluación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editEvaluationForm">
                        <input type="hidden" id="idEvaluation" name="idEvaluation">
                        <div class="form-group">
                            <i class="fas fa-pencil-alt"></i>
                            <label for="editEvaluationTitle" class="col-form-label">Titulo de la evaluación</label>
                            <input type="text" value="" class="form-control" id="editEvaluationTitle" name="editEvaluationTitle" required>
                        </div>
                        <div class="form-group">
                            <i class="far fa-comment-dots"></i>
                            <label for="editEvaluationDescription" class="col-form-label">Descripción de la evaluación</label>
                            <input type="text" value="" class="form-control" id="editEvaluationDescription" name="editEvaluationDescription" required>
                        </div>
                        <div class="form-group">
                            <i class="fas fa-calendar-check"></i>
                            <label for="editEvaluationStarDate" class="col-form-label">Fecha en que inicia la evaluación</label>
                            <input type="datetime-local" value="" class="form-control" id="editEvaluationStarDate" name="editEvaluationStarDate"
                                min="{{ date('Y-m-d\TH:i') }}"
                                value="{{ date('Y-m-d\TH:i') }}" required
                            >
                        </div>
                        <div class="form-group">
                            <i class="fas fa-calendar-times"></i>
                            <label for="editEvaluationEndDate" class="col-form-label">Fecha en que termina la evaluación</label>
                            <input type="datetime-local" value="" class="form-control" id="editEvaluationEndDate" name="editEvaluationEndDate"
                                min="{{ date('Y-m-d\TH:i') }}"
                                value="{{ date('Y-m-d\TH:i') }}" required
                            >
                        </div>
                        <div class="form-group">
                            <i class="fas fa-exclamation-triangle"></i>
                            <label for="editEvaluationStatus" class="col-form-label">Estado</label>
                            <select class="form-control text-black" name="editEvaluationStatus" id="editEvaluationStatus"></select>
                        </div>
                        <div class="text-center mb-2 mt-2">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-outline-success" id="btnEditEvaluation">Actualizar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script  src="/dist/js/evaluations/evaluations.js"></script>
    <script>
        $(document).ready(function() {
            $('#evaluations').DataTable( {
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
                ajax: '/admin/evaluacion/evaluations',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'title', name: 'title' },
                    { data: 'description', name: 'description' },
                    { data: 'start_date', name: 'start_date' },
                    { data: 'end_date', name: 'end_date' },
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
