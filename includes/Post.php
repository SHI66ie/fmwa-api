<?php
/**
 * Post Model Class
 * Handles post-related database operations
 * 
 * @package FMWA
 * @version 1.0.0
 */

require_once __DIR__ . '/Database.php';

class Post {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all published posts
     * 
     * @param int $limit Number of posts to retrieve
     * @param int $offset Offset for pagination
     * @param string $postType Type of post (post, news, event, page)
     * @return array
     */
    public function getPublished($limit = 10, $offset = 0, $postType = null) {
        $sql = "SELECT p.*, u.full_name as author_name, u.username as author_username
                FROM posts p
                INNER JOIN users u ON p.author_id = u.id
                WHERE p.status = 'published' AND p.published_at <= NOW()";
        
        if ($postType) {
            $sql .= " AND p.post_type = :post_type";
        }
        
        $sql .= " ORDER BY p.published_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->getPDO()->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        if ($postType) {
            $stmt->bindValue(':post_type', $postType, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get post by slug
     * 
     * @param string $slug Post slug
     * @return object|null
     */
    public function getBySlug($slug) {
        $sql = "SELECT p.*, u.full_name as author_name, u.username as author_username, u.avatar as author_avatar
                FROM posts p
                INNER JOIN users u ON p.author_id = u.id
                WHERE p.slug = ? AND p.status = 'published'";
        
        return $this->db->fetch($sql, [$slug]);
    }
    
    /**
     * Get post by ID
     * 
     * @param int $id Post ID
     * @return object|null
     */
    public function getById($id) {
        $sql = "SELECT p.*, u.full_name as author_name, u.username as author_username
                FROM posts p
                INNER JOIN users u ON p.author_id = u.id
                WHERE p.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Get featured posts
     * 
     * @param int $limit Number of posts to retrieve
     * @return array
     */
    public function getFeatured($limit = 5) {
        $sql = "SELECT p.*, u.full_name as author_name
                FROM posts p
                INNER JOIN users u ON p.author_id = u.id
                WHERE p.status = 'published' AND p.is_featured = 1 AND p.published_at <= NOW()
                ORDER BY p.published_at DESC
                LIMIT ?";
        
        return $this->db->query($sql, [$limit]);
    }
    
    /**
     * Get posts by category
     * 
     * @param int $categoryId Category ID
     * @param int $limit Number of posts
     * @param int $offset Offset for pagination
     * @return array
     */
    public function getByCategory($categoryId, $limit = 10, $offset = 0) {
        $sql = "SELECT p.*, u.full_name as author_name
                FROM posts p
                INNER JOIN users u ON p.author_id = u.id
                INNER JOIN post_categories pc ON p.id = pc.post_id
                WHERE pc.category_id = ? AND p.status = 'published' AND p.published_at <= NOW()
                ORDER BY p.published_at DESC
                LIMIT ? OFFSET ?";
        
        return $this->db->query($sql, [$categoryId, $limit, $offset]);
    }
    
    /**
     * Search posts
     * 
     * @param string $query Search query
     * @param int $limit Number of posts
     * @return array
     */
    public function search($query, $limit = 20) {
        $sql = "SELECT p.*, u.full_name as author_name,
                MATCH(p.title, p.content, p.excerpt) AGAINST(? IN NATURAL LANGUAGE MODE) as relevance
                FROM posts p
                INNER JOIN users u ON p.author_id = u.id
                WHERE MATCH(p.title, p.content, p.excerpt) AGAINST(? IN NATURAL LANGUAGE MODE)
                AND p.status = 'published' AND p.published_at <= NOW()
                ORDER BY relevance DESC, p.published_at DESC
                LIMIT ?";
        
        return $this->db->query($sql, [$query, $query, $limit]);
    }
    
    /**
     * Create new post
     * 
     * @param array $data Post data
     * @return int Post ID
     */
    public function create($data) {
        return $this->db->insert('posts', $data);
    }
    
    /**
     * Update post
     * 
     * @param int $id Post ID
     * @param array $data Post data
     * @return int Number of affected rows
     */
    public function update($id, $data) {
        return $this->db->update('posts', $data, 'id = ?', [$id]);
    }
    
    /**
     * Delete post
     * 
     * @param int $id Post ID
     * @return int Number of affected rows
     */
    public function delete($id) {
        return $this->db->delete('posts', 'id = ?', [$id]);
    }
    
    /**
     * Increment post views
     * 
     * @param int $id Post ID
     * @return bool
     */
    public function incrementViews($id) {
        $sql = "UPDATE posts SET views = views + 1 WHERE id = ?";
        $stmt = $this->db->getPDO()->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Get post categories
     * 
     * @param int $postId Post ID
     * @return array
     */
    public function getCategories($postId) {
        $sql = "SELECT c.* FROM categories c
                INNER JOIN post_categories pc ON c.id = pc.category_id
                WHERE pc.post_id = ?";
        
        return $this->db->query($sql, [$postId]);
    }
    
    /**
     * Attach categories to post
     * 
     * @param int $postId Post ID
     * @param array $categoryIds Array of category IDs
     * @return bool
     */
    public function attachCategories($postId, $categoryIds) {
        // First, remove existing categories
        $this->db->delete('post_categories', 'post_id = ?', [$postId]);
        
        // Then add new categories
        foreach ($categoryIds as $categoryId) {
            $this->db->insert('post_categories', [
                'post_id' => $postId,
                'category_id' => $categoryId
            ]);
        }
        
        return true;
    }
    
    /**
     * Get recent posts
     * 
     * @param int $limit Number of posts
     * @return array
     */
    public function getRecent($limit = 5) {
        return $this->getPublished($limit, 0);
    }
    
    /**
     * Get related posts
     * 
     * @param int $postId Current post ID
     * @param int $limit Number of posts
     * @return array
     */
    public function getRelated($postId, $limit = 5) {
        $sql = "SELECT DISTINCT p.*, u.full_name as author_name
                FROM posts p
                INNER JOIN users u ON p.author_id = u.id
                INNER JOIN post_categories pc ON p.id = pc.post_id
                WHERE pc.category_id IN (
                    SELECT category_id FROM post_categories WHERE post_id = ?
                )
                AND p.id != ? AND p.status = 'published' AND p.published_at <= NOW()
                ORDER BY p.published_at DESC
                LIMIT ?";
        
        return $this->db->query($sql, [$postId, $postId, $limit]);
    }
    
    /**
     * Count total posts
     * 
     * @param string $status Post status
     * @return int
     */
    public function count($status = 'published') {
        return $this->db->count('posts', 'status = ?', [$status]);
    }
}
