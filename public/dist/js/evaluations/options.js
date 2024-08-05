let optionCount = 0;
let isTableVisible = false;
// Method to add a new option
function addOption() {
    optionCount++;
    // console.log("Contador "+optionCount);

    // Mostrar la tabla si no está visible
    if (!isTableVisible) {
        document.getElementById('optionsTable').style.display = 'table';
        isTableVisible = true;
    }

    // Create a new row (tr) element
    const newOptionRow = document.createElement('tr');
    newOptionRow.id = `option_row_${optionCount}`;

    // Create a new cell (td) for the title input
    const titleCell = document.createElement('td');
    const titleInput = document.createElement('input');
    titleInput.type = 'text';
    titleInput.className = 'form-control-plaintext';
    titleInput.id = `title_${optionCount}`;
    titleInput.name = `title_${optionCount}`;
    titleInput.placeholder = "Escribe aqui la opción de esta pregunta";
    titleInput.required = true;
    titleCell.appendChild(titleInput);


    // Crear una celda (td) para la opción
    const optionCell = document.createElement('td');
    const optionQuestion = document.createElement('span');
    optionQuestion.className = 'badge badge-success';
    optionQuestion.id = `option_${optionCount}`;
    optionQuestion.textContent = 'VERDADERA';
    optionQuestion.style.cursor = 'pointer';
    optionQuestion.title = "Cambiar estado de la opción";
    optionQuestion.onclick = function() { toggleOptionStatus(optionQuestion); };
    optionCell.appendChild(optionQuestion);


    // Create a new cell (td) for the percentage sapn
    const percentageCell = document.createElement('td');
    const percentageSpan = document.createElement('span');
    percentageSpan.id = `percentage_${optionCount}`;
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
    removeButton.onclick = function() { removeOption(optionCount); };
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

//Method to calculate the porcentage of a option
function updatePercentages() {
    const rows = document.querySelectorAll('#trOptions tr');
    const trueRows = Array.from(rows).filter(row => {
        const optionSpan = row.querySelector('span[id^="option_"]');
        return optionSpan && optionSpan.textContent === 'VERDADERA';
    });

    const valuePercentage = (100 / trueRows.length); // Calcula el nuevo porcentaje basado solo en opciones VERDADERAS

    rows.forEach((row) => {
        const percentageSpan = row.querySelector('span[id^="percentage_"]');
        const optionSpan = row.querySelector('span[id^="option_"]');
        if (percentageSpan) {
            if (optionSpan && optionSpan.textContent === 'VERDADERA') {
                percentageSpan.textContent = `${valuePercentage}%`;
            } else {
                percentageSpan.textContent = `0%`; // Establecer porcentaje en 0 para opciones FALSAS
            }
        }
    });
}

// Method to remove  a new option

function removeOption(optionId) {
    optionCount--;
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
                // Crear los elementos para cada opción
                response.data.forEach(option => {
                    // Crear un div para la opción
                    const optionDiv = $('<div class="form-group"></div>');
                    // Crear el input para la opción
                    const inputOptions = $('<input type="text" readonly class="form-control">');
                    // Establecer el valor del input
                    inputOptions.val(option.question_option);

                    if (option.is_correct === 1) {
                        inputOptions.addClass('text-success');
                    } else {
                        inputOptions.addClass('text-danger');
                    }
                    // Añadir el input al div
                    optionDiv.append(inputOptions);
                    // Añadir el div al contenedor
                    $('#optionsContainer').append(optionDiv);
                });
            } else {
                console.error('Expected an array but received:', response.data);
                Swal.fire({
                    title: "Datos no válidos recibidos",
                    icon: "error"
                });
                console.log('E1');
            }
            // Abrir el modal
            $('#mdlviewOptions').modal('show');
        } else {
            Swal.fire({
                title: response.message,
                icon: "error"
            });
            console.log('E2');
        }
    })
    .catch((err) => {
        Swal.fire({
            title: "Hubo un error al obtener las opciones de esta pregunta",
            icon: "error"
        });
        console.log('E3');
    });
}
