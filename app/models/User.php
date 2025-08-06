<?php
class User extends Model
{
    // Obtener todos los usuarios
    public function getAll()
    {
        $stmt = $this->getDB()->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo usuario
    public function create($data)
    {
        $sql = "INSERT INTO usuarios 
                (NOMBRES, APELLIDOS, CORREO, IDENTIFICACION, EDAD, CIUDAD, CELULAR, CONTRASEÑA, ROL)
                VALUES 
                (:nombre, :apellido, :correo, :documento, :edad, :ciudad, :celular, :contrasena, :rol)";

        $stmt = $this->getDB()->prepare($sql);

        return $stmt->execute([
            ':nombre'     => $data['nombre'],
            ':apellido'   => $data['apellido'],
            ':correo'     => $data['correo'],
            ':documento'  => $data['documento'],
            ':edad'       => $data['edad'],
            ':ciudad'     => $data['ciudad'],
            ':celular'    => $data['celular'],
            ':contrasena' => $data['contrasena'],
            ':rol'        => $data['rol'],
        ]);
    }

    // Buscar un usuario por correo electrónico
    public function findByCorreo($correo)
    {
        $stmt = $this->getDB()->prepare("SELECT * FROM usuarios WHERE CORREO = :correo LIMIT 1");
        $stmt->execute([':correo' => $correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener un usuario por ID
    public function getById($id)
    {
        $stmt = $this->getDB()->prepare("SELECT * FROM usuarios WHERE ID_USUARIO = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar la foto de perfil del usuario
    public function updateFoto($id, $nombreArchivo)
    {
        $stmt = $this->getDB()->prepare("UPDATE usuarios SET FOTO = ? WHERE ID_USUARIO = ?");
        $stmt->execute([$nombreArchivo, $id]);
    }

    // Actualizar perfil del usuario
    public function actualizarPerfil($id, $nombre, $apellidos, $ciudad, $edad, $correo)
    {
        $stmt = $this->getDB()->prepare("UPDATE usuarios SET NOMBRES = ?, APELLIDOS = ?, CIUDAD = ?, EDAD = ?, CORREO = ? WHERE ID_USUARIO = ?");
        return $stmt->execute([$nombre, $apellidos, $ciudad, $edad, $correo, $id]);
    }

    // Cambiar contraseña del usuario
    public function cambiarContrasena($id_usuario, $nueva)
    {
        $stmt = $this->getDB()->prepare("UPDATE usuarios SET CONTRASEÑA = ? WHERE ID_USUARIO = ?");
        return $stmt->execute([$nueva, $id_usuario]);
    }

    // Actualizar rol del usuario
    public function actualizarRol($id, $rol)
    {
        $stmt = $this->getDB()->prepare("UPDATE usuarios SET ROL = ? WHERE ID_USUARIO = ?");
        return $stmt->execute([$rol, $id]);
    }

    // Actualizar contraseña (función duplicada, podría consolidarse con cambiarContrasena)
    public function actualizarPassword($id_usuario, $nueva)
    {
        $stmt = $this->getDB()->prepare("UPDATE usuarios SET CONTRASEÑA = ? WHERE ID_USUARIO = ?");
        return $stmt->execute([$nueva, $id_usuario]);
    }

    // Obtener mascotas registradas por un usuario
    public function getMascotasByUsuario($idUsuario)
    {
        $stmt = $this->getDB()->prepare("SELECT ID_MASCOTAS, ESPECIE FROM mascotas WHERE ID_USUARIO_FK = ?");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Enviar y obtener mensajes del usuario
    public function getmensajesByUsuario($idUsuario)
    {
        // Enviar mensaje si los datos están completos
        $emisor_id = $_SESSION['ID_USUARIO'];
        $receptor_id = $_POST['receptor_id'] ?? null;
        $contenido = trim($_POST['contenido'] ?? '');

        if ($receptor_id && $contenido) {
            $stmt = $this->getDB()->prepare("INSERT INTO mensajes (emisor_id, receptor_id, contenido) VALUES (:emisor, :receptor, :contenido)");
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

        // Obtener todos los mensajes relacionados con el usuario
        $stmt = $this->getDB()->prepare("SELECT * FROM mensajes WHERE emisor_id = ? OR receptor_id = ? ORDER BY fecha DESC");
        $stmt->execute([$idUsuario, $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function usuarioDioLike($idUsuario, $idPublicacion)
    {
        $stmt = $this->getDB()->prepare("SELECT COUNT(*) FROM likes WHERE id_usuario = ? AND id_publicacion = ?");
        $stmt->execute([$idUsuario, $idPublicacion]);
        return $stmt->fetchColumn() > 0;
    }

    public function darLike($idUsuario, $idPublicacion)
    {
        $stmt = $this->getDB()->prepare("INSERT INTO likes (id_usuario, id_publicacion) VALUES (?, ?)");
        return $stmt->execute([$idUsuario, $idPublicacion]);
    }

    public function quitarLike($idUsuario, $idPublicacion)
    {
        $stmt = $this->getDB()->prepare("DELETE FROM likes WHERE id_usuario = ? AND id_publicacion = ?");
        return $stmt->execute([$idUsuario, $idPublicacion]);
    }

    public function updateBiografia($idUsuario, $biografia)
    {
        $stmt = $this->getDB()->prepare("UPDATE usuarios SET biografia = ? WHERE ID_USUARIO = ?");
        return $stmt->execute([$biografia, $idUsuario]);
    }
}
