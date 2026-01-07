<?php
/**
 * USER CONTROLLER
 */

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        try {
            $users = $this->userModel->all();
            
            Response::success([
                'users' => $users,
                'count' => count($users)
            ]);
            
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
}