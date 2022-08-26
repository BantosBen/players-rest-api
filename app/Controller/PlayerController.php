<?php

class Player
{
    private $connection;
    private $dbConnect;
    private $auth;

    /**
     * Class constructor.
     */
    function __construct()
    {
        require_once dirname(__FILE__) . "/DbConnect.php";
        require_once dirname(__FILE__) . "/AuthController.php";
        $this->dbConnect = new DBConnect;
        $this->connection = $this->dbConnect->connect();

        $this->auth = new AuthController;
    }


    public function getAllPlayers($response)
    {
        $sql = "SELECT * FROM `players`";
        $result = $this->connection->query($sql);

        $message = array();
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
    }

    public function getPlayerById($response, $args)
    {
        $id = $args['id'];

        $sql = "SELECT * FROM `players` WHERE `id`='$id'";
        $result = $this->connection->query($sql);

        $message = array();
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
    }

    public function getPlayersByGender($response, $args)
    {
        $id = $args['id'];
        
        $sql = "SELECT * FROM `players`";
        $result = $this->connection->query($sql);

        $message = array();
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
    }
}
