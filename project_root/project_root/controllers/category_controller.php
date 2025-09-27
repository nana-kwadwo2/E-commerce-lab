<?php
require_once '../classes/category_class.php';

/**
 * Add category controller function
 */
function add_category_ctr($cat_name, $created_by = null)
{
    $category = new Category();
    $category_id = $category->add_category($cat_name, $created_by);
    
    if ($category_id) {
        return array('status' => 'success', 'category_id' => $category_id);
    }
    
    return array('status' => 'error', 'message' => 'Category name already exists or failed to add category');
}

/**
 * Get categories controller function
 */
function get_categories_ctr()
{
    $category = new Category();
    $categories = $category->get_categories();
    
    if ($categories !== false) {
        return array('status' => 'success', 'categories' => $categories);
    }
    
    return array('status' => 'error', 'message' => 'Failed to fetch categories');
}

/**
 * Update category controller function
 */
function update_category_ctr($cat_id, $cat_name)
{
    $category = new Category();
    $result = $category->update_category($cat_id, $cat_name);
    
    if ($result) {
        return array('status' => 'success', 'message' => 'Category updated successfully');
    }
    
    return array('status' => 'error', 'message' => 'Category name already exists or failed to update');
}

/**
 * Delete category controller function
 */
function delete_category_ctr($cat_id)
{
    $category = new Category();
    $result = $category->delete_category($cat_id);
    
    if ($result) {
        return array('status' => 'success', 'message' => 'Category deleted successfully');
    }
    
    return array('status' => 'error', 'message' => 'Failed to delete category');
}

/**
 * Get single category controller function
 */
function get_category_ctr($cat_id)
{
    $category = new Category();
    $category_data = $category->get_category($cat_id);
    
    if ($category_data) {
        return array('status' => 'success', 'category' => $category_data);
    }
    
    return array('status' => 'error', 'message' => 'Category not found');
}
?>