<?php

class DBConnect
{
    private $connection;

    function connect()
    {
        include_once dirname(__FILE__) . "/Constants.php";
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if (mysqli_connect_errno()) {
            echo "Failed to connect" . mysqli_connect_error();
            return null;
        }

        return $this->connection;
    }


    public function generateSessionToken()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $token = '';
        for ($i = 0; $i < 20; ++$i) {
            $token .= $characters[rand(0, $charactersLength - 1)];
        }
        return $token;
    }
}
