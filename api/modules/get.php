<?php

require_once 'global.php';

class Get extends GlobalMethods
{
    private $pdo;
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    //ESSENTIALS
    private function get_records($table = null, $conditions = null, $columns = '*', $customSqlStr = null, $params = [])
    {
        if ($customSqlStr != null) {
            $sqlStr = $customSqlStr;
        } else {
            $sqlStr = "SELECT $columns FROM $table";
            if ($conditions != null) {
                $sqlStr .= " WHERE " . $conditions;
            }
        }
        $result = $this->executeQuery($sqlStr, $params);

        if ($result['code'] == 200) {
            return $this->sendPayload($result['data'], 'success', "Successfully retrieved data.", $result['code']);
        }
        return $this->sendPayload(null, 'failed', "Failed to retrieve data.", $result['code']);
    }

    private function executeQuery($sql, $params = [])
    {
        try {
            $statement = $this->pdo->prepare($sql);
            if ($statement->execute($params)) {
                $data = $statement->fetchAll(PDO::FETCH_ASSOC);
                $code = 200;
                return array("code" => $code, "data" => $data);
            } else {
                $errmsg = "No data found.";
                $code = 404;
            }
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 403;
        }
        return array("code" => $code, "errmsg" => $errmsg);
    }



    public function get_all_users()
    {
        return $this->get_records('user');
    }


    //ADMIN: GET USERS
    public function get_users($id = null)
    {
        $condition = null;
        if ($id != null) {
            $condition = "id=$id";
        }
        return $this->get_records('user', $condition);
    }










    public function get_users_more_complicated($id = null)
    {
        try {
            $sql = "SELECT * FROM user";
            $params = [];

            if ($id !== null) {
                $sql .= " WHERE id = ?";
                $params[] = $id;
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->sendPayload($users, 'success', 'Users retrieved successfully', 200);

        } catch (\PDOException $e) {
            return $this->sendPayload(null, 'error', $e->getMessage(), 400);
        }
    }
}
