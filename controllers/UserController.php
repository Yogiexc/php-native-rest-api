<?php
/**
 * USER CONTROLLER
 * Update method index() untuk support pagination
 */

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * GET /users?page=1&limit=10
     * Ambil semua users dengan pagination
     */
    public function index()
    {
        try {
            // Get query parameters
            $page = Request::query('page', 1);
            $limit = Request::query('limit', 10);
            
            // Validate
            $page = max(1, (int)$page);
            $limit = min(100, max(1, (int)$limit)); // Max 100 per page
            
            $result = $this->userModel->paginate($page, $limit);
            
            Response::success($result);
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }

    // ... existing methods (show, store, update, destroy) ...
}