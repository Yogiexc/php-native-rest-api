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
    public function show($id)
    {
        try {
            $user = $this->userModel->find($id);
            
            if (!$user) {
                Response::error('User not found', 404);
            }
            
            Response::success($user);
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }

    public function store()
    {
        try {
            $data = Request::body();
            $userId = $this->userModel->create($data);
            $user = $this->userModel->find($userId);
            
            Response::created([
                'message' => 'User created successfully',
                'user' => $user
            ]);
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }

    public function update($id)
    {
        try {
            $data = Request::body();
            $this->userModel->update($id, $data);
            $user = $this->userModel->find($id);
            
            Response::success([
                'message' => 'User updated successfully',
                'user' => $user
            ]);
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            $this->userModel->delete($id);
            
            Response::success([
                'message' => 'User deleted successfully'
            ]);
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
    public function search($keyword, $page = 1, $limit = 10)
    {
        try {
            // Validate
            $page = max(1, (int)$page);
            $limit = min(100, max(1, (int)$limit));
            
            $result = $this->userModel->search($keyword, $page, $limit);
            
            Response::success($result);
            
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }
}