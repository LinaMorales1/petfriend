// Espera que el formulario con id #registerForm sea enviado
$("#registerForm").on("submit", function (e) {
  e.preventDefault(); // Previene que se recargue la página al enviar el formulario

  const $mensaje = $("#mensaje"); // Contenedor para mostrar mensajes de error o éxito
  const pass = $('input[name="contrasena"]').val(); // Obtiene la contraseña escrita
  const confirm = $("#confirmar").val(); // Obtiene la confirmación de contraseña

  // Validación: asegurarse que ambas contraseñas coincidan
  if (pass !== confirm) {
    $mensaje.text("Las contraseñas no coinciden.").addClass("text-danger");
    return; // Detiene el proceso si no coinciden
  }

  // Muestra mensaje de carga mientras se realiza la petición
  $mensaje
    .text("Cargando...")
    .removeClass("text-danger text-success")
    .addClass("text-black");

  // Envío del formulario por AJAX
  $.ajax({
    url: "/petfriend/public/auth/createUser", // Ruta del controlador que procesa el registro
    method: "POST", // Método HTTP
    data: $(this).serialize(), // Serializa todos los campos del formulario
    dataType: "json", // Se espera una respuesta en formato JSON

    // Si la respuesta fue exitosa
    success: function (res) {
      // Muestra el mensaje devuelto por el backend
      $mensaje
        .text(res.message)
        .addClass(res.success ? "text-success" : "text-danger");

      // Si fue un éxito, limpia el formulario y redirige al login después de 2 segundos
      if (res.success) {
        $("#registerForm")[0].reset();
        setTimeout(() => {
          window.location.href = "/petfriend/public/auth/login";
        }, 2000);
      }
    },

    // Si ocurrió un error en el servidor (por ejemplo, 500)
    error: function () {
      $mensaje.text("Error en el servidor.").addClass("text-danger");
    },
  });
});
