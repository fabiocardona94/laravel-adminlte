const Tokencsrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let  optionCount = 0;
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

// Method to display evaluation data in a modal.

function openModalEditQuestion(id){
    optionCount = 0;
    console.log('Id '+id);

    $.ajax({
        url: '/admin/pregunta/edit/' + id ,
        method: 'GET',
        success: function(response) {
            if(response.status === 'success') {
                // Llenar los campos del modal con los datos de la evaluación
                $('#question_id').val(response.question.id);
                $('#title_question_edit').val(response.question.question_title);
                $('#question_status_edit').val(response.question.status);

                // Limpiar el select antes de agregar opciones
                $('#question_status_edit').empty();

                // Agregar la opción actual
                if(response.question.status == 0) {
                    $('#question_status_edit').append(new Option("INACTIVA", "0", true, true));
                    $('#question_status_edit').append(new Option("ACTIVA", "1"));
                } else {
                    $('#question_status_edit').append(new Option("ACTIVA", "1", true, true));
                    $('#question_status_edit').append(new Option("INACTIVA", "0"));
                }

                // Verifica si response.options_asociated es un array
                if (Array.isArray(response.options_asociated.options)) {
                    // Limpiar el contenedor de opciones
                    $('#containerEditOptions').empty();

                    response.options_asociated.options.forEach(options => {
                        optionCount++;
                        // Create a new div element to hold the label, input, and remove button
                        const newOptionDiv = document.createElement('div');
                        newOptionDiv.classList.add('form-group');
                        newOptionDiv.id = `option_div_${optionCount}`;

                        // Create a new label element
                        const newLabel = document.createElement('label');
                        newLabel.for = `option_${optionCount}`;
                        newLabel.className = 'col-form-label';
                        newLabel.textContent = `Opción ${optionCount}`;

                        // Create a wrapper for input and remove button
                        const inputWrapper = document.createElement('div');
                        inputWrapper.classList.add('d-flex', 'align-items-center');

                        // Create a new input element
                        const newInput = document.createElement('input');
                        newInput.type = 'text';
                        newInput.className = 'form-control';
                        newInput.id = `option_${optionCount}`;
                        newInput.name = `option_${optionCount}`;
                        newInput.value = options.question_option;
                        newInput.required = true;

                        if (options.is_correct === 1) {
                            newInput.classList.add('text-success');
                        } else {
                            newInput.classList.add('text-danger');
                        }



                        // Create a new remove button
                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.className = 'btn btn-danger btn-sm ml-2';
                        removeButton.textContent = 'X';
                        removeButton.title = 'Eliminar Opción';
                        removeButton.onclick = function() { removeOption(optionCount); };


                        // Append the input and remove button to the input wrapper
                        inputWrapper.appendChild(newInput);
                        // inputWrapper.appendChild(correctOptionButton);
                        inputWrapper.appendChild(removeButton);

                        // Append the label and input wrapper to the new div
                        newOptionDiv.appendChild(newLabel);
                        newOptionDiv.appendChild(inputWrapper);

                        // Append the new div to the divOptions container
                        document.getElementById('containerEditOptions').appendChild(newOptionDiv);

                        // // Añadir el div al contenedor
                        // $('#containerEditOptions').append(newOptionDiv);
                    });

                    // Muestra el modal (si es necesario)
                    $('#modalEditQuestion').modal('show');
                } else {
                    console.error('options_asociated no es un array:', response.options_asociated);
                    Swal.fire({
                        title: 'Error en los datos recibidos',
                        icon: 'error',
                    });
                }
            } else {
                $('#modalEditQuestion').modal('hide');
                Swal.fire({
                    title: response.message,
                    icon: "error"
                });
            }
        },
        error: function(xhr, status, error) {
            $('#modalEditQuestion').modal('hide ');
            Swal.fire({
                title: "Hubo un error al obtener los datos de la evaluación",
                icon: "error"
            });
        }
    });
}
//Method to update the data of a question.


let FormEditQuestion = document.getElementById("editQuestionForm");
if(FormEditQuestion)
{
    FormEditQuestion.addEventListener('submit', function(event) {
        event.preventDefault();
        updateQuestion();
    });
}

function updateQuestion(){

    const id = document.getElementById('question_id').value;
    const question_title = document.getElementById('title_question_edit').value;
    const status = document.getElementById('question_status_edit').value;
    console.log('id '+id);
    console.log('title '+question_title);
    console.log('status '+status);
    status === "INACTIVA" ? status == 0:  status == 1;
    $ .ajax({
        url: '/admin/pregunta/update/'+id,
        method: 'PATCH',
        data: {
            _token: Tokencsrf,
            question_title: question_title,
            status: status,
        },
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire({

                    title: response.message,
                    icon: 'success',
                    confirmButtonText: "Aceptar",

                }).then(() => {
                    // Cerrar el modal
                    $('#modalEditQuestion').modal('hide');
                    // Recargar la DataTable
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
                title: "Hubo un error al crear la evaluación",
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
                                    <input type="checkbox" id="selectOptions" name="unrelated_questions[]" value="${question.id}" class="">
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
