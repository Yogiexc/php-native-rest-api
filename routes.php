<?php
/**
 * ROUTES
 * Tambahkan di bagian paling atas (sebelum route lainnya)
 */

// GET /logs (public - untuk demo, di production harus protected)
if ($method === 'GET' && $uri === '/logs') {
    $date = Request::query('date');
    $logs = Logger::getLogs($date);
    
    Response::success([
        'date' => $date ?? date('Y-m-d'),
        'total' => count($logs),
        'logs' => $logs
    ]);
    exit;
}
$userController = new UserController();

// POST /login (public - no auth)
if ($method === 'POST' && $uri === '/login') {
    $data = Request::body();
    
    // Hardcoded untuk demo
    if ($data['email'] === 'admin@example.com' && $data['password'] === 'password') {
        $token = 'token_user_123';
        
        Response::success([
            'message' => 'Login successful',
            'token' => $token,
            'user' => ['id' => 1, 'name' => 'Admin', 'email' => 'admin@example.com']
        ]);
    } else {
        Response::error('Invalid credentials', 401);
    }
    
    exit;
}

// GET /users/search (public - no auth)
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

// GET /users (public - no auth)
if ($method === 'GET' && $uri === '/users') {
    $userController->index();
    exit;
}

// GET /users/{id} (public - no auth)
if ($method === 'GET' && preg_match('/^\/users\/(\d+)$/', $uri, $matches)) {
    $id = $matches[1];
    $userController->show($id);
    exit;
}

// POST /users (protected - need auth)
if ($method === 'POST' && $uri === '/users') {
    AuthMiddleware::authenticate(); // Check auth
    $userController->store();
    exit;
}

// PUT /users/{id} (protected - need auth)
if ($method === 'PUT' && preg_match('/^\/users\/(\d+)$/', $uri, $matches)) {
    AuthMiddleware::authenticate(); // Check auth
    $id = $matches[1];
    $userController->update($id);
    exit;
}

// DELETE /users/{id} (protected - need auth)
if ($method === 'DELETE' && preg_match('/^\/users\/(\d+)$/', $uri, $matches)) {
    AuthMiddleware::authenticate(); // Check auth
    $id = $matches[1];
    $userController->destroy($id);
    exit;
}