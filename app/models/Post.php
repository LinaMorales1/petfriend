<?php
class Post extends Model
{
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
        $stmt = $this->getDB()->query("SELECT p.*, u.NOMBRES, u.APELLIDOS FROM publicaciones p JOIN usuarios u ON p.usuario_id = u.ID_USUARIO ORDER BY p.fecha DESC");
        return $stmt->fetchAll();
    }

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

        $data['id'] = $db->lastInsertId();
        return $data;
    }
}
