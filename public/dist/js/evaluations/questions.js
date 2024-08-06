
const Tokencsrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const btnCreateQuestion = document.getElementById('btnCreateQuestion');
const btnCreateAssociatedQuestion = document.getElementById('btnCreateAssociatedQuestion');
const btnEditAssociatedQuestion = document.getElementById('btnEditAssociatedQuestion');
const btnSendAssociatedQuestion = document.getElementById('btnSendAssociatedQuestion');

let  optionCounts = 0;
let objectEcxit = true;
let isTableOptionsVisible = false;
let selectedValues = [];
const headersFetch1 = {
    "Content-Type": "application/json",
    "Accept": "application/json, text-plain, */*",
    "X-Requested-With": "XMLHttpRequest",
    "X-CSRF-TOKEN": Tokencsrf
};

//Method to obtain the data de
function getOptionsData() {
    const rows = document.querySelectorAll('#trOptions tr');
    const optionsData = [];

    rows.forEach(row => {
        const titleInput = row.querySelector('input[id^="title_asociated"]');
        const optionSpan = row.querySelector('span[id^="option_"]');
        const optionValue = (optionSpan.textContent === "FALSA") ? 0 : 1;
        const percentageSpan = row.querySelector('span[id^="percentage_"]');
        const percentageValue = percentageSpan ? percentageSpan.textContent.replace('%', '') : '';

        optionsData.push({
            title: titleInput ? titleInput.value : '',
            option: optionValue,
            percentage: percentageValue
        });
    });

    return optionsData;
}


// Method to create a new Question
let formCreateQuestion = document.getElementById("createQuestionForm");
if(formCreateQuestion)
{
    formCreateQuestion.addEventListener('submit', function(event) {
        event.preventDefault();
        btnCreateQuestion.setAttribute('disabled','disabled');
        createQuestion();
    });
}

function createQuestion(){
    const questionTitle = document.getElementById('questionTitle').value;
    const dataQuestion = getOptionsData();
    // console.log("Titulo Pregunta "+questionTitle);

    let data = {
        question_title: questionTitle,
        options: dataQuestion
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
        btnCreateQuestion.removeAttribute('disabled');
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
    const idEvaluationAsociated = document.getElementById('idEvaluationAsociated').value;
    // console.log("ID Evaluación "+idEvaluationAsociated);
    const associatedQuestionTitle = document.getElementById('associatedQuestionTitle').value;
    const dataAsociatedQuestion = getOptionsData();

    let data = {
        question_title: associatedQuestionTitle,
        evaluation_id: idEvaluationAsociated,
        options: dataAsociatedQuestion
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
    $('#idQuestionEdit').val(dataQuestion.id);
    $('#title_asociatedquestion_edit').val(dataQuestion.question_title);
    console.log('Titulo '+dataQuestion.question_title);

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
function getOptionsasociateds(options) {
    // Verifica si options es un array
    if (Array.isArray(options)) {
        // Limpiar el contenedor de opciones
        $('#trOptionsAsociated').empty();

        options.forEach(option => {
            optionCounts++;
            // Create a new row (tr) element
            const newOptionRow = document.createElement('tr');
            newOptionRow.id = `option_asociated_row_${optionCounts}`;

            // Create a new cell (td) for the title input
            const titleCell = document.createElement('td');
            const titleInput = document.createElement('input');
            titleInput.type = 'text';
            titleInput.className = 'form-control-plaintext';
            titleInput.id = `title_asociated${optionCounts}`;
            titleInput.name = `title_asociated${optionCounts}`;
            titleInput.value = option.question_option;
            titleInput.placeholder = "Escribe aqui la opción de esta pregunta";
            titleInput.required = true;
            titleCell.appendChild(titleInput);
            // statusbtnCreateAssociatedQuestion(titleInput);

            // Create a new cell (td) for the option span
            const optionCell = document.createElement('td');
            const optionQuestion = document.createElement('span');
            optionQuestion.className = option.is_correct === 1 ? 'badge badge-success' : 'badge badge-danger';
            optionQuestion.id = `option_asociated${optionCounts}`;
            optionQuestion.textContent = option.is_correct === 1 ? 'VERDADERA' : 'FALSA';
            optionQuestion.style.cursor = 'pointer';
            optionQuestion.title = "Cambiar estado de la opción";
            optionQuestion.onclick = function() { toggleAssociatedOptionStatus(optionQuestion); };
            optionCell.appendChild(optionQuestion);

            // Create a new cell (td) for the percentage span
            const percentageCell = document.createElement('td');
            const percentageSpan = document.createElement('span');
            percentageSpan.id = `percentage_asociated${optionCounts}`;
            percentageSpan.title = "Porcentaje que vale esta opción";
            percentageSpan.style.cursor = 'pointer';
            percentageSpan.textContent = option.percentage_value + '%';
            percentageCell.appendChild(percentageSpan);

            // Create a new cell (td) for the remove button
            const actionsCell = document.createElement('td');
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn btn-danger btn-sm';
            removeButton.textContent = 'X';
            removeButton.title = 'Eliminar Opción';
            removeButton.onclick = function() { removeAsocitedOption(optionCounts); };
            actionsCell.appendChild(removeButton);

            // Append cells to the new row
            newOptionRow.appendChild(titleCell);
            newOptionRow.appendChild(optionCell);
            newOptionRow.appendChild(percentageCell);
            newOptionRow.appendChild(actionsCell);

            // Append the new row to the table body
            document.getElementById('trOptionsAsociated').appendChild(newOptionRow);
            updatePercentagesQuestions();
        });

        // Muestra el modal
        $('#modalEditQuestion').modal('show');
    } else {
        console.error('options no es un array:', options);
        Swal.fire({
            title: 'Error en los datos recibidos',
            icon: 'error',
        });
    }
}


function addOptionAsociated() {

    optionCounts++;
    // console.log("Contador "+optionCounts);

    // Mostrar la tabla si no está visible
    if (!isTableOptionsVisible) {
        document.getElementById('optionsAsociatedTable').style.display = 'table';
        isTableOptionsVisible = true;
    }

    // Create a new row (tr) element
    const newOptionRow = document.createElement('tr');
    newOptionRow.id = `option_asociated_row_${optionCounts}`;

    // Create a new cell (td) for the title input
    const titleCell = document.createElement('td');
    const titleInput = document.createElement('input');
    titleInput.type = 'text';
    titleInput.className = 'form-control-plaintext';
    titleInput.id = `title_asociated${optionCounts}`;
    titleInput.name = `title_asociated${optionCounts}`;
    titleInput.placeholder = "Escribe aqui la opción de esta pregunta";
    titleInput.required = true;
    titleCell.appendChild(titleInput);

    // Crear una celda (td) para la opción
    const optionCell = document.createElement('td');
    const optionQuestion = document.createElement('span');
    optionQuestion.className = 'badge badge-success';
    optionQuestion.id = `option_asociated${optionCounts}`;
    optionQuestion.textContent = 'VERDADERA';
    optionQuestion.style.cursor = 'pointer';
    optionQuestion.title = "Cambiar estado de la opción";
    optionQuestion.onclick = function() { toggleAssociatedOptionStatus(optionQuestion); };
    optionCell.appendChild(optionQuestion);


    // Create a new cell (td) for the percentage sapn
    const percentageCell = document.createElement('td');
    const percentageSpan = document.createElement('span');
    percentageSpan.id = `percentage_asociated${optionCounts}`;
    percentageSpan.title = "Porcentaje que vale esta opción";
    percentageSpan.style.cursor = 'pointer';
    percentageCell.appendChild(percentageSpan);


    // Create a new cell (td) for the remove button
    const actionsCell = document.createElement('td');
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.className = 'btn btn-danger btn-sm';
    removeButton.textContent = 'X';
    removeButton.title = 'Eliminar Opción';
    removeButton.onclick = function() { removeAsocitedOption(optionCounts); };
    actionsCell.appendChild(removeButton);

    // Append cells to the new rowpercentageCell
    newOptionRow.appendChild(titleCell);
    newOptionRow.appendChild(optionCell);
    newOptionRow.appendChild(percentageCell);
    newOptionRow.appendChild(actionsCell);

    // Append the new row to the table body
    document.getElementById('trOptionsAsociated').appendChild(newOptionRow)

    updatePercentagesQuestions();
}

function statusbtnCreateAssociatedQuestion(titleInput){
    const btnCreateAssociatedQuestion= document.getElementById('btnCreateAssociatedQuestion');
    titleInput.addEventListener('input', function() {
        if (titleInput.value.trim() !== '') {
            btnCreateAssociatedQuestion.removeAttribute('disabled');
            console.log('se ejecuta3')
        } else {
            btnCreateAssociatedQuestion.setAttribute('disabled', 'disabled');
            console.log('se ejecuta4')
        }
    });
}
//Method to calculate the porcentage of a option asociated
function updatePercentagesQuestions() {
    const rowsAsociated = document.querySelectorAll('#trOptionsAsociated tr');
    const trueRows = Array.from(rowsAsociated).filter(row => {
        const optionSpan = row.querySelector('span[id^="option_asociated"]');
        return optionSpan && optionSpan.textContent === 'VERDADERA';
    });

    const valuePercentage = (100 / trueRows.length); // Calcula el nuevo porcentaje basado solo en opciones VERDADERAS

    rowsAsociated.forEach((row) => {
        const percentageSpan = row.querySelector('span[id^="percentage_asociated"]');
        const optionSpan = row.querySelector('span[id^="option_asociated"]');
        if (percentageSpan) {
            if (optionSpan && optionSpan.textContent === 'VERDADERA') {
                percentageSpan.textContent = `${valuePercentage}%`;
            } else {
                percentageSpan.textContent = `0%`; // Establecer porcentaje en 0 para opciones FALSAS
            }
        }
    });
}

function removeAsocitedOption(optionId) {
    optionCounts--;
    const rowToRemove = document.getElementById(`option_asociated_row_${optionId}`);
    if (rowToRemove) {
        rowToRemove.parentNode.removeChild(rowToRemove);

        // Ocultar la tabla si no hay más filas
        if (document.getElementById('trOptionsAsociated').childElementCount === 0) {
            document.getElementById('optionsAsociatedTable').style.display = 'none';
            isTableOptionsVisible = false;
        } else {
            // Actualizar los porcentajes de todas las filas
            updatePercentagesQuestions();
        }
    }
}

//Method to change of option the question to TRUE or FALSE
function toggleAssociatedOptionStatus(optionQuestion) {
    if (optionQuestion.textContent === 'VERDADERA') {
        optionQuestion.textContent = 'FALSA';
        optionQuestion.className = 'badge badge-danger';
    } else {
        optionQuestion.textContent = 'VERDADERA';
        optionQuestion.className = 'badge badge-success';
    }
    updatePercentagesQuestions();
}





function updateQuestion(){

    const idQuestionEdit = document.getElementById('idQuestionEdit').value;
    const question_title = document.getElementById('title_asociatedquestion_edit').value;
    const status = document.getElementById('question_status_edit').value;
    // console.log('id '+id);
    // console.log('title '+question_title);
    // console.log('status '+status);
    status === "INACTIVA" ? status == 0:  status == 1;

    console.log('Id '+idQuestionEdit);

    let data = {
        id : idQuestionEdit,
        question_title: question_title,
        status: status,
    }

    fetch(`/admin/pregunta/update/${idQuestionEdit}`,{
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
                title: "Succes Fail",
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
    // console.log('Token '+Tokencsrf);

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
    const idBankEvaluationAsociated = document.getElementById('idBankEvaluationAsociated').value;
    let data = {
        evaluation_id: idBankEvaluationAsociated,
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


