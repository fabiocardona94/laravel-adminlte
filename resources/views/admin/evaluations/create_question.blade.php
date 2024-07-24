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
                <table id="questions" class="table table-striped table-bordered mt-2 mb-2" style="width:100%">
                    <thead>
                        <tr>
                            <th>Titulo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
    
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            {{-- <th>Filtrar</th>
                            <th>Filtrar</th>
                            <th>Filtrar</th> --}}
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
                    <div class="form-group">
                        <i class="fas fa-pencil-alt"></i>
                        <label for="question_title" class="col-form-label">Titulo de la pregunta</label>
                        <textarea class="form-control" id="question_title" name="question_title" required cols="30" rows="2" required></textarea>
                    </div>
                    <div class="form-group" id="divOptions">
                        <i class="fas fa-check-square"></i>
                        <label for="question_title" class="col-form-label text-center">Opciones de respuesta para esta pregunta</label>
                        <br>
                        <button class="btn btn-sm btn-primary" type="button" onclick="addOption()"> 
                            <i class="fas fa-plus"></i> Agregar Opción
                        </button>
                    </div>
                    <div class="text-center mb-2 mt-2">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-outline-success" onclick="createQuestion()">Crear</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script src="/dist/js/evaluations/evaluation.js"></script>
    @endpush
</x-layout.app>