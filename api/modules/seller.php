<?php

require_once 'global.php';

class Seller extends GlobalMethods
{
    private $pdo;
    
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // GET Seller Profile
    public function get_seller_profile($id)
    {
        $sql = "SELECT * FROM sellers WHERE sellerID = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($result) {
                return $this->sendPayload($result, "success", "Seller profile retrieved", 200);
            } else {
                return $this->sendPayload(null, "failed", "Seller not found", 404);
            }
        } catch (\PDOException $e) {
            return $this->sendPayload(null, "error", $e->getMessage(), 500);
        }
    }

    // UPDATE Seller Profile
    public function update_seller_profile($data)
    {
        $sql = "UPDATE sellers SET name = ?, email = ?, contact = ?, address = ? WHERE sellerID = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                trim($data->name),
                trim($data->email),
                trim($data->contact),
                trim($data->address),
                $data->sellerID
            ]);

            return $this->sendPayload(null, "success", "Seller profile updated", 200);
        } catch (\PDOException $e) {
            return $this->sendPayload(null, "error", $e->getMessage(), 500);
        }
    }
}
