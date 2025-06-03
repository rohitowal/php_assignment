<?php

class UserRepository {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function getAllUsers() {
        $query = "SELECT * FROM user";
        $result = mysqli_query($this->connection, $query);
        $users = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }

        return $users;
    }

    public function create(User $user) {
        $stmt = $this->connection->prepare("INSERT INTO user (name, country) VALUES (?, ?)");
        $stmt->bind_param("ss", $user->name, $user->country);
        $stmt->execute();
        $stmt->close();
    }
}
