let optionCount = 0;
let isTableVisible = false;


// Method to add a new option
function addOption() {
    optionCount++;
    const uniqueOptionId = `${Date.now()}_${optionCount}`;

    // Mostrar la tabla si no está visible
    if (!isTableVisible) {
        document.getElementById('optionsTable').style.display = 'table';
        isTableVisible = true;
    }

    // Create a new row (tr) element
    const newOptionRow = document.createElement('tr');
    newOptionRow.id = `option_row_${uniqueOptionId}`;

    // Create a new cell (td) for the title input
    const titleCell = document.createElement('td');
    const titleInput = document.createElement('input');
    titleInput.type = 'text';
    titleInput.className = 'form-control-plaintext';
    titleInput.id = `title_${uniqueOptionId}`;
    titleInput.name = `title_${uniqueOptionId}`;
    titleInput.placeholder = "Escribe aqui la opción de esta pregunta";
    titleInput.required = true;
    titleCell.appendChild(titleInput);
    statusButtonCreateEvaluation(titleInput);

    // Crear una celda (td) para la opción
    const optionCell = document.createElement('td');
    const optionQuestion = document.createElement('span');
    optionQuestion.className = 'badge badge-success';
    optionQuestion.id = `option_${uniqueOptionId}`;
    optionQuestion.textContent = 'VERDADERA';
    optionQuestion.style.cursor = 'pointer';
    optionQuestion.title = "Cambiar estado de la opción";
    optionQuestion.onclick = function() { toggleOptionStatus(optionQuestion); };
    optionCell.appendChild(optionQuestion);


    // Create a new cell (td) for the percentage sapn
    const percentageCell = document.createElement('td');
    const percentageSpan = document.createElement('span');
    percentageSpan.id = `percentage_${uniqueOptionId}`;
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
    removeButton.onclick = function() { removeOption(uniqueOptionId); };
    actionsCell.appendChild(removeButton);

    // Append cells to the new rowpercentageCell
    newOptionRow.appendChild(titleCell);
    newOptionRow.appendChild(optionCell);
    newOptionRow.appendChild(percentageCell);
    newOptionRow.appendChild(actionsCell);

    // Append the new row to the table body
    document.getElementById('trOptions').appendChild(newOptionRow)

    updatePercentages();
}



//Method to disabled button to create or update  a question yes the input its empty
function statusButtonCreateEvaluation(titleInput){
    const btnCreateQuestion= document.getElementById('btnCreateQuestion');
    titleInput.addEventListener('input', function() {
        if (titleInput.value.trim() !== '') {
            btnCreateQuestion.removeAttribute('disabled');
        } else {
            btnCreateQuestion.setAttribute('disabled', 'disabled');
        }
    });

}



//Method to calculate the porcentage of a option
function updatePercentages() {
    const rows = document.querySelectorAll('#trOptions tr');
    const trueRows = Array.from(rows).filter(row => {
        const optionSpan = row.querySelector('span[id^="option_"]');
        return optionSpan && optionSpan.textContent === 'VERDADERA';
    });

    const valuePercentage = (100 / trueRows.length);

    rows.forEach((row) => {
        const percentageSpan = row.querySelector('span[id^="percentage_"]');
        const optionSpan = row.querySelector('span[id^="option_"]');
        if (percentageSpan) {
            if (optionSpan && optionSpan.textContent === 'VERDADERA') {
                percentageSpan.textContent = `${valuePercentage}%`;
            } else {
                percentageSpan.textContent = `0%`;
            }
        }
    });
}

// Method to remove  a new option

function removeOption(optionId) {
    const rowToRemove = document.getElementById(`option_row_${optionId}`);
    if (rowToRemove) {
        rowToRemove.parentNode.removeChild(rowToRemove);

        // Ocultar la tabla si no hay más filas
        if (document.getElementById('trOptions').childElementCount === 0) {
            document.getElementById('optionsTable').style.display = 'none';
            isTableVisible = false;
        } else {
            // Actualizar los porcentajes de todas las filas
            updatePercentages();
        }
    }
}

//Method to change of option the question to TRUE or FALSE
function toggleOptionStatus(optionQuestion) {
    if (optionQuestion.textContent === 'VERDADERA') {
        optionQuestion.textContent = 'FALSA';
        optionQuestion.className = 'badge badge-danger';
    } else {
        optionQuestion.textContent = 'VERDADERA';
        optionQuestion.className = 'badge badge-success';
    }
    updatePercentages();
}

//Method for get the options an question
function seeAQuestionOptions(id,title){
    // console.log('Id '+id);
    // console.log('Titulo de la pregunta '+title);
    fetch(`/admin/opciones/opcionesasociadas/${id}`)
    .then(response => response.json())
    .then((response) => {
        if(response.status === 'success') {

            // Actualizar el valor del input
            document.getElementById('add_question_title').textContent = title;
            // Limpiar el contenedor de opciones
            $('#optionsContainer').empty();

            // Verifica si response.data es un array
            if (Array.isArray(response.data)) {
                const optionsContainer = document.getElementById('optionsContainer');
                if (Array.isArray(response.data) && response.data.length > 0) {
                    response.data.forEach(option => {
                        const optionDiv = document.createElement('div');
                        optionDiv.className = 'form-group';

                        const inputOptions = document.createElement('input');
                        inputOptions.type = 'text';
                        inputOptions.readOnly = true;
                        inputOptions.className = 'form-control';
                        inputOptions.value = option.question_option;

                        if (option.is_correct === 1) {
                            inputOptions.classList.add('text-success');
                        } else {
                            inputOptions.classList.add('text-danger');
                        }

                        optionDiv.appendChild(inputOptions);


                        optionsContainer.appendChild(optionDiv);
                    });
                } else {
                    const noOptionsMessage = document.createElement('p');
                    noOptionsMessage.textContent = 'No hay opciones disponibles para esta pregunta.';
                    noOptionsMessage.className = 'text-center'

                    const divBtnAddQuestions = document.createElement('div');
                    divBtnAddQuestions.className = ' text-center';


                    const btnAddOptions = document.createElement('button');
                    btnAddOptions.className = 'btn btn-sm btn-primary';
                    btnAddOptions.textContent = 'Agregar Opción'
                    divBtnAddQuestions.appendChild(btnAddOptions);

                    optionsContainer.appendChild(noOptionsMessage);
                    optionsContainer.appendChild(divBtnAddQuestions);
                }
            } else {
                Swal.fire({
                    title: "Datos no válidos recibidos",
                    icon: "error"
                });
            }
            // Abrir el modal
            $('#mdlviewOptions').modal('show');
        } else {
            Swal.fire({
                title: response.message,
                icon: "error"
            });
        }
    })
    .catch((err) => {
        Swal.fire({
            title: "Hubo un error al obtener las opciones de esta pregunta",
            icon: "error"
        });
    });
}
