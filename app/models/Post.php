<?php

// Modelo que gestiona las operaciones sobre la tabla "publicaciones"
class Post extends Model
{
    // ===================== PUBLICACIONES =====================

    public function getByUser($userId, $status = null, $excludeStatus = null)
    {
        if ($status) {
            $stmt = $this->getDB()->prepare("
                SELECT * FROM publicaciones
                WHERE usuario_id = ? AND estado = ?
                ORDER BY fecha DESC
            ");
            $stmt->execute([$userId, $status]);
        } elseif ($excludeStatus) {
            $stmt = $this->getDB()->prepare("
                SELECT * FROM publicaciones
                WHERE usuario_id = ? AND estado != ?
                ORDER BY fecha DESC
            ");
            $stmt->execute([$userId, $excludeStatus]);
        } else {
            $stmt = $this->getDB()->prepare("
                SELECT * FROM publicaciones
                WHERE usuario_id = ?
                ORDER BY fecha DESC
            ");
            $stmt->execute([$userId]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $stmt = $this->getDB()->query("SELECT p.*, u.NOMBRES, u.APELLIDOS, u.CIUDAD FROM publicaciones p JOIN usuarios u ON p.usuario_id = u.ID_USUARIO ORDER BY p.fecha DESC");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $db = $this->getDB();

        $stmt = $db->prepare("
            INSERT INTO publicaciones (usuario_id, titulo, contenido, ciudad, imagen, mascota_id)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['usuario_id'],
            $data['titulo'],
            $data['contenido'],
            $data['ciudad'],
            $data['imagen'],
            $data['mascota_id']

        ]);

        $data['id'] = $db->lastInsertId();

        return $data;
    }

    public function update($id, $data)
    {
        $stmt = $this->getDB()->prepare("
            UPDATE publicaciones
            SET titulo = ?, contenido = ?, imagen = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['titulo'],
            $data['contenido'],
            $data['imagen'],
            $id
        ]);

        return $stmt->rowCount();
    }

    public function getByUserAndEstado($userId, $estado)
    {
        $stmt = $this->getDB()->prepare("SELECT * FROM publicaciones WHERE usuario_id = ? AND estado = ? ORDER BY fecha DESC");
        $stmt->execute([$userId, $estado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarCompletadas()
    {
        $this->getDB()->query("DELETE FROM publicaciones WHERE estado = 'COMPLETADA'");
    }

    public function updateEstado($id, $estado)
    {
        $stmt = $this->getDB()->prepare("UPDATE publicaciones SET estado = ? WHERE id = ?");
        $stmt->execute([$estado, $id]);
    }

    public function getAllWithUser()
    {
        $sql = "SELECT p.*, u.NOMBRES, u.APELLIDOS, u.CIUDAD
            FROM publicaciones p
            JOIN usuarios u ON p.usuario_id = u.ID_USUARIO
            ORDER BY p.id DESC";

        return $this->getDB()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function actualizarEstado($id, $estado)
    {
        $stmt = $this->getDB()->prepare("UPDATE publicaciones SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }

    public function getById($id)
    {
        $stmt = $this->getDB()->prepare("SELECT * FROM publicaciones WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateEstadoByMascota($mascotaId, $estado)
    {
        $stmt = $this->getDB()->prepare("UPDATE publicaciones SET estado = ? WHERE mascota_id = ?");
        return $stmt->execute([$estado, $mascotaId]);
    }

    // ===================== COMENTARIOS =====================

    public function guardarComentario($usuarioId, $publicacionId, $contenido)
    {
        $sql = "INSERT INTO comentarios (usuario_id, publicacion_id, contenido) VALUES (:usuario_id, :publicacion_id, :contenido)";
        $stmt = $this->getDB()->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId);
        $stmt->bindParam(':publicacion_id', $publicacionId);
        $stmt->bindParam(':contenido', $contenido);
        return $stmt->execute();
    }

    public function obtenerComentarios($publicacionId)
    {
        $sql = "SELECT c.*, u.NOMBRES 
        FROM comentarios c
        JOIN usuarios u ON c.usuario_id = u.ID_USUARIO
        WHERE c.publicacion_id = :publicacion_id
        ORDER BY c.id ASC"; // 👈 corregido

        $stmt = $this->getDB()->prepare($sql);
        $stmt->bindParam(':publicacion_id', $publicacionId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarComentarios($publicacionId)
    {
        $stmt = $this->getDB()->prepare("SELECT COUNT(*) FROM comentarios WHERE publicacion_id = ?");
        $stmt->execute([$publicacionId]);
        return $stmt->fetchColumn();
    }

    // ===================== REACCIONES (LIKES) =====================

    public function darLike($usuarioId, $publicacionId)
    {
        $sql = "INSERT INTO reacciones (usuario_id, publicacion_id) VALUES (:usuario_id, :publicacion_id)";
        $stmt = $this->getDB()->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId);
        $stmt->bindParam(':publicacion_id', $publicacionId);
        return $stmt->execute();
    }

    public function quitarLike($usuarioId, $publicacionId)
    {
        $sql = "DELETE FROM reacciones WHERE usuario_id = :usuario_id AND publicacion_id = :publicacion_id";
        $stmt = $this->getDB()->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId);
        $stmt->bindParam(':publicacion_id', $publicacionId);
        return $stmt->execute();
    }

    public function usuarioDioLike($usuarioId, $publicacionId)
    {
        $sql = "SELECT COUNT(*) FROM reacciones WHERE usuario_id = :usuario_id AND publicacion_id = :publicacion_id";
        $stmt = $this->getDB()->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId);
        $stmt->bindParam(':publicacion_id', $publicacionId);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function contarLikes($publicacionId)
    {
        $sql = "SELECT COUNT(*) FROM reacciones WHERE publicacion_id = :publicacion_id";
        $stmt = $this->getDB()->prepare($sql);
        $stmt->bindParam(':publicacion_id', $publicacionId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function actualizar($data)
{
    $params = [
        'titulo' => $data['titulo'],
        'contenido' => $data['contenido'],
        'id' => $data['id']
    ];

    $sql = "UPDATE publicaciones SET 
                titulo = :titulo,
                contenido = :contenido";

    if (!empty($data['imagen'])) {
        $sql .= ", imagen = :imagen";
        $params['imagen'] = $data['imagen'];
    }

    $sql .= " WHERE id = :id";

    $stmt = $this->getDB()->prepare($sql);
    $stmt->execute($params);
}

}
