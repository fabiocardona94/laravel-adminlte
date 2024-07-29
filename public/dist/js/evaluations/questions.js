// const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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
    // console.log("Token"+csrfToken);


    $.ajax({
        url: '/admin/pregunta/store',
        method: 'POST',
        data: {
            _token: csrfToken,
            question_title: question_title,
            // evaluation_id: evaluation_id,
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

