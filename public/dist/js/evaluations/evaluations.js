
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const btnCreateEvalation = document.getElementById('btnCreateEvaluation');
const btnEditEvaluation = document.getElementById('btnEditEvaluation');
const headersFetch = {
    "Content-Type": "application/json",
    "Accept": "application/json, text-plain, */*",
    "X-Requested-With": "XMLHttpRequest",
    "X-CSRF-TOKEN": csrfToken
};

let formCreateEvaluation = document.getElementById("createEvaluationForm");
if(formCreateEvaluation)
{
    formCreateEvaluation.addEventListener('submit', function(event) {
        event.preventDefault();
        btnCreateEvalation.setAttribute('disabled','disabled');
        createEvaluation();
    });
}

// Method to create a new evaluation
function createEvaluation(){
    let titleEvaluation = document.getElementById('titleEvaluation').value;
    let descriptionEvaluation = document.getElementById('descriptionEvaluation').value;
    let startDate = document.getElementById('startDate').value;
    let endDate = document.getElementById('endDate').value;

    let data = {
        _token: csrfToken,
        title: titleEvaluation,
        description: descriptionEvaluation,
        start_date: startDate,
        end_date: endDate,
    };

    fetch(`/admin/evaluacion/store`,{
        method : 'POST',
        headers : headersFetch,
        body :JSON.stringify(data)
    })
    .then(response => response.json())
    .then(response =>{
        if(response.status === 'success') {
            Swal.fire({
                title: response.message,
                icon: 'success',
                confirmButtonText: "Aceptar",
            }).then(() => {
                $('#mdlCreateEvalution').modal('hide');
                $('#evaluations').DataTable().ajax.reload();
            });
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
            title: "Hubo un error al crear la evaluación",
            icon: "error",
        });
    })
    .finally(() =>{
        btnCreateEvalation.removeAttribute('disabled');
    });
}

// Method for clear the fields when you close the modal to create an evaluation
$('#mdlCreateEvalution').on('hidden.bs.modal', function (event) {
    document.getElementById('titleEvaluation').value = '';
    document.getElementById('descriptionEvaluation').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
});

//Method to open the modal and be able to edit the evaluation data.
function openEditEvaluationModal(id) {

    fetch(`/admin/evaluacion/${id}`)
    .then(response => response.json())
    .then(response => {
        if (response.status === 'success') {
            // Llenar los campos del modal con los datos de la evaluación
            $('#idEvaluation').val(response.data.id);
            $('#editEvaluationTitle').val(response.data.title);
            $('#editEvaluationDescription').val(response.data.description);
            $('#editEvaluationStarDate').val(response.data.start_date);
            $('#editEvaluationEndDate').val(response.data.end_date);
            $('#editEvaluationStatus').val(response.data.status);

            // Limpiar el select antes de agregar opciones
            $('#editEvaluationStatus').empty();

            // Agregar la opción actual
            if (response.data.status == 0) {
                $('#editEvaluationStatus').append(new Option("INACTIVA", "0", true, true));
                $('#editEvaluationStatus').append(new Option("ACTIVA", "1"));
            } else {
                $('#editEvaluationStatus').append(new Option("ACTIVA", "1", true, true));
                $('#editEvaluationStatus').append(new Option("INACTIVA", "0"));
            }
            // Abrir el modal
            $('#mdlEditEvalution').modal('show');
        } else {
            Swal.fire({
                title: response.message,
                icon: "error"
            });
        }

    })
    .catch(error => {
        // console.log(error);
        Swal.fire({
            title: "Hubo un error al obtener los datos de la evaluación",
            icon: "error"
        });
    })
    .finally(() => {

    })
}

// Method for clear the fields when you close the modal to create an evaluation
$('#mdlEditEvalution').on('hidden.bs.modal', function (event) {
    idEvaluation.value = '';
    editEvaluationTitle.value = '';
    editEvaluationDescription.value = '';
    editEvaluationStarDate.value = '';
    editEvaluationEndDate.value = '';
    editEvaluationStatus.value = '';
});

//Method to update the evaluation


let FormEditEvaluation = document.getElementById("editEvaluationForm");
if(FormEditEvaluation)
{
    FormEditEvaluation.addEventListener('submit', function(event) {
        event.preventDefault();
        btnEditEvaluation.setAttribute('disabled','disabled');
        updateEvalution();[]
    });
}

function updateEvalution(){

    const idEvaluation = document.getElementById('idEvaluation').value;
    const editEvaluationTitle = document.getElementById('editEvaluationTitle').value;
    const editEvaluationDescription = document.getElementById('editEvaluationDescription').value;
    const editEvaluationStarDate = document.getElementById('editEvaluationStarDate').value;
    const editEvaluationEndDate = document.getElementById('editEvaluationEndDate').value;
    const editEvaluationStatus = document.getElementById('editEvaluationStatus').value;

    editEvaluationStatus === "INACTIVA" ? editEvaluationStatus == 0:  editEvaluationStatus == 1;
    let data = {
        title: editEvaluationTitle,
        description: editEvaluationDescription ,
        start_date: editEvaluationStarDate,
        end_date: editEvaluationEndDate,
        status: editEvaluationStatus,
    };

    fetch(`/admin/evaluacion/update/${idEvaluation}`, {
        method: 'PATCH',
        headers: headersFetch,
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(response => {

        if (response.status === 'success') {
            Swal.fire({

                title: response.message,
                icon: 'success',
                confirmButtonText: "Aceptar",

            }).then(() => {
                // Cerrar el modal
                $('#mdlEditEvalution').modal('hide');
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

    })
    .catch(error => {
        // console.log(error);
        Swal.fire({
            title: "Hubo un error al obtener los datos de la evaluación",
            icon: "error"
        });
    })
    .finally(() => {
        console.log("Finalizo la ejecución");
        btnEditEvaluation.removeAttribute('disabled');

    })
}







