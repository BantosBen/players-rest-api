<?php

class Player
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


    public function getAllPlayers($request, $response)
    {
        $message = array();

        if ($this->auth->isAuthorized($request)) {
            $sql = "SELECT * FROM `players` ORDER BY RAND()";
            $result = $this->connection->query($sql);

            if ($result->num_rows > 0) {
                $players = array();

                while ($player = $result->fetch_assoc()) {
                    array_push($players, $player);
                }

                $message['error'] = false;
                $message['message'] = 'Player found';
                $message['players'] = $players;
            } else {
                $message['error'] = true;
                $message['message'] = 'No player found';
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

    public function getPlayerById($request, $response, $args)
    {
        $message = array();

        if ($this->auth->isAuthorized($request)) {
            $id = $args['id'];

            $sql = "SELECT * FROM `players` WHERE `id`='$id'";
            $result = $this->connection->query($sql);

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    $player = $row;
                }

                $message['error'] = false;
                $message['message'] = 'Player found';
                $message['player'] = $player;
            } else {
                $message['error'] = true;
                $message['message'] = 'No player found';
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

    public function getPlayersByGender($request, $response, $args)
    {
        $message = array();

        if ($this->auth->isAuthorized($request)) {
            $gender = $args['gender'];

            $sql = "SELECT * FROM `players` WHERE `gender`='$gender'";
            $result = $this->connection->query($sql);

            if ($result->num_rows > 0) {
                $players = array();

                while ($player = $result->fetch_assoc()) {
                    array_push($players, $player);
                }

                $message['error'] = false;
                $message['message'] = 'Player found';
                $message['players'] = $players;
            } else {
                $message['error'] = true;
                $message['message'] = 'No player found';
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

    public function deletePlayersById($request, $response, $args)
    {
        $message = array();

        if ($this->auth->isAuthorized($request)) {
            $id = $args['id'];

            $sql = "DELETE FROM `players` WHERE `id`='$id'";
            $result = $this->connection->query($sql);

            if ($result > 0) {
                $message['error'] = false;
                $message['message'] = 'Player deleted';
            } else {
                $message['error'] = true;
                $message['message'] = 'Failed! Try again';
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
