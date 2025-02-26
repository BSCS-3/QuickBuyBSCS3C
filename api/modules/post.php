<?php

require_once 'global.php';

class Post extends GlobalMethods
{

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function add_user($data)
    {
        try {
            // Validate required fields
            if (!isset($data->username) || !isset($data->email) || !isset($data->password)) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Username, email and password are required",
                    400
                );
            }

            // Hash password
            $hashed_password = password_hash($data->password, PASSWORD_DEFAULT);

            // Set default role as customer if not specified
            $role = isset($data->role) ? $data->role : 'customer';

            // Insert new user
            $sql = "INSERT INTO users (username, email, password, first_name, last_name, role) 
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data->username,
                $data->email,
                $hashed_password,
                $data->first_name ?? null,
                $data->last_name ?? null,
                $role
            ]);

            return $this->sendPayload(
                null,
                "success",
                "User registered successfully",
                201
            );

        } catch (PDOException $e) {
            // Handle duplicate email/username
            if ($e->getCode() == '23000') {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Email or username already exists",
                    409
                );
            }

            return $this->sendPayload(
                null,
                "failed",
                $e->getMessage(),
                400
            );
        }
    }


}





