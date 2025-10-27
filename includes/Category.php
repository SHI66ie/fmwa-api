<?php
/**
 * Category Model Class
 * Handles category-related database operations
 * 
 * @package FMWA
 * @version 1.0.0
 */

require_once __DIR__ . '/Database.php';

class Category {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all active categories
     * 
     * @return array
     */
    public function getAll() {
        return $this->db->query("SELECT * FROM categories WHERE status = 'active' ORDER BY display_order, name");
    }
    
    /**
     * Get category by ID
     * 
     * @param int $id Category ID
     * @return object|null
     */
    public function getById($id) {
        return $this->db->find('categories', $id);
    }
    
    /**
     * Get category by slug
     * 
     * @param string $slug Category slug
     * @return object|null
     */
    public function getBySlug($slug) {
        return $this->db->fetch("SELECT * FROM categories WHERE slug = ?", [$slug]);
    }
    
    /**
     * Create new category
     * 
     * @param array $data Category data
     * @return int Category ID
     */
    public function create($data) {
        return $this->db->insert('categories', $data);
    }
    
    /**
     * Update category
     * 
     * @param int $id Category ID
     * @param array $data Category data
     * @return int Number of affected rows
     */
    public function update($id, $data) {
        return $this->db->update('categories', $data, 'id = ?', [$id]);
    }
    
    /**
     * Delete category
     * 
     * @param int $id Category ID
     * @return int Number of affected rows
     */
    public function delete($id) {
        return $this->db->delete('categories', 'id = ?', [$id]);
    }
    
    /**
     * Get post count for category
     * 
     * @param int $id Category ID
     * @return int
     */
    public function getPostCount($id) {
        $sql = "SELECT COUNT(*) as count FROM post_categories WHERE category_id = ?";
        $result = $this->db->fetch($sql, [$id]);
        return (int) $result->count;
    }
    
    /**
     * Get categories with post counts
     * 
     * @return array
     */
    public function getAllWithCounts() {
        $sql = "SELECT c.*, COUNT(pc.post_id) as post_count
                FROM categories c
                LEFT JOIN post_categories pc ON c.id = pc.category_id
                WHERE c.status = 'active'
                GROUP BY c.id
                ORDER BY c.display_order, c.name";
        
        return $this->db->query($sql);
    }
}
