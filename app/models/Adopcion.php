<?php
class Adopcion extends Model
{
    public function getAllWithDetails()
    {
        $sql = "SELECT a.`ID-SOLICITUD` AS ID, 
               u.NOMBRES, u.APELLIDOS, u.CIUDAD AS CIUDAD, 
               m.ESPECIE, 
               e.ESTADO AS ESTADO, 
               a.FECHA
        FROM adopciones a
        JOIN usuarios u ON a.ID_USUARIO_FK = u.ID_USUARIO
        JOIN mascotas m ON a.ID_MASCOTA_FK = m.ID_MASCOTAS
        JOIN estado e ON a.ID_ESTADO_FK = e.ID
        ORDER BY a.FECHA DESC";


        return $this->getDB()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarEstado($id, $estado)
    {
        $stmt = $this->getDB()->prepare("UPDATE adopciones SET ID_ESTADO_FK = ? WHERE `ID-SOLICITUD` = ?");
        return $stmt->execute([$estado, $id]);
    }
    public function crearSolicitud($data)
    {
        $stmt = $this->getDB()->prepare("
        INSERT INTO adopciones (ID_USUARIO_FK, ID_MASCOTA_FK, CIUDAD, FECHA, ID_ESTADO_FK)
        VALUES (?, ?, ?, ?, ?)
    ");

        return $stmt->execute([
            $data['id_usuario'],
            $data['id_mascota'],
            $data['ciudad'],
            $data['fecha'],
            $data['estado']
        ]);
    }
    public function actualizarEstadoPorMascota($mascotaId, $estado)
    {
        $stmt = $this->getDB()->prepare("
        UPDATE adopciones 
        SET ID_ESTADO_FK = ?
        WHERE ID_MASCOTA_FK = ?
    ");
        return $stmt->execute([$estado, $mascotaId]);
    }
    public function getMascotaIdBySolicitud($solicitudId)
    {
        $stmt = $this->getDB()->prepare("SELECT ID_MASCOTA_FK FROM adopciones WHERE `ID-SOLICITUD` = ?");
        $stmt->execute([$solicitudId]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila['ID_MASCOTA_FK'] ?? null;
    }
}
