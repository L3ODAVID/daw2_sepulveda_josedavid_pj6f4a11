<?php

header("Content-Type: application/json");

// Customer data
$orderId = $_POST["orderId"] ?? "";
$name = $_POST["customerName"] ?? "";
$address = $_POST["address"] ?? "";
$email = $_POST["email"] ?? "";
$phone = $_POST["phone"] ?? "";

if (!$orderId || !$name || !$address || !$email || !$phone) {
    echo json_encode(["success" => false, "message" => "Complete all fields."]);
    exit;
}

// Products and prices
$catalogue = [
    0 => ["Classic T-Shirt", 20],
    1 => ["Basic Hoodie", 35],
    2 => ["Denim Jeans", 45],
    3 => ["Summer Dress", 50]
];

$products = [];
$subtotal = 0;

foreach ($catalogue as $i => $item) {
    if (isset($_POST["products"][$i]["active"])) {
        $quantity = (int) $_POST["products"][$i]["quantity"];

        if ($quantity < 1) {
            echo json_encode(["success" => false, "message" => "Invalid quantity."]);
            exit;
        }

        $price = $item[1];
        $total = $price * $quantity;
        $subtotal += $total;

        $products[] = [
            "name" => $item[0],
            "price" => $price,
            "quantity" => $quantity,
            "total" => $total
        ];
    }
}

if (empty($products)) {
    echo json_encode(["success" => false, "message" => "Select a product."]);
    exit;
}

// Calculate VAT
$vat = round($subtotal * 0.21, 2);
$total = $subtotal + $vat;

// Create order
$order = [
    "orderId" => $orderId,
    "customerName" => $name,
    "address" => $address,
    "email" => $email,
    "phone" => $phone,
    "products" => $products,
    "subtotal" => $subtotal,
    "vat" => $vat,
    "totalWithVat" => $total
];

// Save order
$file = __DIR__ . "/../../orders/orders.json";
$orders = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

if (!is_array($orders)) {
    $orders = [];
}

$orders[] = $order;

file_put_contents($file, json_encode($orders, JSON_PRETTY_PRINT), LOCK_EX);

echo json_encode([
    "success" => true,
    "message" => "Order created successfully."
]);

?>
