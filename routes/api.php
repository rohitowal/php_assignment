
    <?php
        parse_str($_SERVER['QUERY_STRING'], $_GET);
        include_once(__DIR__ . '/../controllers/ProductController.php');

        $method = $_SERVER['REQUEST_METHOD'];

        $data = json_decode(file_get_contents("php://input"), true);

        switch ($method) {
            case 'GET':
                ProductController::getAll();
                break;
            case 'POST':
                ProductController::create($data);
                break;
            case 'PUT':
                ProductController::update($data,$_GET['id'] ?? null);
                break;
            case 'DELETE':
                ProductController::delete($_GET['id'] ?? null);
                break;
            default:
                echo json_encode(["error" => "Invalid request method"]);
        }
    ?>

