<?php
class User extends Model
{
    public function getAll()
    {
        $stmt = $this->getDB()->query("SELECT * FROM usuarios");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO usuarios 
                (NOMBRES, APELLIDOS, CORREO, IDENTIFICACION, EDAD, CIUDAD, CONTRASEÑA, ROL)
                VALUES 
                (:nombre, :apellido, :correo, :documento, :edad, :ciudad, :contrasena, :rol)";

        $stmt = $this->getDB()->prepare($sql);

        return $stmt->execute([
            ':nombre'     => $data['nombre'],
            ':apellido'   => $data['apellido'],
            ':correo'     => $data['correo'],
            ':edad'       => $data['edad'],
            ':documento'  => $data['documento'],
            ':ciudad'     => $data['ciudad'],
            ':contrasena' => $data['contrasena'],
            ':rol'        => $data['rol'],
        ]);
    }

    public function findByCorreo($correo)
    {
        $stmt = $this->getDB()->prepare("SELECT * FROM usuarios WHERE CORREO = :correo LIMIT 1");
        $stmt->execute([':correo' => $correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->getDB()->prepare("SELECT * FROM usuarios WHERE ID_USUARIO = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateFoto($id, $nombreArchivo)
    {
        $stmt = $this->getDB()->prepare("UPDATE usuarios SET FOTO = ? WHERE ID_USUARIO = ?");
        $stmt->execute([$nombreArchivo, $id]);
    }

    // Actualiza los datos personales del usuario
    public function actualizarPerfil($id, $nombre, $apellidos, $ciudad, $edad, $correo)
    {
        $stmt = $this->getDB()->prepare("UPDATE usuarios SET NOMBRES = ?, APELLIDOS = ?, CIUDAD = ?, EDAD = ?, CORREO = ? WHERE ID_USUARIO = ?");
        return $stmt->execute([$nombre, $apellidos, $ciudad, $edad, $correo, $id]);
    }



    // Cambia la contraseña del usuario
    public function cambiarContrasena($id_usuario, $nueva)
    {
        $stmt = $this->getDB()->prepare("UPDATE usuarios SET CONTRASEÑA = ? WHERE ID_USUARIO = ?");
        return $stmt->execute([$nueva, $id_usuario]);
    }

    public function actualizarRol($id, $rol)
    {
        $stmt = $this->getDB()->prepare("UPDATE usuarios SET ROL = ? WHERE ID_USUARIO = ?");
        return $stmt->execute([$rol, $id]);
    }
    public function actualizarPassword($id_usuario, $nueva)
    {
        $stmt = $this->getDB()->prepare("UPDATE usuarios SET CONTRASEÑA = ? WHERE ID_USUARIO = ?");
        return $stmt->execute([$nueva, $id_usuario]);
    }
    public function getMascotasByUsuario($idUsuario)
    {
        $stmt = $this->getDB()->prepare("SELECT ID_MASCOTAS, ESPECIE FROM mascotas WHERE ID_USUARIO_FK = ?");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getmensajesByUsuario($idUsuario)
    {
        $emisor_id = $_SESSION['ID_USUARIO'];
        $receptor_id = $_POST['receptor_id'] ?? null;
        $contenido = trim($_POST['contenido'] ?? '');

        if ($receptor_id && $contenido) {
            $stmt =$this->getDB()->prepare("INSERT INTO mensajes (emisor_id, receptor_id, contenido) VALUES (:emisor, :receptor, :contenido)");
            $stmt->execute([
                'emisor' => $emisor_id,
                'receptor' => $receptor_id,
                'contenido' => $contenido
            ]);
            header("Location: ../perfil.php?id=$receptor_id&mensaje_enviado=1");
            exit();
        } else {
            echo "Error: Datos incompletos.";
        }
        $stmt = $this->getDB()->prepare("SELECT * FROM mensajes WHERE emisor_id = ? OR receptor_id = ? ORDER BY fecha DESC");
        $stmt->execute([$idUsuario, $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
