function ResetPassword(id, password_tmp, status, tipo_solicitud) {
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
                    'id': id,
                    'password_tmp': password_tmp,
                    'status': status,
                    'tipo_solicitud': tipo_solicitud
                })
            })
            .then(response => {
                // Manejar la respuesta
                if (!response.ok) {
                    throw new Error('Error en la solicitud');
                }
                return response.json();
            })
            .then(data => {
                // Manejar la respuesta del servidor
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
                Swal.fire("Error al realizar la solicitud", "", "error");
            });
        } else if (result.isDenied) {
            Swal.fire("No se ha realizado la solicitud", "", "info");
        }
    });
}
