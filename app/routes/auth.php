<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;


$app->add(new Tuupola\Middleware\JwtAuthentication([
    "path" => ["/admin"],
    "attribute" => "user",
    "cookie" => "user",
    "secret" => JWT_APP_SECRET,
    'algorithm' => ['HS256'],
    'secure' => JWT_SECURE,
    'error' => function ($response, $arguments) {
        return  $response->withHeader('Location', '/login')->withStatus(200);

    },
    "before" => function ($request) {
        null;
    },
    "logger" => $log
]));

$app->get('/login', function (Request $request, Response $response, $args) {
    return $this->get('common_template')->render($response, "login.phtml", ["title" => "Sign In", "error" => false])->withStatus(200);
});

$app->post('/login', function (Request $request, Response $response, $args) {
    $username = $request->getParsedBody()['username'];
    $password = $request->getParsedBody()['password'];
    $remember = isset($request->getParsedBody()['remember']) ? 1 : 0;
    (int) $session_length = $remember == 1 ? 604800 : 1800;
    $token = $this->get("user")->setUsername($username)->setPassword($password)->authenticate($session_length);
    if ($token == null) {
        return $this->get('common_template')->render($response, "login.phtml", ["title" => "Sign In", "error" => true])->withStatus(403);
    } else {
        setcookie("user", $token, time() + $session_length);
        return  $response->withHeader('Location', '/admin')->withStatus(200);
    }
});


$app->get('/signout', function (Request $request, Response $response, $args) {
    return $this->get('common_template')->render($response, "logout.phtml", ["title" => "Sign Out", "error" => false])->withStatus(200);
});

$app->get('/logoutnow', function (Request $request, Response $response, $args) {
    $login_redirect = '/login';
    setcookie('user', '', time() - 604800);
    return  $response->withHeader('Location', $login_redirect)->withStatus(200);
});
