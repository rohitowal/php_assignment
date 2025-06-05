 
    <?php
        parse_str($_SERVER['QUERY_STRING'], $_GET);
        use Controllers\OrderController;

        $method = $_SERVER['REQUEST_METHOD'];

        $data = json_decode(file_get_contents("php://input"), true);

        switch ($method) {
            // case 'GET':
            //     OrderController::getAll();
            //     break;
            case 'POST':
                OrderController::confirmOrder($data);
                break;
            // case 'PUT':
            //     OrderController::update($data,$_GET['id'] ?? null);
            //     break;
            // case 'DELETE':
            //     OrderController::delete($_GET['id'] ?? null);
            //     break;
            default:
                echo json_encode(["error" => "Invalid request method"]);
        }
    ?> 
