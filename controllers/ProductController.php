
<?php
    include_once(__DIR__ . '/../config/dbconfig.php');

    class ProductController{

        //get
        public static function getAll(){
            global $connection;

            $query = "SELECT * FROM product";
            $result = mysqli_query($connection,$query);

            $products = [];

            while($row = mysqli_fetch_assoc($result)){
                $products[] = $row;
            }
            echo json_encode($products,JSON_PRETTY_PRINT);
        }

        //post
        public static function create($data) {
            global $connection;

            $id = intval($data['id']);
            $name = $data['name'];
            $description = $data['description'];
            $price = floatval($data['price']);

            $stmt = $connection->prepare("INSERT INTO product (id, name, description, price) VALUES (?, ?, ?, ?)");

            if ($stmt === false) {
                echo json_encode(["error" => $connection->error]);
                return;
            }

            $stmt->bind_param("issd", $id, $name, $description, $price);

            if ($stmt->execute()) {
                echo json_encode(["message" => "Product created"]);
            } else {
                echo json_encode(["error" => $stmt->error]);
            }

            $stmt->close();
        }


        //PUT
        public static function update($data, $id) {
            global $connection;

            if (!$id) {
                echo json_encode(["error" => "Missing id in URL"]);
                return;
            }

            $name = $data['name'];
            $description = $data['description'];
            $price = floatval($data['price']);

            $stmt = $connection->prepare("UPDATE product SET name = ?, description = ?, price = ? WHERE id = ?");

            if ($stmt === false) {
                echo json_encode(["error" => $connection->error]);
                return;
            }

            $stmt->bind_param("ssdi", $name, $description, $price, $id);

            if ($stmt->execute()) {
                echo json_encode(["message" => "Product updated"]);
            } else {
                echo json_encode(["error" => $stmt->error]);
            }

            $stmt->close();
        }



        //delete
        public static function delete($id) {
            global $connection;

            if (!$id) {
                echo json_encode(["error" => "Missing id in URL"]);
                return;
            }

            $stmt = $connection->prepare("DELETE FROM product WHERE id = ?");

            if ($stmt === false) {
                echo json_encode(["error" => $connection->error]);
                return;
            }

            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                if($stmt->affected_rows > 0){
                    echo json_encode(["message" => "Product deleted"]);
                }else{
                    echo json_encode(["error" => "no product found with given id]"]);
                }
                
            } else {
                echo json_encode(["error" => $stmt->error]);
            }

            $stmt->close();
        }


    }

?>
