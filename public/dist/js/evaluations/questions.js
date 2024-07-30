const Tokencsrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
// Method to create a new Question
let formCreateQuestion = document.getElementById("createQuestionForm");
if(formCreateQuestion)
{
    formCreateQuestion.addEventListener('submit', function(event) {
        event.preventDefault();
        createQuestion();
    });
}

function createQuestion(){
    const question_title = document.getElementById('question_title').value;
    const optionElements = document.querySelectorAll('[id^="option_"]');
    let options = [];

    optionElements.forEach((input) => {
        if (input.value !== undefined && input.value.trim() !== "") {
            options.push(input.value.trim());
        }
    });
    // console.log("Titulo Pregunta "+question_title);
    // console.log("Opcion"+options);
    // console.log("Token"+Token);

    $.ajax({
        url: '/admin/pregunta/store',
        method: 'POST',
        data: {
            _token: csrfToken,
            question_title: question_title,
            options: options
        },
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire({

                    title: response.message,
                    icon: 'success',
                    confirmButtonText: "Aceptar",
                }).then(() => {
                    $('#createQuestion').modal('hide');
                    $('#questions').DataTable().ajax.reload();
                });
            } else {
                alert('Error: ' + response.message);
                Swal.fire({
                    title: response.message,
                    icon: "error",
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: "Hubo un error al crear la pregunta",
                icon: "error",
            });
        }
    });
}


// Method to create a new Question assosited
let formCreateQuestionAsocciated = document.getElementById("createQuestionAsocciatedForm");
if(formCreateQuestionAsocciated)
{
    formCreateQuestionAsocciated.addEventListener('submit', function(event) {
        event.preventDefault();
        createQuestioAsocciated();
    });
}

function createQuestioAsocciated(){
    const question_title = document.getElementById('question_title').value;
    const evaluation_id = document.getElementById('evaluationId').value;
    const optionElements = document.querySelectorAll('[id^="option_"]');
    let options = [];

    optionElements.forEach((input) => {
        if (input.value !== undefined && input.value.trim() !== "") {
            options.push(input.value.trim());
        }
    });
    console.log("Titulo Pregunta "+question_title);
    console.log("Opcion"+options);
    console.log("Token"+csrfToken);
    console.log("Id de la evaluación"+evaluation_id);


    $.ajax({
        url: '/admin/pregunta/createquestionasociated',
        method: 'POST',
        data: {
            _token: csrfToken,
            question_title: question_title,
            evaluation_id: evaluation_id,
            options: options
        },
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire({

                    title: response.message,
                    icon: 'success',
                    confirmButtonText: "Aceptar",
                }).then(() => {
                    $('#createQuestionAsociated').modal('hide');
                    $('#questions').DataTable().ajax.reload();
                    location.reload();
                });
            } else {
                alert('Error: ' + response.message);
                Swal.fire({
                    title: response.message,
                    icon: "error",
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: "Hubo un error al crear la pregunta",
                icon: "error",
            });
        }
    });
}


// Method to update the status of an question ascociated
function updateAssociatedQuestion (evaluation_id,question_id){

    // console.log('Id Evaluación '+evaluation_id);
    // console.log('Id Pregunta '+question_id);
    // console.log('Token '+Token);

    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, actualizar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/admin/evaluacion_pregunta/update/'+ evaluation_id + '/' + question_id,
                method: 'PATCH',
                data: {
                    _token:Tokencsrf,
                    evaluation_id: evaluation_id,
                    question_id: question_id,
                },
                success: function(response) {
                    if(response.status === 'success') {
                        Swal.fire({

                            title: response.message,
                            icon: 'success',
                            confirmButtonText: "Aceptar",
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        alert('Error: ' + response.message);
                        Swal.fire({
                            title: response.message,
                            icon: "error",
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Hubo un error al eliminar la pregunta",
                        icon: "error",
                    });
                }
                // .finally(() =>{
                //     btnCreateEvaluation.removeAttribute('disabled')
                // }),
            });
        }
    });


}

//Method to obtain questions from the question bank


function consultQuestionBank(id, page = 1) {
    console.log(id);

    $.ajax({
        url: '/admin/pregunta/bancopreguntas/' + id,
        method: 'GET',
        data: {
            _token: csrfToken,
            id: id,
            page: page,
        },
        success: function(response) {
            if (response.status === 'success') {
                console.log('Contador ' + response.quantity_associated_questions);

                // Limpia la tabla antes de agregar nuevos datos
                $('#associated_questions tbody').empty();

                // Llena la tabla con las preguntas no asociadas
                response.unrelated_questions.forEach(function(question) {
                    $('#associated_questions tbody').append(`
                        <tr>
                            <td>${question.question_title}
                                <div class="d-flex justify-content-end pb-2">
                                    <input type="checkbox" name="unrelated_questions[]" value="${question.id}" class="">
                                </div>
                            </td>
                        </tr>
                    `);
                });

                // Actualiza el paginador
                updatePagination(response.pagination,id);

                // Muestra el modal
                $('#bankQuestions').modal('show');

            } else {
                alert('Error: ' + response.message);
                Swal.fire({
                    title: response.message,
                    icon: "error",
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: "No se ha podido obtener las preguntas",
                icon: "error",
            });
        }
    });
}

function updatePagination(pagination,id) {
    let paginationHtml = '';

    if (pagination.current_page > 1) {
        paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="consultQuestionBank(${id}, ${pagination.current_page - 1})"> <span aria-hidden="true">&laquo;</span></a></li>`;
    } else {
        paginationHtml += `<li class="page-item disabled"><a class="page-link"><span aria-hidden="true">&laquo;</span></a></li>`;
    }

    for (let i = 1; i <= pagination.last_page; i++) {
        if (i === pagination.current_page) {
            paginationHtml += `<li class="page-item active" aria-current="page"><a class="page-link" href="#" onclick="consultQuestionBank(${id}, ${i})">${i}</a></li>`;
        } else {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="consultQuestionBank(${id}, ${i})">${i}</a></li>`;
        }
    }

    if (pagination.current_page < pagination.last_page) {
        paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="consultQuestionBank(${id}, ${pagination.current_page + 1})"><span aria-hidden="true">&raquo;</span></a></li>`;
    } else {
        paginationHtml += `<li class="page-item disabled"><a class="page-link"><span aria-hidden="true">&raquo;</span></a></li>`;
    }

    $('.pagination').html(paginationHtml);
}
