
    <?php
        parse_str($_SERVER['QUERY_STRING'], $_GET);
        
        use Controllers\UserController;

        $method = $_SERVER['REQUEST_METHOD'];

        $data = json_decode(file_get_contents("php://input"), true);

        switch ($method) {
            case 'GET':
                UserController::getAll();
                break;
            case 'POST':
                UserController::create($data);
                break;
            // case 'PUT':
            //     UserController::update($data,$_GET['id'] ?? null);
            //     break;
            // case 'DELETE':
            //     UserController::delete($_GET['id'] ?? null);
            //     break;
            default:
                echo json_encode(["error" => "Invalid request method"]);
        }
    ?>

