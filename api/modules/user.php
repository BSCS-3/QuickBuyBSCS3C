<?php

require_once 'global.php';
require_once __DIR__ . '/seller.php';

class User extends GlobalMethods
{

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function add_user($data)
    {
        try {
            // validation
            if (!isset($data->username) || !isset($data->email) || !isset($data->password)) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Username, email and password are required",
                    400
                );
            }

            // hash password
            $hashed_password = password_hash($data->password, PASSWORD_DEFAULT);

            // default role
            $role = isset($data->role) ? $data->role : 'customer';

            // insert new user
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

            $user_id = $this->pdo->lastInsertId();

            // If user is a seller, create their business profile
            if ($role === 'seller' && isset($data->shop_name)) {
                $seller = new Seller($this->pdo);
                $result = $seller->create_business_profile($user_id, $data->shop_name);
                
                if ($result['status']['remarks'] !== 'success') {
                    // If business profile creation fails, delete the user and return error
                    $this->pdo->exec("DELETE FROM users WHERE id = " . $user_id);
                    return $this->sendPayload(
                        null,
                        "failed",
                        "Failed to create business profile: " . $result['status']['message'],
                        400
                    );
                }
            }

            return $this->sendPayload(
                ['user_id' => $user_id],
                "success",
                "User registered successfully",
                201
            );

        } catch (PDOException $e) {
            // handle duplicate email/username
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

    public function get_user_profile($user_id)
    {
        try {
            // Validate user_id
            if (!$user_id) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "User ID is required",
                    400
                );
            }

            // Remove the role check to allow both customers and sellers
            $sql = "SELECT id, username, email, first_name, last_name, role, is_active, created_at, updated_at 
                    FROM users 
                    WHERE id = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$user_id]);
            $profile = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$profile) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Profile not found",
                    404
                );
            }

            return $this->sendPayload(
                $profile,
                "success",
                "Profile retrieved successfully",
                200
            );

        } catch (PDOException $e) {
            return $this->sendPayload(
                null,
                "error",
                $e->getMessage(),
                500
            );
        }
    }

    public function update_profile($user_id, $data)
    {
        try {
            // Validate user_id
            if (!$user_id) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "User ID is required",
                    400
                );
            }

            // Fields that are allowed to be updated
            $allowed_fields = ['first_name', 'last_name', 'email', 'username'];
            $updates = [];
            $values = [];

            // Build update query dynamically based on provided fields
            foreach ($allowed_fields as $field) {
                if (isset($data->$field)) {
                    $updates[] = "$field = ?";
                    $values[] = $data->$field;
                }
            }

            if (empty($updates)) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "No fields to update",
                    400
                );
            }

            // Add user_id to values array
            $values[] = $user_id;

            // Remove the role check to allow both customers and sellers
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);

            // Fetch and return updated profile
            return $this->get_user_profile($user_id);

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
                "error",
                $e->getMessage(),
                500
            );
        }
    }

}





