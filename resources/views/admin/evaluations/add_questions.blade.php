<x-layout.app meta-title='Evaluación' meta-description="Evaluación">
    <div class="row">
        <div class="col-12">
            <div class="card mt-2">
                <div class="card-header">
                    <div class="d-flex justify-content-start">
                        <a href="{{ route('admin.evaluacion.index')}}">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    @if($evaluation)
                        <div class="text-center">
                            <p class="h4">{{ $evaluation->title }}</p>
                        </div>
                    @else
                        <div class="card-body">
                            <p class="text-center">No se encontró la evaluación.</p>
                        </div>
                    @endif

                </div>
            </div>
            <div class="card mt-2">
                <div class="card-header bg-danger">
                  <h3 class="card-title">Eliminar Pregunta</h3>
        
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="{{ route('admin.evaliacion_pregunta.update')}}" method="POST">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="evaluation_id" value="{{$evaluation->id}}">
                            <table id="associated_questions" class="table table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Titulo</th>
                                        <div class="d-flex justify-content-end pb-2">
                                            <button type="submit" class="btn btn-danger text-center">Eliminar Preguntas</button>
                                        </div>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($associated_questions->isEmpty())
                                        <tr>
                                            <td colspan="3">
                                                <p class="text-center">Esta evaluación aún no tiene preguntas asignadas</p>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($associated_questions as $question)
                                            <tr>
                                                <td>
                                                    {{ $question->question_title }}
                                                    <div class="d-flex justify-content-end pb-2">
                                                        <input type="checkbox" name="associated_questions[]" value="{{$question->id}}" class="">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card mt-2">
                <div class="card-header bg-success">
                  <h3 class="card-title">Agregar Pregunta</h3>
        
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <form action=" {{ route('admin.evaliacion_pregunta.store')}}" method="POST">
                        @csrf
                        <input type="hidden" name="evaluation_id" value="{{$evaluation->id}}">
                        <table id="evaluations" class="table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>
                                        Titulo
                                    </th>
                                    <div class="d-flex justify-content-end pb-2">
                                        <button type="submit" class="btn btn-success text-center">Añadair Preguntas</button>
                                    </div>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($unrelated_questions->isEmpty())
                                    <tr>
                                        <td colspan="3">
                                            <p class="text-center">Por el momento no hay mas preguntas</p>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($unrelated_questions as $unrelated_questions)
                                        <tr>
                                            <td>
                                                {{$unrelated_questions->question_title}}
                                                <div class="d-flex justify-content-end pb-2">
                                                    <input type="checkbox" name="unrelated_questions[]" value="{{$unrelated_questions->id}}" class="">
                                                </div>
                                            </td>
                                        </tr> 
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="/dist/js/evaluations/evaluation.js"></script>
        <script>
            $(document).ready(function() {
                $('#associated_questions').DataTable( {
                        "language": {
                            "url": "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                        },
                        lengthMenu: [
                            [10, 25, 50, -1],
                            [10, 25, 50, 'All']
                        ],
                        order: [[ 3, 'desc' ]],
                        // processing: true,
                        // serverSide: true,
                });
            });
        </script>
        </script>
    @endpush
</x-layout.app>