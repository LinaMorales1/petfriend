$(document).ready(function () {
  // AJAX para actualizar estado (admin)
  $(".form-estado-publicacion").on("submit", function (e) {
    e.preventDefault();

    const form = $(this);
    const id = form.data("id");
    const estado = form.find('select[name="estado"]').val();

    $.ajax({
      url: "/petfriend/public/admin/actualizarEstadoPublicacion",
      type: "POST",
      data: {
        publicacion_id: id,
        estado: estado,
      },
      success: function () {
        const alerta = $("#alerta-estado");
        alerta.removeClass("d-none");
        setTimeout(() => alerta.addClass("d-none"), 3000);
      },
      error: function () {
        alert("❌ Error al actualizar el estado");
      },
    });
  });

  // AJAX para "Me gusta"
  $(document).on("click", ".btn-like", async function (e) {
    e.preventDefault();
    const btn = $(this);
    const publicacionId = btn.data("id");

    try {
      const res = await $.ajax({
        url: "/petfriend/public/user/likeAjax",
        method: "POST",
        data: { publicacion_id: publicacionId },
        dataType: "json",
      });

      if (res.success) {
        btn.find(".like-count").text(res.likes);
      }
    } catch (error) {
      console.error("Error al dar like:", error);
    }
  });

  // AJAX para comentarios
  $(document).on("submit", ".form-comentario", async function (e) {
    e.preventDefault();
    const form = $(this);
    const publicacionId = form.find('input[name="publicacion_id"]').val();
    const comentario = form.find('input[name="comentario"]').val().trim();

    if (!comentario) return;

    try {
      const res = await $.ajax({
        url: "/petfriend/public/user/comentarAjax",
        method: "POST",
        data: { publicacion_id: publicacionId, comentario },
        dataType: "json",
      });

      if (res.success && res.comentarios) {
        let html = "";
        res.comentarios.forEach((c) => {
          html += `<p><strong>${c.NOMBRES}:</strong> ${c.contenido}</p>`;
        });

        // Reemplazar comentarios
        form.prev(".comentarios").html(html);

        // Limpiar input
        form.find('input[name="comentario"]').val("");
      }
    } catch (err) {
      console.error("Error al comentar:", err);
    }
  });
});
