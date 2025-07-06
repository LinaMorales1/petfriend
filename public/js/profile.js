$('#formFoto').on('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        $('#mensajeFoto').text('Subiendo imagen...');

        try {
            const response = await $.ajax({
                url: '/petfriend/public/user/updatePhoto',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
            });

            // Extraer nombre del archivo del backend
            if (typeof response === 'string' && response.startsWith('ok:')) {
                const nombreArchivo = response.split(':')[1];
                const nuevaRuta = `/petfriend/public/uploads/perfiles/${nombreArchivo}`;

                // Reemplazar imagen actual (evita cache agregando timestamp)
                $('#fotoPerfil').attr('src', nuevaRuta + '?t=' + new Date().getTime());

                $('#mensajeFoto').text('Foto actualizada correctamente.');
                $('#formFoto')[0].reset();
            } else {
                $('#mensajeFoto').text(response).addClass('text-danger');
            }
        } catch (err) {
            console.error(err);
            $('#mensajeFoto').text('Error al subir la foto.').addClass('text-danger');
        }
    });