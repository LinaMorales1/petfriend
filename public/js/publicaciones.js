$("#Formulario").on("submit", async function (e) {
  e.preventDefault();

  const form = $(this)[0];
  const formData = new FormData(form);

  $("#mensaje").html(`
    <div class="alert alert-info" role="alert">
      ⏳ Publicando...
    </div>
  `);

  try {
    const response = await $.ajax({
      url: "/petfriend/public/user/create",
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
    });

    if (response.success) {
      $("#mensaje").html(`
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          ✅ ${response.message || "¡Publicación creada con éxito!"}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
      `);

      // Limpia el formulario
      $("#Formulario")[0].reset();

      // Redirige opcionalmente después de 2 segundos
      setTimeout(() => {
        window.location.href = "/petfriend/public/user/estado";
      }, 2000);

    } else {
      $("#mensaje").html(`
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          ❌ ${response.message || "Ocurrió un error inesperado."}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
      `);
    }
  } catch (error) {
    console.error(error);
    $("#mensaje").html(`
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        ❌ Error inesperado: ${error.responseText || error.message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
      </div>
    `);
  }
});
