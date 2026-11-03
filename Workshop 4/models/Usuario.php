<?php
// Modelo de usuarios: todas las consultas a la tabla usuarios
class Usuario
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Usuario por nombre de usuario, para el login (false si no existe)
    public function buscarPorUsername($username)
    {
        $statement = $this->conn->prepare(
            'SELECT id, username, password FROM usuarios WHERE username = :username LIMIT 1'
        );
        $statement->execute(['username' => $username]);
        return $statement->fetch();
    }

    // Usuario por id, con el nombre de su provincia (false si no existe)
    public function buscarPorId($id)
    {
        $statement = $this->conn->prepare(
            'SELECT u.id, u.username, u.provincia_id, p.nombre AS provincia
             FROM usuarios u
             LEFT JOIN provincias p ON p.id = u.provincia_id
             WHERE u.id = :id'
        );
        $statement->execute(['id' => $id]);
        return $statement->fetch();
    }

    // Crea un usuario; la contraseña ya llega cifrada
    public function crear($username, $passwordHash, $provinciaId)
    {
        $statement = $this->conn->prepare(
            'INSERT INTO usuarios (username, password, provincia_id)
             VALUES (:username, :password, :provincia_id)'
        );
        $statement->execute([
            'username'     => $username,
            'password'     => $passwordHash,
            'provincia_id' => $provinciaId,
        ]);
    }

    // Actualiza nombre y provincia; la contraseña solo cambia si llega un hash nuevo
    public function actualizar($id, $username, $provinciaId, $passwordHash = null)
    {
        if ($passwordHash !== null) {
            $statement = $this->conn->prepare(
                'UPDATE usuarios SET username = :username, provincia_id = :provincia_id, password = :password WHERE id = :id'
            );
            $statement->execute([
                'username'     => $username,
                'provincia_id' => $provinciaId,
                'password'     => $passwordHash,
                'id'           => $id,
            ]);
        } else {
            $statement = $this->conn->prepare(
                'UPDATE usuarios SET username = :username, provincia_id = :provincia_id WHERE id = :id'
            );
            $statement->execute([
                'username'     => $username,
                'provincia_id' => $provinciaId,
                'id'           => $id,
            ]);
        }
    }
}