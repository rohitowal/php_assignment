<?php

include_once(__DIR__ . '/../config/dbconfig.php');

class UserController{

    public static function getAll(){

        global $connection;

        $query = "SELECT * FROM user";
        $result = mysqli_query($connection,$query);

        $users = [];

        while($row = mysqli_fetch_assoc($result)){
                $users[] = $row;
            }
            echo json_encode($users,JSON_PRETTY_PRINT);
    }

    public static function create($data){
        global $connection;

        $name = $data['name'];
        $country = $data['country'];

        $stmt = $connection->prepare("INSERT INTO user (name,country) VALUES (?,?)");

        if ($stmt === false) {
                echo json_encode(["error" => $connection->error]);
                return;
        }

        $stmt->bind_param("ss", $name, $country);

        if($stmt->execute()){
            echo json_encode(["message" => "user created"]);
        }else{
            echo json_encode(["error" => $stmt->error]);
        }
        $stmt->close();
    }



}


?>