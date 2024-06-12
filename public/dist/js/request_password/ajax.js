function ResetPassword(id) {
    Swal.fire({
        title: "Deseas solicitar el restablecimiento de contraseña?",
        showDenyButton: true,
        confirmButtonText: "Solicitar",
        denyButtonText: `Cancelar`
    }).then((result) => {
        if (result.isConfirmed) {
            // Configurar la solicitud fetch
            fetch(`/admin/solicitar/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    'id': id
                })
            })
            .then(response => {
                console.log('Raw response:', response);
                return response.json(); 
            })
            .then(data => {
                if (!data.status == 'error') {
                    throw new Error(data.message);
                }
                Swal.fire({

                    title: data.message,
                    icon: 'success',
                    didClose: () => {
                        // Recargar la página si la actualización fue exitosa
                        location.reload();
                    }
                });
            })
            .catch(error => {
                // Manejar errores
                console.error('Error:', error);
                Swal.fire({
                    title: "Error al realizar la solicitud",
                    text: error.message,
                    icon: "error",
                    willClose: () => {
                        // Recargar la página cuando el cuadro de diálogo se cierra
                        location.reload();
                    }
                });
            });
        } else if (result.isDenied) {
            Swal.fire("No se ha realizado la solicitud", "", "info");
        }
    });
}
