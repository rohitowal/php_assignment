<?php
include_once(__DIR__ . '/../repositories/UserRepository.php');
include_once(__DIR__ . '/../models/User.php');
include_once(__DIR__ . '/../config/dbconfig.php');

class UserService {
    public static function getAll() {
        global $connection;
        $repo = new UserRepository($connection);
        return $repo->getAllUsers();
    }

    public static function create($data) {
        global $connection;
        $user = new User(null, $data['name'], $data['country']);
        $repo = new UserRepository($connection);
        $repo->create($user);
        return ["message" => "User created"];
    }
}
