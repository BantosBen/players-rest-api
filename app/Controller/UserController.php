<?php

use DI\ContainerBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Factory\AppFactory;

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


            $sql = "UPDATE `users` SET `name`='$name', `email`='$email' WHERE `session_token`='$sessionToken'";
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

    function deleteAccount($request, $response, $args)
    {
        $message = array();

        if ($this->auth->isAuthorized($request)) {
            $sessionToken = $this->auth->extractToken($request);

            $sql = "DELETE FROM `players` WHERE `session_token`='$sessionToken'";
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

    function uploadProfile($request, $response, $directory)
    {
        $message = array();

        if ($this->auth->isAuthorized($request)) {
            $sessionToken = $this->auth->extractToken($request);
            $id = $this->auth->getUserIdBySessionToken($sessionToken);
            $uploadedFiles = $request->getUploadedFiles();
            $filename = $id . ".png";
            $directory .="/". $filename;

            $uploadedFile = $uploadedFiles['avatar'];
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $uploadedFile->moveTo($directory);
            }

            $path = "https://upluv.gloyaldigital.com/public/uploads/" . $filename;

            $sql = "UPDATE `users` SET `image`='$path' WHERE `session_token`='$sessionToken'";
            $result = $this->connection->query($sql);

            if ($result > 0) {
                $message['error'] = false;
                $message['message'] = 'File uploaded successfully';
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

    function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
    {
    }
}
