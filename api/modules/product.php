<?php

require_once 'global.php';

class product extends GlobalMethods
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get_products($data)
    {
        $sql_get = "SELECT * FROM products";

        if ($data) {
            $sql_get .= " WHERE productID = ?";
        }

        try {
            $stmt = $this->pdo->prepare($sql_get);
            $stmt->execute($data ? [$data] : null);
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if (count($result) > 0) {
                return $this->sendPayload($result, "success", "Success: Product found", 200);
            } else {
                return $this->sendPayload(null, "failed", "Error: Product not found", 404);
            }
        } catch (\Throwable $e) {
            return $this->sendPayload(null, "failed", "Error: " . $e->getMessage(), 500);
        }
    }

    public function add_product($data)
    {
        $sql_add = "INSERT INTO products(name, price, description, colors, quantity) 
                    VALUES (?, ?, ?, ?, ?)";

        $required_values = ['name', 'price', 'description', 'colors', 'quantity'];
        foreach ($required_values as $value) {
            if (!isset($data->$value) || empty($data->$value)) {
                return $this->sendPayload(null, "failed", "Error: All values are require, missing: $value", 400);
            }
        }

        if (!is_numeric($data->price)) {
            return $this->sendPayload(null, "failed", "Error: Invalid, Price must be number", 400);
        }

        if (!is_numeric($data->quantity)) {
            return $this->sendPayload(null, "failed", "Error: Invalid, Quantity must be number", 400);
        }

        try {
            $stmt = $this->pdo->prepare($sql_add);
            $stmt->execute(
                [
                    trim($data->name),
                    $data->price,
                    trim($data->description),
                    trim($data->colors),
                    $data->quantity
                ]
            );
            $result = $this->get_products($this->pdo->lastInsertId());

            return $this->sendPayload($result, "success", "Success: Product added", 200);
        } catch (\Throwable $e) {
            return $this->sendPayload(null, "failed", "Error: " . $e->getMessage(), 500);
        }
    }

    public function update_product($data)
    {
        $sql_update = "UPDATE products 
                       SET name = ?, price =? , description = ?, colors = ?, quantity = ? 
                       WHERE productID = ?";

        if (!is_numeric($data->price)) {
            return $this->sendPayload(null, "failed", "Error: Invalid, Price must be number", 400);
        }

        if (!is_numeric($data->quantity)) {
            return $this->sendPayload(null, "failed", "Error: Invalid, Quantity must be number", 400);
        }

        try {
            $stmt = $this->pdo->prepare($sql_update);
            $stmt->execute(
                [
                    trim($data->name),
                    $data->price,
                    trim($data->description),
                    trim($data->colors),
                    $data->quantity,
                    $data->productID
                ]
            );
            $result = $this->get_products($data->productID);

            return $this->sendPayload($result, "success", "Success: Product updated", 200);
        } catch (\Throwable $e) {
            return $this->sendPayload(null, "failed", "Error: " . $e->getMessage(), 500);
        }
    }

    public function delete_product($data)
    {
        $sql_delete = "DELETE FROM products WHERE productID = ?";
        try {
            $stmt = $this->pdo->prepare($sql_delete);
            $stmt->execute([$data]);

            if ($stmt->rowCount() > 0) {
                return $this->sendPayload(null, "success", "Success: Product deleted", 200);
            } else {
                return $this->sendPayload(null, "failed", "Error: Product not found", 404);
            }
        } catch (\Throwable $e) {
            return $this->sendPayload(null, "failed", "Error: " . $e->getMessage(), 500);
        }
    }
}
