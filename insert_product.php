<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        include('dbconfig.php');

        $data = json_decode(file_get_contents("php://input"),true); 

        if(isset($data['id']) && isset($data['name']) && isset($data['description']) && isset($data['price']) ){
            $id = intval($data['id']);
            $name = mysqli_real_escape_string($connection,$data['name']);
            $description = mysqli_real_escape_string($connection,$data['description']);
            $price = floatval($data['price']);

            $query = "INSERT INTO PRODUCT (id,name,description,price) VALUES ($id,'$name','$description',$price)";
            
            if (mysqli_query($connection, $query)) {
                echo json_encode(["status" => "success", "message" => "Product inserted successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Database error: " . mysqli_error($connection)]);
            }
        }else{
            echo json_encode(["status" => "error", "message" => "Missing required fields (id, name, description, price)"]);
        }


    ?>
</body>
</html>