<?php

require_once 'global.php';

class Seller extends GlobalMethods
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get_business_profile($seller_id)
    {
        try {
            if (!$seller_id) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Seller ID is required",
                    400
                );
            }

            // First verify the seller exists and is active
            $sql_user = "SELECT id, role FROM users WHERE id = ? AND role = 'seller' AND is_active = 1";
            $stmt_user = $this->pdo->prepare($sql_user);
            $stmt_user->execute([$seller_id]);
            $seller = $stmt_user->fetch(PDO::FETCH_ASSOC);

            if (!$seller) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Invalid or inactive seller account",
                    404
                );
            }

            // Get business profile
            $sql = "SELECT * FROM business_profiles WHERE seller_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$seller_id]);
            $profile = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$profile) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Business profile not found",
                    404
                );
            }

            return $this->sendPayload(
                $profile,
                "success",
                "Business profile retrieved successfully",
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

    public function update_business_profile($seller_id, $data)
    {
        try {
            if (!$seller_id) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Seller ID is required",
                    400
                );
            }

            // Verify seller exists and is active
            $sql_user = "SELECT id, role FROM users WHERE id = ? AND role = 'seller' AND is_active = 1";
            $stmt_user = $this->pdo->prepare($sql_user);
            $stmt_user->execute([$seller_id]);
            $seller = $stmt_user->fetch(PDO::FETCH_ASSOC);

            if (!$seller) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Invalid or inactive seller account",
                    404
                );
            }

            // Fields that are allowed to be updated
            $allowed_fields = [
                'business_name',
                'description',
                'logo_url',
                'contact_email',
                'contact_phone',
                'address'
            ];

            $updates = [];
            $values = [];

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

            // Add updated_at timestamp
            $updates[] = "updated_at = CURRENT_TIMESTAMP";

            // Add seller_id to values array
            $values[] = $seller_id;

            $sql = "UPDATE business_profiles SET " . implode(', ', $updates) . " WHERE seller_id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);

            // Return updated profile
            return $this->get_business_profile($seller_id);

        } catch (PDOException $e) {
            // Handle duplicate business name
            if ($e->getCode() == '23000') {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Business name already exists",
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

    // Helper method to create initial business profile
    public function create_business_profile($seller_id, $business_name)
    {
        try {
            // First check if a profile already exists
            $check_sql = "SELECT seller_id FROM business_profiles WHERE seller_id = ?";
            $check_stmt = $this->pdo->prepare($check_sql);
            $check_stmt->execute([$seller_id]);
            
            if ($check_stmt->fetch()) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Business profile already exists for this seller",
                    409
                );
            }

            // Get seller's email from users table
            $user_sql = "SELECT email FROM users WHERE id = ? AND role = 'seller'";
            $user_stmt = $this->pdo->prepare($user_sql);
            $user_stmt->execute([$seller_id]);
            $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Invalid seller ID",
                    404
                );
            }

            // Create the business profile
            $sql = "INSERT INTO business_profiles (
                        seller_id, 
                        business_name, 
                        contact_email,
                        is_approved,
                        created_at
                    ) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $seller_id,
                $business_name,
                $user['email'],
                false // Default to not approved
            ]);

            return $this->sendPayload(
                null,
                "success",
                "Business profile created successfully",
                201
            );

        } catch (PDOException $e) {
            // Handle duplicate business name
            if ($e->getCode() == '23000') {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Business name already exists",
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

    public function get_seller_products($seller_id)
    {
        try {
            if (!$seller_id) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Seller ID is required",
                    400
                );
            }

            $sql = "SELECT * FROM products WHERE seller_id = ? ORDER BY created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$seller_id]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->sendPayload(
                $products,
                "success",
                "Products retrieved successfully",
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

    public function add_product($seller_id, $data)
    {
        try {
            if (!isset($data->name) || !isset($data->price)) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Product name and price are required",
                    400
                );
            }

            $sql = "INSERT INTO products (
                seller_id, name, description, price, stock, 
                image_url, is_active, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $seller_id,
                $data->name,
                $data->description ?? null,
                $data->price,
                $data->stock ?? 0,
                $data->image_url ?? null,
                $data->is_active ?? true
            ]);

            $product_id = $this->pdo->lastInsertId();

            // Fetch and return the created product
            $sql = "SELECT * FROM products WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            return $this->sendPayload(
                $product,
                "success",
                "Product added successfully",
                201
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

    public function update_product($seller_id, $product_id, $data)
    {
        try {
            // Verify product belongs to seller
            $check_sql = "SELECT id FROM products WHERE id = ? AND seller_id = ?";
            $check_stmt = $this->pdo->prepare($check_sql);
            $check_stmt->execute([$product_id, $seller_id]);
            
            if (!$check_stmt->fetch()) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Product not found or doesn't belong to seller",
                    404
                );
            }

            $allowed_fields = ['name', 'description', 'price', 'stock', 'image_url', 'is_active'];
            $updates = [];
            $values = [];

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

            $updates[] = "updated_at = CURRENT_TIMESTAMP";
            $values[] = $product_id;
            $values[] = $seller_id;

            $sql = "UPDATE products SET " . implode(', ', $updates) . 
                   " WHERE id = ? AND seller_id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);

            // Fetch and return updated product
            $sql = "SELECT * FROM products WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            return $this->sendPayload(
                $product,
                "success",
                "Product updated successfully",
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

    public function delete_product($seller_id, $product_id)
    {
        try {
            // Verify product belongs to seller
            $check_sql = "SELECT id FROM products WHERE id = ? AND seller_id = ?";
            $check_stmt = $this->pdo->prepare($check_sql);
            $check_stmt->execute([$product_id, $seller_id]);
            
            if (!$check_stmt->fetch()) {
                return $this->sendPayload(
                    null,
                    "failed",
                    "Product not found or doesn't belong to seller",
                    404
                );
            }

            $sql = "DELETE FROM products WHERE id = ? AND seller_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$product_id, $seller_id]);

            return $this->sendPayload(
                null,
                "success",
                "Product deleted successfully",
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
}
