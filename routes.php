<?php
/**
 * ROUTING MANUAL
 */

$userController = new UserController();

// GET /users
if ($method === 'GET' && $uri === '/users') {
    $userController->index();
    exit;
}

// GET /users/{id}
if ($method === 'GET' && preg_match('/^\/users\/(\d+)$/', $uri, $matches)) {
    $id = $matches[1];
    $userController->show($id);
    exit;
}

// POST /users
if ($method === 'POST' && $uri === '/users') {
    $userController->store();
    exit;
}

// PUT /users/{id}
if ($method === 'PUT' && preg_match('/^\/users\/(\d+)$/', $uri, $matches)) {
    $id = $matches[1];
    $userController->update($id);
    exit;
}

// DELETE /users/{id}
if ($method === 'DELETE' && preg_match('/^\/users\/(\d+)$/', $uri, $matches)) {
    $id = $matches[1];
    $userController->destroy($id);
    exit;
}