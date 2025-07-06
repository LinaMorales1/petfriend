$('#registerForm').on('submit', function(e) {
        e.preventDefault();

        const $mensaje = $('#mensaje');
        const pass = $('input[name="contrasena"]').val();
        const confirm = $('#confirmar').val();

        if (pass !== confirm) {
            $mensaje.text('Las contraseñas no coinciden.').addClass('text-danger');
            return;
        }

        $mensaje.text('Cargando...').removeClass('text-danger text-success').addClass('text-black');

        $.ajax({
            url: '/petfriend/public/auth/createUser',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                $mensaje.text(res.message).addClass(res.success ? 'text-success' : 'text-danger');
                if (res.success) {
                    $('#registerForm')[0].reset();
                    setTimeout(() => {
                        window.location.href = '/petfriend/public/auth/login';
                    }, 2000);
                }
            },
            error: function() {
                $mensaje.text('Error en el servidor.').addClass('text-danger');
            }
        });
    });