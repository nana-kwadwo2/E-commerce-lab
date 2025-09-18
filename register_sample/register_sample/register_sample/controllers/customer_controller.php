<?php

require_once '../classes/customer_class.php';

/**
 * Register a new customer
 */
function register_customer_ctr($name, $email, $password, $country, $city, $contact, $role = 2)
{
    $customer = new Customer();
    $customer_id = $customer->addCustomer($name, $email, $password, $country, $city, $contact, $role);
    
    if ($customer_id) {
        return $customer_id;
    }
    return false;
}

/**
 * Get customer by email
 */
function get_customer_by_email_ctr($email)
{
    $customer = new Customer();
    return $customer->getCustomerByEmail($email);
}

/**
 * Edit customer information
 */
function edit_customer_ctr($customer_id, $name, $email, $country, $city, $contact)
{
    $customer = new Customer();
    return $customer->editCustomer($customer_id, $name, $email, $country, $city, $contact);
}

/**
 * Delete customer
 */
function delete_customer_ctr($customer_id)
{
    $customer = new Customer();
    return $customer->deleteCustomer($customer_id);
}

/**
 * Verify customer login
 */
function verify_customer_login_ctr($email, $password)
{
    $customer = new Customer();
    return $customer->verifyPassword($email, $password);
}