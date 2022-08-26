<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require '../app/Controller/AuthController.php';

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

$app->run();
