<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require '../app/Controller/AuthController.php';
require '../app/Controller/PlayerController.php';
require '../app/Controller/UserController.php';

$app = AppFactory::create();

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->post('/public/login', function (Request $request, Response $response) {
	$auth = new AuthController;
	$response = $auth->login($request, $response);
	return $response;
});

$app->post('/public/signup', function (Request $request, Response $response) {
	$auth = new AuthController;
	$response = $auth->createAccount($request, $response);
	return $response;
});

$app->get('/public/players', function (Request $request, Response $response) {
	$player = new Player;
	$response = $player->getAllPlayers($request, $response);
	return $response;
});

$app->get('/public/players/view/{id}', function (Request $request, Response $response, array $args) {
	$player = new Player;
	$response = $player->getPlayerById($request, $response, $args);
	return $response;
});


$app->get('/public/players/filter/{gender}', function (Request $request, Response $response, array $args) {
	$player = new Player;
	$response = $player->getPlayersByGender($request, $response, $args);
	return $response;
});

$app->delete('/public/players/delete/{id}', function (Request $request, Response $response, array $args) {
	$player = new Player;
	$response = $player->deletePlayersById($request, $response, $args);
	return $response;
});

$app->put('/public/account/update', function (Request $request, Response $response) {
	$player = new User;
	$response = $player->updateAccount($request, $response);
	return $response;
});

$app->delete('/public/account/delete', function (Request $request, Response $response) {
	$player = new User;
	$response = $player->deleteAccount($request, $response);
	return $response;
});

$app->run();
