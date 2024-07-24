const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let optionCount = 0;


// -----------------------------------------------------Evaluations--------------------------------------//
// Method to create a new evaluation
function createEvaluation(){

    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;

    $.ajax({
        url: '/admin/evaluacion/store',
        method: 'POST',
        data: {
            _token: csrfToken, 
            title: title,
            description: description,
            start_date: startDate,
            end_date: endDate,
        },
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire({

                    title: response.message,
                    icon: 'success',
                    confirmButtonText: "Aceptar",
                }).then(() => {
                    $('#createEvalution').modal('hide');
                    $('#evaluations').DataTable().ajax.reload();
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


//Method to open the modal and be able to edit the evaluation data.
function openEditEvaluationModal(id) {
    $.ajax({
        url: '/admin/evaluacion/' + id + '',
        method: 'GET',
        success: function(response) {
            if(response.status === 'success') {  
                    // Llenar los campos del modal con los datos de la evaluación
                    $('#evaluation_id').val(response.data.id);
                    $('#title_evaluation').val(response.data.title);
                    $('#description_evaluation').val(response.data.description);
                    $('#start_date_evaluation').val(response.data.start_date);
                    $('#end_date_evaluation').val(response.data.end_date);
                    $('#evaluation_status').val(response.data.status);

                    // Limpiar el select antes de agregar opciones
                    $('#evaluation_status').empty();

                    // Agregar la opción actual
                    if(response.data.status == 0) {
                        $('#evaluation_status').append(new Option("INACTIVA", "0", true, true));
                        $('#evaluation_status').append(new Option("ACTIVA", "1"));
                    } else {
                        $('#evaluation_status').append(new Option("ACTIVA", "1", true, true));
                        $('#evaluation_status').append(new Option("INACTIVA", "0"));
                    }
                    // Abrir el modal
                    $('#editEvalution').modal('show');
            } else {
                Swal.fire({
                    title: response.message,
                    icon: "error"
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: "Hubo un error al obtener los datos de la evaluación",
                icon: "error"
            });
        }
    });
}

//Method to update the evaluation

function updateEvalution(){

    const id = document.getElementById('evaluation_id').value;
    const title = document.getElementById('title_evaluation').value;
    const description = document.getElementById('description_evaluation').value;
    const startDate = document.getElementById('start_date_evaluation').value;
    const endDate = document.getElementById('end_date_evaluation').value;
    const status = document.getElementById('evaluation_status').value;
    status === "INACTIVA" ? status == 0:  status == 1;
    $ .ajax({
        url: '/admin/evaluacion/update/'+id,
        method: 'PATCH',
        data: {
            _token: csrfToken, 
            title: title,
            description: description,
            start_date: startDate,
            end_date: endDate,
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
                    $('#editEvalution').modal('hide');
                    // Recargar la DataTable
                    $('#evaluations').DataTable().ajax.reload();
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



// -----------------------------------------------------Questionss--------------------------------------//


// Method to create a new Question
function createQuestion(){
    const question_title = document.getElementById('question_title').value;

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
                title: "Hubo un error al crear la evaluación",
                icon: "error",
            });
        }
    });
}



// -----------------------------------------------------Options--------------------------------------//


// Method to add a new option
function addOption() {
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
    newInput.required = true;

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
    document.getElementById('divOptions').appendChild(newOptionDiv);
}

// Method to remove  a new option
function removeOption(optionId) {
    optionCount--;
    const optionDiv = document.getElementById(`option_div_${optionId}`);
    optionDiv.remove();
}



