<?php
class Mensaje extends Model
{
    public function enviar($emisorId, $receptorId, $mensaje)
    {
        $stmt = $this->getDB()->prepare("
            INSERT INTO mensajes (emisor_id, receptor_id, mensaje)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$emisorId, $receptorId, $mensaje]);
    }

    public function obtenerMensajes($usuarioId)
    {
        $stmt = $this->getDB()->prepare("
            SELECT m.*, u.NOMBRES AS nombre_emisor, r.NOMBRES AS nombre_receptor
            FROM mensajes m
            JOIN usuarios u ON m.emisor_id = u.ID_USUARIO
            JOIN usuarios r ON m.receptor_id = r.ID_USUARIO
            WHERE emisor_id = ? OR receptor_id = ?
            ORDER BY fecha_envio DESC
        ");
        $stmt->execute([$usuarioId, $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
$receptor_id = $_GET['receptor'] ?? null;
if ($receptor_id) {
    $mensaje = new Mensaje();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $emisor_id = $_SESSION['user_id']; // Asumiendo que el ID del usuario está en la sesión
        $contenido = $_POST['mensaje'] ?? '';
        if (!empty($contenido)) {
            $mensaje->enviar($emisor_id, $receptor_id, $contenido);
            header("Location: /petfriend/public/mensajes?receptor=$receptor_id");
            exit;
        }
    }
    $mensajes = $mensaje->obtenerMensajes($_SESSION['user_id']);
}
