const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let formCreateEvaluation = document.getElementById("createEvaluationForm");
if(formCreateEvaluation)
{
    formCreateEvaluation.addEventListener('submit', function(event) {
        event.preventDefault();
        // btnCreateEvaluation.setAttribute('disabled','disabled');
        createEvaluation();
    });
}
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
        // .finally(() =>{
        //     btnCreateEvaluation.removeAttribute('disabled')
        // }),
    });
}


//Method to open the modal and be able to edit the evaluation data.
function openEditEvaluationModal(id) {
    $.ajax({
        url: '/admin/evaluacion/' + id ,
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


let FormEditEvaluation = document.getElementById("editEvaluationForm");
if(FormEditEvaluation)
{
    FormEditEvaluation.addEventListener('submit', function(event) {
        event.preventDefault();
        updateEvalution();
    });
}

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






