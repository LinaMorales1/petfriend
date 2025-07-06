$(document).ready(function() {
                $('.form-estado-publicacion').on('submit', function(e) {
                    e.preventDefault();

                    const form = $(this);
                    const id = form.data('id');
                    const estado = form.find('select[name="estado"]').val();

                    $.ajax({
                        url: '/petfriend/public/admin/actualizarEstadoPublicacion',
                        type: 'POST',
                        data: {
                            publicacion_id: id,
                            estado: estado
                        },
                        success: function() {
                            const alerta = $('#alerta-estado');
                            alerta.removeClass('d-none');
                            setTimeout(() => alerta.addClass('d-none'), 3000);
                        },
                        error: function() {
                            alert('❌ Error al actualizar el estado');
                        }
                    });
                });
            });