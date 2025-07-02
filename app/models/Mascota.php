<?php
class Mascota extends Model
{
    public function getAll()
    {
        $stmt = $this->getDB()->query("SELECT ID_MASCOTAS, ESPECIE FROM mascotas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
