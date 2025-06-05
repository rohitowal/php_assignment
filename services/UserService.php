<?php
namespace Services;

use Repositories\UserRepository;
use Models\User;
use Config\dbconfig;
/**
 * Class UserService
 * 
 * Service layer for handling user-related business logic.
 * Provides an abstraction between controllers and data access layer.
 */
class UserService {
    /**
     * Retrieve all users from the database
     * 
     * @return array List of all users
     */
    public static function getAll() {
        global $connection;
        $repo = new UserRepository($connection);
        return $repo->getAllUsers();
    }

    /**
     * Create a new user in the database
     * 
     * @param array $data User data containing:
     *                    - name: User's name
     *                    - country: User's country
     * @return array Response message indicating success
     */
    public static function create($data) {
        global $connection;
        $user = new User(null, $data['name'], $data['country']);
        $repo = new UserRepository($connection);
        $repo->create($user);
        return ["message" => "User created"];
    }
}
