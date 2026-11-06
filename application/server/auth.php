<?php

header("Content-Type: application/json");


function sendResponse($success, $message, $statusCode) {
    http_response_code($statusCode);

    echo json_encode([
        "success" => $success,
        "message" => $message
    ]);

    exit;
}

function authenticateUser($username, $password) {

    $file = __DIR__ . "/../../users/users.json";

    if (!file_exists($file)) {
        sendResponse(false, "Users file not found.", 404);
    }

    $users = json_decode(file_get_contents($file), true);

    if (!is_array($users)) {
        sendResponse(false, "Unable to read users file.", 500);
    }

    foreach ($users as $user) {

        if (
            $user["user"] === $username &&
            password_verify($password, $user["password"])
        ) {
            sendResponse(true, "Login successful.", 200);
        }
    }

    sendResponse(false, "Incorrect username or password.", 401);
}


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendResponse(false, "Method not allowed.", 405);
}


$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";


if ($username === "" || $password === "") {
    sendResponse(false, "Please enter username and password.", 400);
}


authenticateUser($username, $password);

?>
