<?php
/**
 * USER MODEL
 * Tambahkan method paginate()
 */

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // ... existing methods (all, find, create, update, delete) ...

    /**
     * Get users dengan pagination
     * 
     * @param int $page
     * @param int $limit
     * @return array
     */
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
}