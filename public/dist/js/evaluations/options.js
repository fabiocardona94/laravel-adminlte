
// const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let optionCount = 0;
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

//Method for get the options an
function seeAQuestionOptions(id,title){
    // console.log('Id '+id);
    // console.log('Titulo de la pregunta '+title);

    $.ajax({
        url: '/admin/opciones/opcionesasociadas/' + id ,
        method: 'GET',
        success: function(response) {
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
                }
                // Abrir el modal
                $('#viewOptions').modal('show');
            } else {
                Swal.fire({
                    title: response.message,
                    icon: "error"
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: "Hubo un error al obtener las opciones de esta pregunta",
                icon: "error"
            });
        }
    });

}
