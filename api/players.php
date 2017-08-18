<?php

// This endpoint gets the total number of players
// TODO: Error handling

require_once '../config.php';

// Make sure GET request
$method = $_SERVER['REQUEST_METHOD'];
if ($method != 'GET') {
    header('Content-Type: application/json');
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode(array("status" => "error", "code" => 405, "messages" => ["Method Not Allowed - GET Request Only"]));
    exit;
}

// Connect to database
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Get number of players
$result = $db->query("SELECT COUNT(*) FROM users;");
$row = mysqli_fetch_row($result);
$num_players = intval($row[0]);

// Generate response
header('Content-Type: application/json');
$data = array('num_players' => $num_players);
echo json_encode(array("status" => "ok", "code" => 200, "data" => $data));
exit;
