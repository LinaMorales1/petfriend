<?php

// Modelo que gestiona las operaciones sobre la tabla "publicaciones"
class Post extends Model
{
    // Obtiene publicaciones de un usuario por estado específico, excluyendo un estado, o sin filtro de estado
    public function getByUser($userId, $status = null, $excludeStatus = null)
    {
        if ($status) {
            // Si se pasa un estado, filtra solo por ese estado
            $stmt = $this->getDB()->prepare("
                SELECT * FROM publicaciones
                WHERE usuario_id = ? AND estado = ?
                ORDER BY fecha DESC
            ");
            $stmt->execute([$userId, $status]);
        } elseif ($excludeStatus) {
            // Si se indica un estado a excluir, trae todos menos ese
            $stmt = $this->getDB()->prepare("
                SELECT * FROM publicaciones
                WHERE usuario_id = ? AND estado != ?
                ORDER BY fecha DESC
            ");
            $stmt->execute([$userId, $excludeStatus]);
        } else {
            // Si no se filtra, trae todas las publicaciones del usuario
            $stmt = $this->getDB()->prepare("
                SELECT * FROM publicaciones
                WHERE usuario_id = ?
                ORDER BY fecha DESC
            ");
            $stmt->execute([$userId]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene todas las publicaciones con nombre del autor
    public function getAll()
    {
        $stmt = $this->getDB()->query("SELECT p.*, u.NOMBRES, u.APELLIDOS FROM publicaciones p JOIN usuarios u ON p.usuario_id = u.ID_USUARIO ORDER BY p.fecha DESC");
        return $stmt->fetchAll();
    }

    // Crea una nueva publicación en la base de datos
    public function create($data)
    {
        $db = $this->getDB();

        $stmt = $db->prepare("
            INSERT INTO publicaciones (usuario_id, titulo, contenido, imagen)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['usuario_id'],
            $data['titulo'],
            $data['contenido'],
            $data['imagen'],
        ]);

        // Retorna los datos de la publicación incluyendo su ID generado
        $data['id'] = $db->lastInsertId();
        return $data;
    }

    // Actualiza título, contenido e imagen de una publicación existente
    public function update($id, $data)
    {
        $db = $this->getDB();

        $stmt = $db->prepare("
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

    // Obtiene publicaciones de un usuario por un estado específico
    public function getByUserAndEstado($userId, $estado)
    {
        $stmt = $this->getDB()->prepare("SELECT * FROM publicaciones WHERE usuario_id = ? AND estado = ? ORDER BY fecha DESC");
        $stmt->execute([$userId, $estado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Elimina todas las publicaciones con estado 'COMPLETADA'
    public function eliminarCompletadas()
    {
        $this->getDB()->query("DELETE FROM publicaciones WHERE estado = 'COMPLETADA'");
    }

    // Actualiza el estado de una publicación (usado desde el formulario de estado)
    public function updateEstado($id, $estado)
    {
        $stmt = $this->getDB()->prepare("UPDATE publicaciones SET estado = ? WHERE id = ?");
        $stmt->execute([$estado, $id]);
    }
    // Actualizar datos personales
    public function updateProfile($id_usuario, $data)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($data['actualizar_perfil'])) {
            $stmt = $this->getDB()->prepare("UPDATE usuarios SET NOMBRES = ?, APELLIDOS = ?, CIUDAD = ?, EDAD = ?, CORREO = ? WHERE ID_USUARIO = ?");
            $stmt->execute([
                $data['nombre'],
                $data['apellidos'],
                $data['ciudad'],
                $data['edad'],
                $data['correo'],
                $id_usuario
            ]);
            $mensaje = "Perfil actualizado correctamente.";
        }
    }

    // Cambiar contraseña
    public function password($id_usuario, $nueva)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cambiar_password'])) {
            $nueva = $_POST['nueva_password'];
            $confirmar = $_POST['confirmar_password'];

            if ($nueva === $confirmar && strlen($nueva) >= 8) {
                $stmt = $this->getDB()->prepare("UPDATE usuarios SET CONTRASEÑA = ? WHERE ID_USUARIO = ?");
                $stmt->execute([$nueva, $id_usuario]);
                $mensaje = "Contraseña cambiada correctamente.";
            } else {
                $mensaje = "Error: Las contraseñas no coinciden o no cumplen con los requisitos.";
            }
        }
    }
    // Actualiza la foto de perfil del usuario
    public function updateFoto($id, $nombreArchivo)
    {
        $stmt = $this->getDB()->prepare("UPDATE usuarios SET FOTO = ? WHERE ID_USUARIO = ?");
        $stmt->execute([$nombreArchivo, $id]);
    }
}
