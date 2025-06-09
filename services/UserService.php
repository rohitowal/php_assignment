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

    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    
    /**
     * Retrieve all users from the database
     * 
     * @return array List of all users
     */
    public function getAll() {
        global $connection;
        return $this->userRepo->getAllUsers();
    }

    /**
     * Create a new user in the database
     * 
     * @param array $data User data containing:
     *                    - name: User's name
     *                    - country: User's country
     * @return array Response message indicating success
     */
    public function create($data) {
        global $connection;
        $user = new User(null, $data['name'], $data['country']);
        $this->userRepo->create($user);
        return ["message" => "User created"];
    }
}
