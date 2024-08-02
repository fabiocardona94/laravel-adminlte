
const Tokencsrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const BtnCreateQuestion = document.getElementById('btnCreateQuestion');
const btnCreateAssociatedQuestion = document.getElementById('btnCreateAssociatedQuestion');
const btnEditAssociatedQuestion = document.getElementById('btnEditAssociatedQuestion');
const btnSendAssociatedQuestion = document.getElementById('btnSendAssociatedQuestion');
const evaluation_id = document.getElementById('evaluationId').value;

let  optionCounts = 0;
let selectedValues = [];
const headersFetch1 = {
    "Content-Type": "application/json",
    "Accept": "application/json, text-plain, */*",
    "X-Requested-With": "XMLHttpRequest",
    "X-CSRF-TOKEN": Tokencsrf
};


// Method to create a new Question
let formCreateQuestion = document.getElementById("createQuestionForm");
if(formCreateQuestion)
{
    formCreateQuestion.addEventListener('submit', function(event) {
        event.preventDefault();
        BtnCreateQuestion.setAttribute('disabled','disabled');
        createQuestion();
    });
}

function createQuestion(){
    const question_title = document.getElementById('questionTitle').value;
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

    let data = {
        question_title: question_title,
        options: options
    }

    fetch(`/admin/pregunta/store`,{
        method : 'POST',
        headers : headersFetch1,
        body : JSON.stringify(data)

    })
    .then(response => response.json())
    .then(response =>{
        if(response.status === 'success') {
            Swal.fire({
                title: response.message,
                icon: 'success',
                confirmButtonText: "Aceptar",
            }).then(() => {
                $('#mdlcreateQuestion').modal('hide');
                $('#questions').DataTable().ajax.reload();
            });
        } else {
            alert('Error: ' + response.message);
            Swal.fire({
                title: response.message,
                icon: "error",
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: "Hubo un error al crear la pregunta",
            icon: "error",
        });
    })
    .finally(() =>{
        BtnCreateQuestion.removeAttribute('disabled');
    });
}


// Method to create a new Question assosited
let formCreateQuestionAsocciated = document.getElementById("createQuestionAsocciatedForm");
if(formCreateQuestionAsocciated)
{
    formCreateQuestionAsocciated.addEventListener('submit', function(event) {
        event.preventDefault();
        btnCreateAssociatedQuestion.setAttribute('disabled','disabled');
        createQuestioAsocciated();
    });
}

function createQuestioAsocciated()
{
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
    // console.log("Token"+csrfToken);
    // console.log("Id de la evaluación"+evaluation_id);

    let data = {
        question_title: question_title,
        evaluation_id: evaluation_id,
        options: options
    }

    fetch(`/admin/pregunta/createquestionasociated`,{
        method : 'POST',
        headers : headersFetch1,
        body : JSON.stringify(data)

    })
    .then(response => response.json())
    .then(response =>{
        if(response.status === 'success') {
            Swal.fire({
                title: response.message,
                icon: 'success',
                confirmButtonText: "Aceptar",
            }).then(() => {
                $('#mdlCreateQuestionAsociated').modal('hide');
                location.reload();
            });
        } else {
            alert('Error: ' + response.message);
            Swal.fire({
                title: response.message,
                icon: "error",
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: "Hubo un error al crear la pregunta",
            icon: "error",
        });
    })
    .finally(() =>{
        btnCreateAssociatedQuestion.removeAttribute('disabled');
    });
}

// Method to display evaluation data in a modal.

function openModalEditQuestion(id){
    optionCounts = 0;
    fetch(`/admin/pregunta/edit/${id}`)
    .then(response => response.json())
    .then(response =>{
        if(response.status === 'success') {
            getDataQuestuion(response.question);
            getOptionsasociateds(response.options_asociated.options);
        } else {
            $('#modalEditQuestion').modal('hide');
            Swal.fire({
                title: response.message,
                icon: "error"
            });
        }
    })
    .catch(error => {
        $('#modalEditQuestion').modal('hide ');
        Swal.fire({
            title: "Hubo un error al obtener los datos de la evaluación",
            icon: "error"
        });
    })
}

//Method to obtain the data of a question and show them in the modal
function getDataQuestuion(dataQuestion){
    // Llenar los campos del modal con los datos de la evaluación
    // $('#question_id').val(dataQuestion.id);
    $('#title_question_edit').val(dataQuestion.question_title);
    $('#question_status_edit').val(dataQuestion.status);

    // Limpiar el select antes de agregar opciones
    $('#question_status_edit').empty();

    // Agregar la opción actual
    if(dataQuestion.status == 0) {
        $('#question_status_edit').append(new Option("INACTIVA", "0", true, true));
        $('#question_status_edit').append(new Option("ACTIVA", "1"));
    } else {
        $('#question_status_edit').append(new Option("ACTIVA", "1", true, true));
        $('#question_status_edit').append(new Option("INACTIVA", "0"));
    }
}

//Method to obtain the associated options of a question and show them in the modal
function getOptionsasociateds(options){

    // Verifica si response.options_asociated es un array
    if (Array.isArray(options)) {
        // Limpiar el contenedor de opciones
        $('#containerEditOptions').empty();

        options.forEach(options => {
            optionCounts++;
            // Create a new div element to hold the label, input, and remove button
            const newOptionDiv = document.createElement('div');
            newOptionDiv.classList.add('form-group');
            newOptionDiv.id = `option_div_${optionCounts}`;

            // Create a new label element
            const newLabel = document.createElement('label');
            newLabel.for = `option_${optionCounts}`;
            newLabel.className = 'col-form-label';
            newLabel.textContent = `Opción ${optionCounts}`;

            // Create a wrapper for input and remove button
            const inputWrapper = document.createElement('div');
            inputWrapper.classList.add('d-flex', 'align-items-center');

            // Create a new input element
            const newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.className = 'form-control';
            newInput.id = `option_${optionCounts}`;
            newInput.name = `option_${optionCounts}`;
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
            removeButton.onclick = function() { removeOption(optionCounts); };


            // Append the input and remove button to the input wrapper
            inputWrapper.appendChild(newInput);
            // inputWrapper.appendChild(correctOptionButton);
            inputWrapper.appendChild(removeButton);

            // Append the label and input wrapper to the new div
            newOptionDiv.appendChild(newLabel);
            newOptionDiv.appendChild(inputWrapper);

            // Append the new div to the divOptions container
            document.getElementById('containerEditOptions').appendChild(newOptionDiv);
        });

        // Muestra el modal
        $('#modalEditQuestion').modal('show');
    } else {
        console.error('options_asociated no es un array:', response.options_asociated);
        Swal.fire({
            title: 'Error en los datos recibidos',
            icon: 'error',
        });
    }

}

//Method to update the data of a question.


let FormEditQuestion = document.getElementById("editQuestionForm");
if(FormEditQuestion)
{
    FormEditQuestion.addEventListener('submit', function(event) {
        event.preventDefault();
        btnEditAssociatedQuestion.setAttribute('disabled','disabled')
        updateQuestion();
    });
}


function updateQuestion(){

    const id = document.getElementById('question_id').value;
    const question_title = document.getElementById('title_question_edit').value;
    const status = document.getElementById('question_status_edit').value;
    // console.log('id '+id);
    // console.log('title '+question_title);
    // console.log('status '+status);
    status === "INACTIVA" ? status == 0:  status == 1;


    let data = {
        question_title: question_title,
        status: status,
    }

    fetch(`/admin/pregunta/update/${id}`,{
        method : 'PATCH',
        headers : headersFetch1,
        body : JSON.stringify(data)

    })
    .then(response => response.json())
    .then(response =>{
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
    })
    .catch(error => {
        Swal.fire({
            title: "Hubo un error al crear la evaluación",
            icon: "error",
        });
    })
    .finally(() =>{
        btnEditAssociatedQuestion.removeAttribute('disabled');
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

            let data = {
                evaluation_id: evaluation_id,
                question_id: question_id,
            }

            fetch(`/admin/evaluacion_pregunta/update/${evaluation_id}/${question_id}`,{
                method : 'PATCH',
                headers : headersFetch1,
                body : JSON.stringify(data)

            })
            .then(response => response.json())
            .then(response =>{
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
            })
            .catch(error => {
                Swal.fire({
                    title: "Hubo un error al eliminar la pregunta",
                    icon: "error",
                });
            })
        }
    });


}

//Method to obtain questions from the question bank


// Method for get the questions  and see the modal
function consultQuestionBank(id, page = 1) {
    fetch(`/admin/pregunta/bancopreguntas/${id}/${page}`)
    .then(response => response.json())
    .then(response =>{
        if (response.status === 'success') {
            // Limpia la tabla antes de agregar nuevos datos
            $('#associated_questions tbody').empty();

            // Llena la tabla con las preguntas no asociadas
            response.unrelated_questions.forEach(function(question) {
                const isChecked = selectedValues.includes(question.id.toString()); // Verifica si el valor está en selectedValues
                $('#associated_questions tbody').append(`
                    <tr>
                        <td>${question.question_title}
                            <div class="d-flex justify-content-end pb-2">
                                <input type="checkbox" id="inputSelectQuestion_${question.id}" name="unrelated_questions[]" value="${question.id}" class="" ${isChecked ? 'checked' : ''}>
                            </div>
                        </td>
                    </tr>
                `);
            });

            // Actualiza el paginador
            updatePagination(response.pagination, id);

            // Muestra el modal
            $('#mdlbankQuestions').modal('show');

        } else {
            alert('Error: ' + response.message);
            Swal.fire({
                title: response.message,
                icon: "error",
            });
        }
    })
    .catch(error =>{
        Swal.fire({
            title: "No se ha podido obtener las preguntas",
            icon: "error",
        });
    });
}

// Agrega un evento de cambio al tbody que contiene los inputs dinámicos
let inputSelectQuestion = document.querySelector('#associated_questions tbody').addEventListener('change', function(event) {
    if (event.target && event.target.matches('input[type="checkbox"]')) {
        const checkboxValue = event.target.value;

        if (event.target.checked) {
            if (!selectedValues.includes(checkboxValue)) {
                selectedValues.push(checkboxValue);
            }
        } else {
            selectedValues = selectedValues.filter(value => value !== checkboxValue);
        }

        const anyChecked = selectedValues.length > 0;

        if (anyChecked) {
            btnSendAssociatedQuestion.classList.remove('disabled');
            btnSendAssociatedQuestion.removeAttribute('disabled');
        } else {
            btnSendAssociatedQuestion.classList.add('disabled');
            btnSendAssociatedQuestion.setAttribute('disabled', true);
        }
    }
});

//Mehod for the  paginator in modal when star the questions obtains del bank the questions
function updatePagination(pagination, id) {
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

//Method for the modal pager where I show the available questionsbank
let formbankQuestions = document.getElementById("bankQuestionsForm");
if(formbankQuestions)
{
    formbankQuestions.addEventListener('submit', function(event) {
        event.preventDefault();
        btnSendAssociatedQuestion.setAttribute('disabled','disabled');
        createMultipleAssociatedQuestions();
    });
}else{
    alert('Heror')
}

//Method to create
function createMultipleAssociatedQuestions()
{
    let data = {
        evaluation_id: evaluation_id,
        questions : selectedValues
    }

    fetch(`/admin/pregunta/createmultiplequestionsasociated`,{
        method : 'POST',
        headers : headersFetch1,
        body : JSON.stringify(data)

    })
    .then(response => response.json())
    .then(response =>{
        if(response.status === 'success') {
            Swal.fire({
                title: response.message,
                icon: 'success',
                confirmButtonText: "Aceptar",
            }).then(() => {
                $('#mdlbankQuestions').modal('hide');
                location.reload();
            });
        } else {
            alert('Error: ' + response.message);
            Swal.fire({
                title: response.message,
                icon: "error",
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: "Hubo un error al crear la pregunta",
            icon: "error",
        });
    })
    .finally(() =>{
        btnSendAssociatedQuestion.removeAttribute('disabled');
    });
}


