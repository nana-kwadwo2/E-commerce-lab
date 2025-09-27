$(document).ready(function() {
    let editMode = false;
    let currentEditId = null;

    // Load categories on page load
    loadCategories();

    // Form submission handler
    $('#category-form').submit(function(e) {
        e.preventDefault();
        
        const catName = $('#cat_name').val().trim();
        
        if (!catName) {
            showError('Category name is required');
            return;
        }

        if (catName.length > 100) {
            showError('Category name must be 100 characters or less');
            return;
        }

        if (editMode) {
            updateCategory(currentEditId, catName);
        } else {
            addCategory(catName);
        }
    });

    // Cancel edit handler
    $('#cancel-btn').click(function() {
        cancelEdit();
    });

    // Load categories function
    function loadCategories() {
        $.ajax({
            url: '../actions/fetch_category_action.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    displayCategories(response.categories);
                } else {
                    $('#categories-list').html(
                        '<div class="alert alert-danger">' + response.message + '</div>'
                    );
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading categories:', error);
                $('#categories-list').html(
                    '<div class="alert alert-danger">Error loading categories. Please try again.</div>'
                );
            }
        });
    }

    // Display categories function
    function displayCategories(categories) {
        if (!categories || categories.length === 0) {
            $('#categories-list').html(
                '<div class="alert alert-info">No categories found. Add your first category!</div>'
            );
            return;
        }

        let html = '<div class="row">';
        categories.forEach(function(category) {
            html += `
                <div class="col-md-6 mb-3">
                    <div class="card category-card">
                        <div class="card-body">
                            <h5 class="card-title">${escapeHtml(category.cat_name)}</h5>
                            <p class="card-text text-muted small">
                                ID: ${category.cat_id}
                                ${category.created_by_name ? `<br>Created by: ${escapeHtml(category.created_by_name)}` : ''}
                            </p>
                            <div class="action-btns">
                                <button class="btn btn-sm btn-warning" onclick="editCategory(${category.cat_id}, '${escapeHtml(category.cat_name)}')">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteCategory(${category.cat_id}, '${escapeHtml(category.cat_name)}')">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        $('#categories-list').html(html);
    }

    // Add category function
    function addCategory(catName) {
        const submitBtn = $('#submit-btn');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Adding...');

        $.ajax({
            url: '../actions/add_category_action.php',
            type: 'POST',
            data: {
                cat_name: catName
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    showSuccess('Category added successfully!');
                    $('#category-form')[0].reset();
                    loadCategories();
                } else {
                    showError(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error adding category:', error);
                showError('Error adding category. Please try again.');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    }

    // Update category function
    function updateCategory(catId, catName) {
        const submitBtn = $('#submit-btn');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

        $.ajax({
            url: '../actions/update_category_action.php',
            type: 'POST',
            data: {
                cat_id: catId,
                cat_name: catName
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    showSuccess('Category updated successfully!');
                    cancelEdit();
                    loadCategories();
                } else {
                    showError(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating category:', error);
                showError('Error updating category. Please try again.');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    }

    // Delete category function
    function deleteCategory(catId, catName) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete category: "${catName}". This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '../actions/delete_category_action.php',
                    type: 'POST',
                    data: {
                        cat_id: catId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Category has been deleted successfully.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            loadCategories();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting category:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error deleting category. Please try again.'
                        });
                    }
                });
            }
        });
    }

    // Edit category function (global for onclick)
    window.editCategory = function(catId, catName) {
        editMode = true;
        currentEditId = catId;
        
        $('#cat_name').val(catName);
        $('#submit-btn').html('<i class="fa fa-save"></i> Update Category');
        $('#cancel-btn').show();
        
        $('html, body').animate({
            scrollTop: $('#category-form').offset().top - 20
        }, 500);
    };

    // Cancel edit function
    function cancelEdit() {
        editMode = false;
        currentEditId = null;
        
        $('#category-form')[0].reset();
        $('#submit-btn').html('<i class="fa fa-plus"></i> Add Category');
        $('#cancel-btn').hide();
    }

    // Helper functions
    function showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: message,
            timer: 2000,
            showConfirmButton: false
        });
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message
        });
    }

    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});