<?php

namespace Src\Controllers;

use PDO;
use PDOException;

class UserController
{
    private PDO $db;

    public function __construct()
    {
        try {
            $this->db = new PDO(
                "mysql:host=localhost;dbname=github;charset=utf8",
                "root",
                ""
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die(json_encode([
                "success" => false,
                "message" => "Database connection error",
                "error" => $e->getMessage()
            ]));
        }
    }


    public function index()
    {
        $stmt = $this->db->query("SELECT id, nama AS username, email FROM tabel_admin");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(["success" => true, "data" => $data]);
    }


    public function show($id)
    {
        $stmt = $this->db->prepare("SELECT id, nama AS username, email FROM tabel_admin WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            echo json_encode(["success" => false, "message" => "User not found"]);
            return;
        }

        echo json_encode(["success" => true, "data" => $data]);
    }


    public function store()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input || !isset($input['username'], $input['email'], $input['password'])) {
            echo json_encode(["success" => false, "message" => "Invalid input"]);
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO tabel_admin (nama, email, password) VALUES (?, ?, ?)");
        $stmt->execute([
            $input['username'],
            $input['email'],
            password_hash($input['password'], PASSWORD_BCRYPT)
        ]);

        echo json_encode([
            "success" => true,
            "message" => "User created successfully",
            "id" => $this->db->lastInsertId()
        ]);
    }


    public function update($id)
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input || !isset($input['username'], $input['email'])) {
            echo json_encode(["success" => false, "message" => "Invalid input"]);
            return;
        }

        $sql = "UPDATE tabel_admin SET nama = ?, email = ?";

        $params = [$input['username'], $input['email']];

        if (isset($input['password']) && $input['password'] !== "") {
            $sql .= ", password = ?";
            $params[] = password_hash($input['password'], PASSWORD_BCRYPT);
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        echo json_encode([
            "success" => true,
            "message" => "User updated successfully"
        ]);
    }

    public function destroy($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tabel_admin WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode([
            "success" => true,
            "message" => "User deleted successfully"
        ]);
    }
}
