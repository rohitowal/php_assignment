<?php
namespace Controllers;

use Services\UserService;

/**
 * Class UserController
 * 
 * Handles user-related operations including:
 * - Retrieving user lists
 * - Creating new users
 * - User data management
 */
class UserController {
    /**
     * Retrieve and return all users
     * 
     * @return void Outputs JSON encoded list of all users
     */
    public static function getAll() {
        $users = UserService::getAll();
        echo json_encode($users, JSON_PRETTY_PRINT);
    }

    /**
     * Create a new user
     * 
     * @param array $data User data including required fields
     * @return void Outputs JSON encoded response
     */
    public static function create($data) {
        $response = UserService::create($data);
        echo json_encode($response);
    }
}
