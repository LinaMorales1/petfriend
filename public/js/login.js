 $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            const $mensaje = $('#mensaje');
            $mensaje.text('Validando...').addClass('text-black');

            $.ajax({
                url: '/petfriend/public/auth/loginUser',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    $mensaje.text(res.message).addClass(res.success ? 'text-success' : 'text-danger');
                    if (res.success) {
                        setTimeout(() => {
                            window.location.href = '/petfriend/public/home';
                        }, 2000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', error);
                    console.log('Status:', status);
                    console.log('ResponseText:', xhr.responseText)
                    $mensaje.text('Error al procesar solicitud.').addClass('text-danger');
                }
            });
        });