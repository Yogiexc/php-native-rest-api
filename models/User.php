<?php
/**
 * USER MODEL
 */

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all()
    {
        $stmt = $this->db->query('SELECT * FROM users ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $this->validate($data);
        
        if ($this->emailExists($data['email'])) {
            throw new Exception('Email already exists');
        }
        
        $sql = 'INSERT INTO users (name, email, created_at) 
                VALUES (:name, :email, :created_at)';
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $this->validate($data);
        
        if (!$this->find($id)) {
            throw new Exception('User not found');
        }
        
        if ($this->emailExists($data['email'], $id)) {
            throw new Exception('Email already exists');
        }
        
        $sql = 'UPDATE users SET name = :name, email = :email WHERE id = :id';
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email']
        ]);
    }

    public function delete($id)
    {
        if (!$this->find($id)) {
            throw new Exception('User not found');
        }
        
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    private function validate($data)
    {
        if (empty($data['name'])) {
            throw new Exception('Name is required');
        }
        
        if (empty($data['email'])) {
            throw new Exception('Email is required');
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }
    }

    private function emailExists($email, $excludeId = null)
    {
        $sql = 'SELECT id FROM users WHERE email = :email';
        
        if ($excludeId) {
            $sql .= ' AND id != :exclude_id';
        }
        
        $stmt = $this->db->prepare($sql);
        $params = ['email' => $email];
        
        if ($excludeId) {
            $params['exclude_id'] = $excludeId;
        }
        
        $stmt->execute($params);
        
        return $stmt->fetch() !== false;
    }
    public function paginate($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        
        // Count total users
        $countStmt = $this->db->query('SELECT COUNT(*) as total FROM users');
        $total = $countStmt->fetch()['total'];
        
        // Get paginated users
        $sql = 'SELECT * FROM users ORDER BY id DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $users = $stmt->fetchAll();
        
        return [
            'data' => $users,
            'pagination' => Paginator::make($total, $page, $limit)
        ];
    }
    public function search($keyword, $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        
        // Count total results
        $countSql = 'SELECT COUNT(*) as total FROM users 
                     WHERE name LIKE :keyword OR email LIKE :keyword';
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute(['keyword' => "%$keyword%"]);
        $total = $countStmt->fetch()['total'];
        
        // Get paginated results
        $sql = 'SELECT * FROM users 
                WHERE name LIKE :keyword OR email LIKE :keyword
                ORDER BY id DESC 
                LIMIT :limit OFFSET :offset';
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $users = $stmt->fetchAll();
        
        return [
            'data' => $users,
            'pagination' => Paginator::make($total, $page, $limit),
            'search' => [
                'keyword' => $keyword,
                'found' => $total
            ]
        ];
    }
}