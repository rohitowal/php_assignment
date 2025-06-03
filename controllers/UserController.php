<?php
include_once(__DIR__ . '/../services/UserService.php');

class UserController {
    public static function getAll() {
        $users = UserService::getAll();
        echo json_encode($users, JSON_PRETTY_PRINT);
    }

    public static function create($data) {
        $response = UserService::create($data);
        echo json_encode($response);
    }
}
