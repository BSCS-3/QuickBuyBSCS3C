<?php

class AuthMiddleware
{
    private $allowedRoles;

    public function __construct($allowedRoles = [])
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function handleRequest()
    {
        // Get the Authorization header
        $headers = getallheaders();
        $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

        // Check if token exists
        if (empty($authHeader) || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode([
                'status' => 'failed',
                'message' => 'No token provided or invalid token format'
            ]);
            exit();
        }

        $token = $matches[1];

        try {
            // Verify and decode the token
            $key = $_ENV['SECRET_KEY'];
            $decoded = $this->verifyToken($token, $key);

            // Check if user has required role
            if (!empty($this->allowedRoles) && !in_array($decoded->role, $this->allowedRoles)) {
                http_response_code(403);
                echo json_encode([
                    'status' => 'failed',
                    'message' => 'Unauthorized access: Insufficient permissions'
                ]);
                exit();
            }

            // Add user info to request for later use
            $_REQUEST['user'] = $decoded;

            return true;

        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode([
                'status' => 'failed',
                'message' => 'Invalid or expired token'
            ]);
            exit();
        }
    }

    private function verifyToken($token, $key)
    {
        // Implement JWT verification here
        // You'll need to add a JWT library to your composer.json

        list($header, $payload, $signature) = explode('.', $token);

        // Decode payload
        $payload = json_decode(base64_decode($payload));

        // Verify token hasn't expired
        if (isset($payload->exp) && $payload->exp < time()) {
            throw new Exception('Token has expired');
        }

        return $payload;
    }
}