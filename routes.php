<?php
/**
 * ROUTES
 * Tambahkan route untuk search (LETAKKAN SEBELUM GET /users)
 */

$userController = new UserController();

// GET /users/search?q=keyword
if ($method === 'GET' && $uri === '/users/search') {
    $keyword = Request::query('q', '');
    $page = Request::query('page', 1);
    $limit = Request::query('limit', 10);
    
    if (empty($keyword)) {
        Response::error('Query parameter "q" is required', 400);
    }
    
    $userController->search($keyword, $page, $limit);
    exit;
}

// GET /users (existing route)
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