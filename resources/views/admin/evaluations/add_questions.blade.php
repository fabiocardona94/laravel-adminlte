<x-layout.app meta-title='Evaluación' meta-description="Evaluación">
    <div class="row">
        <div class="col-12">
            <div class="card mt-2">
                <div class="card-header">
                    <div class="d-flex justify-content-start h4">
                        <a href="{{ route('admin.evaluacion.index')}}">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    @if($evaluation)
                        <div class="text-center">
                            <p class="h4">Editando:{{ $evaluation->title }}</p>
                        </div>
                    @else
                        <div class="card-body">
                            <p class="text-center">No se encontró la evaluación.</p>
                        </div>
                    @endif

                </div>
            </div>
            <div class="card mt-2">
                <div class="card-header bg-success">
                  <h3 class="card-title">Cantidad de preguntas: {{$quantity_associated_questions }}</h3>

                  <div class="card-tools">
                    <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-plus" style="color: #0a53d1;"></i>
                            <strong>
                                Agregar Pregunta
                            </strong>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#createQuestion">
                                <i class="fas fa-plus" style="color: #111111;"></i><strong>Nueva pregunta</strong></a>
                            </button>
                            <a class="dropdown-item" href="#"><i class="fas fa-plus" style="color: #111111;"></i><strong>Del banco de preguntas</strong></a>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="{{ route('admin.evaliacion_pregunta.update')}}" method="POST">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="evaluation_id" value="{{$evaluation->id}}">
                            @if ($associated_questions->isEmpty())
                            <div class="card text-center">
                                <div class="card-body">
                                    <strong>
                                        Esta Evaluación aun no tiene preguntas asignadas
                                    </strong>
                                </div>
                            </div>
                            @else
                                @foreach ($associated_questions as $question)
                                    <div class="card shadow">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <strong>
                                                {{ $question->question_title }}
                                            </strong>
                                            <div class="ml-auto">
                                                <a href="" class="" title="Ve respuestas de esta pregunta">
                                                    <i class="far fa-eye" style="color: #000000;"></i>
                                                </a>
                                                <a href="" class="" title="Eliminar pregunta de la evaluación"{{ $evaluation->title }} >
                                                    <i class="fas fa-trash-alt" style="color: #f00000;"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end m-0">
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-plus" style="color: #0a53d1;"></i>
                                                    <strong>
                                                        Agregar Pregunta
                                                    </strong>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#createQuestion">
                                                        <i class="fas fa-plus" style="color: #111111;"></i><strong>Nueva pregunta</strong></a>
                                                    </button>
                                                    <a class="dropdown-item" href="#"><i class="fas fa-plus" style="color: #111111;"></i><strong>Del banco de preguntas</strong></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            @endif
                        </form>
                    </div>
                </div>
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
                        <input type="hidden" value="{{ $evaluation->id }}" id="evaluationId">
                        <div class="form-group">
                            <i class="fas fa-pencil-alt"></i>
                            <label for="question_title" class="col-form-label">Titulo de la pregunta</label>
                            <textarea class="form-control" id="question_title" name="question_title" required cols="30" rows="2" required></textarea>
                        </div>
                        <div class="form-group" id="divOptions">
                            <i class="fas fa-check-square"></i>
                            <label for="question_title" class="col-form-label text-center">Opciones de respuesta para esta pregunta</label>
                            <br>
                        </div>
                        <button class="btn btn-sm btn-primary" type="button" id="buttonAddOption" onclick="addOption()">
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
    @push('scripts')
        <script src="/dist/js/evaluations/evaluation.js"></script>
    @endpush
</x-layout.app>
