$("#Formulario").on("submit", async function (e) {
  e.preventDefault();

  const form = $(this)[0];
  const formData = new FormData(form);

  $("#mensaje").html('<div class="text-info">Publicando...</div>');

  try {
    const response = await $.ajax({
      url: "/petfriend/public/user/create",
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
    });

    if (response.success) {
      $("#mensaje").html(
        '<div class="text-success">¡Publicación creada!</div>'
      );
      $("#Formulario")[0].reset();
      return;
    }

    $("#mensaje").html(`<div class="text-danger">${res.message}</div>`);
    return;
  } catch (error) {
    console.log(error);
    $("#mensaje").html(`<div class="text-danger">${error.message}</div>`);
  }
});
