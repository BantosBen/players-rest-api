<?php

class User
{
    private $connection;
    private $auth;

    /**
     * Class constructor.
     */
    function __construct()
    {
        require_once dirname(__FILE__) . "/DbConnect.php";
        require_once dirname(__FILE__) . "/AuthController.php";
        $dbConnect = new DBConnect;
        $this->connection = $dbConnect->connect();

        $this->auth = new AuthController;
    }

    function updateAccount($request, $response)
    {
        $message = array();

        if ($this->auth->isAuthorized($request)) {
            $requestData = json_decode($request->getBody());
            $email = $requestData->email;
            $name = $requestData->name;
            $sessionToken = $this->auth->extractToken($request);

            $sql = "UPDATE `players` SET `name`='$name', `email`='$email' WHERE `session_token`='$sessionToken'";
            $result = $this->connection->query($sql);

            if ($result > 0) {
                $message['error'] = false;
                $message['message'] = 'Account Updated';
            } else {
                $message['error'] = true;
                $message['message'] = 'Failed! Try again later';
            }

            $response->getBody()->write(json_encode($message));

            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);
        } else {
            $message['error'] = true;
            $message['message'] = 'UnAthorized Access';

            $response->getBody()->write(json_encode($message));

            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(401);
        }
    }

    function deleteAccount($request, $response)
    {
        $message = array();

        if ($this->auth->isAuthorized($request)) {
            $requestData = json_decode($request->getBody());
            $email = $requestData->email;
            $sessionToken = $this->auth->extractToken($request);

            $sql = "DELETE FROM `players` WHERE `session_token`='$sessionToken' AND `email`='$email'";
            $result = $this->connection->query($sql);

            if ($result > 0) {
                $message['error'] = false;
                $message['message'] = 'Account Deleted';
            } else {
                $message['error'] = true;
                $message['message'] = 'Failed! Try again later';
            }

            $response->getBody()->write(json_encode($message));

            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);
        } else {
            $message['error'] = true;
            $message['message'] = 'UnAthorized Access';

            $response->getBody()->write(json_encode($message));

            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(401);
        }
    }
}
