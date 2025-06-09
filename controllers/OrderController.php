<?php 
namespace Controllers;

use Services\OrderService;
use Utils\Logger;

/**
 * Class OrderController
 * 
 * Responsible for order logic
 */
class OrderController {

    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Handle the order confirmation logic 
     * 
     * @param mysqli $connection Database connection object.
     * 
     * @return void
     */
    public function confirmOrder($connection) {
        Logger::info("OrderController::confirmOrder() called");

        // Detect if API client expects JSON
        $acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';
        $wantsJson = strpos($acceptHeader, 'application/json') !== false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Logger::info("Request method is POST");

            // Getting cart and user details from the session
            $cart = $_SESSION['cart'] ?? [];
            $userId = $_SESSION['user_id'] ?? null;

            Logger::info("User ID: " . ($userId ?? 'null'));
            Logger::info("Cart contents: " . json_encode($cart));

            if (!empty($cart)) {
                Logger::info("Cart is not empty. Proceeding to create order.");
                try {
                   // $orderData = OrderService::createOrder($connection, $userId, $cart);
                    $orderData = $this->orderService->createOrder($connection,$userId, $cart);
                    Logger::info("Order created successfully for user ID: {$userId}");

                    // Clear session
                    unset($_SESSION['cart']);
                    unset($_SESSION['country']);
                    unset($_SESSION['conversion_rate']);
                    unset($_SESSION['currency_info']);

                    $_SESSION['order_confirmed'] = true;
                    Logger::info("Session data cleared after order.");

                    // Return based on request type
                    if ($wantsJson) {
                        header('Content-Type: application/json');
                        echo json_encode([
                            'status' => 'success',
                            'message' => 'Order placed successfully.',
                            'order' => $orderData,
                        ]);
                        exit;
                    } else {
                        header("Location: index.php");
                        exit;
                    }

                } catch (\Exception $e) {
                    Logger::error("Failed to create order: " . $e->getMessage());

                    if ($wantsJson) {
                        header('Content-Type: application/json');
                        http_response_code(500);
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Order failed: ' . $e->getMessage(),
                        ]);
                        exit;
                    }
                }
            } else {
                Logger::info("Cart is empty. Order not created.");

                if ($wantsJson) {
                    header('Content-Type: application/json');
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Cart is empty.',
                    ]);
                    exit;
                }
            }
        } else {
            Logger::info("Invalid request method: " . $_SERVER['REQUEST_METHOD']);

            if ($wantsJson) {
                header('Content-Type: application/json');
                http_response_code(405);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Method not allowed. Use POST.',
                ]);
                exit;
            }
        }
    }
}
?>