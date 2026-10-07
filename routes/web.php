<?php

$router->get('/', function() {
    if (isset($_SESSION['user_id'])) {
        redirect('/dashboard');
    }
    view('layouts.app');
});

$router->get('/login', ['App\Controllers\AuthController', 'showLogin']);
$router->post('/login', ['App\Controllers\AuthController', 'login']);
$router->get('/register', ['App\Controllers\AuthController', 'showRegister']);
$router->post('/register', ['App\Controllers\AuthController', 'register']);
$router->get('/logout', ['App\Controllers\AuthController', 'logout']);

$router->get('/dashboard', ['App\Controllers\DashboardController', 'index']);

$router->get('/domains', ['App\Controllers\DomainController', 'index']);
$router->get('/domains/create', ['App\Controllers\DomainController', 'create']);
$router->post('/domains/create', ['App\Controllers\DomainController', 'store']);
$router->get('/domains/{id}', ['App\Controllers\DomainController', 'show']);
$router->post('/domains/{id}/delete', ['App\Controllers\DomainController', 'delete']);



