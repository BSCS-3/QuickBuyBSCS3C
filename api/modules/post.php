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
        $sql = "INSERT INTO users(username, email, pass) VALUES (?, ? ,?)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(
                [
                    $data->username,
                    $data->email,
                    $data->password,
                ]
            );
            return $this->sendPayload(null, "success", "Successfully created a new record", 200);
        } catch (PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }

        return $this->sendPayload(null, "failed", $errmsg, $code);
    }


}





