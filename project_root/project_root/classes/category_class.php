<?php
require_once 'db_class.php';

class Category extends db_connection
{
    /**
     * Add new category
     */
    public function add_category($cat_name, $created_by = null)
    {
        echo "<!-- Debug: add_category called with: $cat_name -->";
        
        // Check if category name already exists
        if ($this->category_exists($cat_name)) {
            return false;
        }
        
        // Ensure database connection
        if (!$this->db_connect()) {
            echo "<!-- Debug: DB connection failed -->";
            return false;
        }
        
        $cat_name = mysqli_real_escape_string($this->db_conn(), $cat_name);
        
        // Simple insert without created_by for now
        $sql = "INSERT INTO categories (cat_name) VALUES ('$cat_name')";
        
        echo "<!-- Debug: SQL: $sql -->";
        
        if ($this->db_write_query($sql)) {
            return $this->last_insert_id();
        }
        return false;
    }

    /**
     * Check if category name exists
     */
    public function category_exists($cat_name)
    {
        if (!$this->db_connect()) {
            return false;
        }
        
        $cat_name = mysqli_real_escape_string($this->db_conn(), $cat_name);
        $sql = "SELECT cat_id FROM categories WHERE cat_name = '$cat_name'";
        $result = $this->db_fetch_one($sql);
        return ($result !== false && $result !== null);
    }

    /**
     * Get all categories
     */
    public function get_categories()
    {
        echo "<!-- Debug: get_categories called -->";
        
        if (!$this->db_connect()) {
            echo "<!-- Debug: DB connection failed in get_categories -->";
            return false;
        }
        
        $sql = "SELECT * FROM categories ORDER BY cat_name";
        echo "<!-- Debug: SQL: $sql -->";
        
        $result = $this->db_fetch_all($sql);
        echo "<!-- Debug: Result: " . print_r($result, true) . " -->";
        
        return $result;
    }

    /**
     * Get category by ID
     */
    public function get_category($cat_id)
    {
        if (!$this->db_connect()) {
            return false;
        }
        
        $cat_id = intval($cat_id);
        $sql = "SELECT * FROM categories WHERE cat_id = $cat_id";
        return $this->db_fetch_one($sql);
    }

    /**
     * Update category
     */
    public function update_category($cat_id, $cat_name)
    {
        if (!$this->db_connect()) {
            return false;
        }
        
        $cat_id = intval($cat_id);
        $cat_name = mysqli_real_escape_string($this->db_conn(), $cat_name);
        
        $sql = "UPDATE categories SET cat_name = '$cat_name' WHERE cat_id = $cat_id";
        return $this->db_write_query($sql);
    }

    /**
     * Delete category
     */
    public function delete_category($cat_id)
    {
        if (!$this->db_connect()) {
            return false;
        }
        
        $cat_id = intval($cat_id);
        $sql = "DELETE FROM categories WHERE cat_id = $cat_id";
        return $this->db_write_query($sql);
    }
}
?>