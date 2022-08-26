<?php

class AuthController
{
    private $connection;
    private $dbConnect;

    /**
     * Class constructor.
     */
    function __construct()
    {
        require_once dirname(__FILE__) . "/DbConnect.php";
        $this->dbConnect = new DBConnect;
        $this->connection = $this->dbConnect->connect();
    }


    public function login($request, $response)
    {
        $requestData = json_decode($request->getBody());
        $message = array();

        $email = $requestData->email;
        $password = $requestData->password;

        $sql = "SELECT * FROM `users` WHERE `email`='$email'";
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            while ($record = $result->fetch_assoc()) {
                $hashedPassword = $record['password'];
            }
            if (password_verify($password, $hashedPassword)) {
                $this->updateSessionToken($email);
                $message['error'] = false;
                $message['message'] = 'login Successfully, ';
                $message['user'] = $this->getUserByEmail($email);
            } else {
                $message['error'] = true;
                $message['message'] = 'Invalid Password';
            }
        } else {
            $message['error'] = true;
            $message['message'] = "Email doesn't exist";
        }

        $response->getBody()->write(json_encode($message));

        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);
    }

    private function updateSessionToken($email)
    {
        $sessionToken = $this->dbConnect->generateSessionToken();
        if ($this->isTokenExists($sessionToken)) {
            $sessionToken = $this->dbConnect->generateSessionToken();
        }

        $sql = "UPDATE `users` SET `session_token`='$sessionToken' WHERE `email`='$email'";
        $this->connection->query($sql);
    }


    private function getUserByEmail($email)
    {
        $sql = "SELECT * FROM `users` WHERE `email`='$email'";
        $result = $this->connection->query($sql);
        while ($record = $result->fetch_assoc()) {
            return $record;
        }
    }


    public function createAccount($request, $response)
    {
        $sessionToken = $this->dbConnect->generateSessionToken();
        if ($this->isTokenExists($sessionToken)) {
            $sessionToken = $this->dbConnect->generateSessionToken();
        }


        $requestData = json_decode($request->getBody());
        $id = time() . rand(1000, 100000);
        $email = $requestData->email;
        $name = $requestData->name;
        $password = password_hash($requestData->password, PASSWORD_DEFAULT);

        $message = array();
        if (!$this->isEmailExist($email)) {
            $sql = "INSERT INTO `users`(`id`, `email`, `name`, `password`, `session_token`) VALUES ('$id','$email','$name','$password','$sessionToken')";
            $result = $this->connection->query($sql);

            if ($result > 0) {
                $message['error'] = false;
                $message['message'] = 'User created successfully';
            } else {
                $message['error'] = true;
                $message['message'] = 'Failed Kindly try again';
            }
        } else {
            $message['error'] = true;
            $message['message'] = 'Email already exists';
        }

        $response->getBody()->write(json_encode($message));

        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);
    }

    private function isEmailExist($email)
    {
        $sql = "SELECT * FROM `users` WHERE `email`='$email'";
        $result = $this->connection->query($sql);
        return $result->num_rows > 0;
    }


    private function isTokenExists($token)
    {
        $sql = "SELECT * FROM `users` WHERE `session_token`='$token'";
        $result = $this->connection->query($sql);
        return $result->num_rows > 0;
    }

    public function isAuthorized($request){
        $authHeader = $request->getHeader('Authorization');
        $sessionToken = $authHeader[0];

        return ($sessionToken != "" && $this->isTokenExists($sessionToken));
    }
}
