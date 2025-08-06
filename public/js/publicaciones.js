// 🟩 Publicar nueva publicación
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

      $("#Formulario")[0].reset();

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

// 🟩 Like AJAX
$(document).on("click", ".btn-like", async function (e) {
  e.preventDefault();

  const btn = $(this);
  const publicacionId = btn.data("publicacion-id");

  try {
    const response = await $.post("/petfriend/public/user/likeAjax", {
      publicacion_id: publicacionId,
    });

    if (response.success) {
      btn.toggleClass("btn-primary btn-outline-primary");
      btn.find("i").toggleClass("bi-hand-thumbs-up-fill bi-hand-thumbs-up");
      btn.next(".like-count").text(response.likes);
    } else {
      alert("❌ Error al dar like");
    }
  } catch (err) {
    console.error("Error AJAX like:", err);
  }
});

// 🟩 Comentario AJAX
$(document).on("submit", ".form-comentario", async function (e) {
  e.preventDefault();

  const form = $(this);
  const publicacionId = form.find("input[name='publicacion_id']").val();
  const comentario = form.find("input[name='comentario']").val();
  const contenedorComentarios = form.closest(".card").find(".comentarios");

  if (!comentario.trim()) return;

  try {
    const response = await $.post("/petfriend/public/user/comentarAjax", {
      publicacion_id: publicacionId,
      comentario: comentario,
    });

    if (response.success && response.comentarios) {
      form[0].reset();
      contenedorComentarios.empty();

      response.comentarios.forEach((coment) => {
        contenedorComentarios.append(`
          <div class="comentario mb-1">
            <strong>${coment.NOMBRES}</strong>: ${coment.contenido}
          </div>
        `);
      });
    } else {
      alert("❌ Error al comentar");
    }
  } catch (err) {
    console.error("Error AJAX comentario:", err);
  }
});
