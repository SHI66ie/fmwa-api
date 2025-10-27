<?php
/**
 * Media Model Class
 * Handles media file operations
 * 
 * @package FMWA
 * @version 1.0.0
 */

require_once __DIR__ . '/Database.php';

class Media {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all media files
     * 
     * @param int $limit Number of files
     * @param int $offset Offset for pagination
     * @return array
     */
    public function getAll($limit = 50, $offset = 0) {
        $sql = "SELECT m.*, u.full_name as uploader_name
                FROM media m
                INNER JOIN users u ON m.uploaded_by = u.id
                ORDER BY m.created_at DESC
                LIMIT ? OFFSET ?";
        
        return $this->db->query($sql, [$limit, $offset]);
    }
    
    /**
     * Get media by ID
     * 
     * @param int $id Media ID
     * @return object|null
     */
    public function getById($id) {
        $sql = "SELECT m.*, u.full_name as uploader_name
                FROM media m
                INNER JOIN users u ON m.uploaded_by = u.id
                WHERE m.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Get media by type
     * 
     * @param string $type File type (image, video, document)
     * @param int $limit Number of files
     * @return array
     */
    public function getByType($type, $limit = 50) {
        $sql = "SELECT m.*, u.full_name as uploader_name
                FROM media m
                INNER JOIN users u ON m.uploaded_by = u.id
                WHERE m.file_type = ?
                ORDER BY m.created_at DESC
                LIMIT ?";
        
        return $this->db->query($sql, [$type, $limit]);
    }
    
    /**
     * Search media files
     * 
     * @param string $query Search query
     * @return array
     */
    public function search($query) {
        $searchTerm = "%{$query}%";
        $sql = "SELECT m.*, u.full_name as uploader_name
                FROM media m
                INNER JOIN users u ON m.uploaded_by = u.id
                WHERE m.title LIKE ? OR m.original_filename LIKE ? OR m.description LIKE ?
                ORDER BY m.created_at DESC";
        
        return $this->db->query($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }
    
    /**
     * Upload new media file
     * 
     * @param array $data Media data
     * @return int Media ID
     */
    public function upload($data) {
        return $this->db->insert('media', $data);
    }
    
    /**
     * Update media metadata
     * 
     * @param int $id Media ID
     * @param array $data Media data
     * @return int Number of affected rows
     */
    public function update($id, $data) {
        return $this->db->update('media', $data, 'id = ?', [$id]);
    }
    
    /**
     * Delete media file
     * 
     * @param int $id Media ID
     * @return int Number of affected rows
     */
    public function delete($id) {
        return $this->db->delete('media', 'id = ?', [$id]);
    }
    
    /**
     * Count media files
     * 
     * @param string $type File type (optional)
     * @return int
     */
    public function count($type = null) {
        if ($type) {
            return $this->db->count('media', 'file_type = ?', [$type]);
        }
        return $this->db->count('media');
    }
}
