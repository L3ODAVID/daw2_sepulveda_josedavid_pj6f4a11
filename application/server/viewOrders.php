<?php

header("Content-Type: application/json");

$file = __DIR__ . "/../../orders/orders.json";

if (!file_exists($file)) {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "No orders found."]);
    exit;
}

$orders = json_decode(file_get_contents($file), true);
$id = $_POST["orderId"] ?? "";

if ($id !== "") {
    $orders = array_filter($orders, function($order) use ($id) {
        return $order["orderId"] == $id;
    });
}

if (empty($orders)) {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "Order not found."]);
    exit;
}

echo json_encode([
    "success" => true,
    "orders" => array_values($orders)
]);

?>
