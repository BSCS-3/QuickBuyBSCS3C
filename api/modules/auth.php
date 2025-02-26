<?php

require_once 'global.php';
require_once __DIR__ . '/../vendor/autoload.php';
$_ENV = array_merge($_ENV, parse_ini_file(__DIR__ . '/../.env'));
use \Firebase\JWT\JWT;

class Auth extends GlobalMethods
{
    private $pdo;
    private $secret_key;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->secret_key = $_ENV['SECRET_KEY'];
    }

    public function login($data)
    {
        try {
            // validate
            if (!isset($data->email) || !isset($data->password)) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Email and password are required",
                    400
                );
            }

            // find by email
            $sql = "SELECT id, email, password, role, username, first_name, last_name, is_active 
                    FROM users 
                    WHERE email = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$data->email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            //if user exists
            if (!$user) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Invalid email or password",
                    401
                );
            }

            // verify password
            if (!password_verify($data->password, $user['password'])) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Invalid email or password",
                    401
                );
            }

            // check if user is active
            if (!$user['is_active']) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Account is inactive",
                    403
                );
            }

            // actual token generation
            $payload = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'exp' => time() + (60 * 60 * 24)
            ];

            $token = JWT::encode($payload, $this->secret_key, 'HS256');

            // prepare response data
            $response = [
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'username' => $user['username'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'role' => $user['role']
                ]
            ];

            return $this->sendPayload(
                $response,
                "success",
                "Login successful",
                200
            );

        } catch (Exception $e) {
            return $this->sendPayload(
                null,
                "error",
                $e->getMessage(),
                500
            );
        }
    }
}