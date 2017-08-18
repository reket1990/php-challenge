<?php

// This endpoint gets the total number of players
// TODO: Error handling

require_once '../config.php';

// Make sure GET request
$method = $_SERVER['REQUEST_METHOD'];
if ($method != 'GET') {
    header("HTTP/1.0 405 Method Not Allowed");
    echo "405: Method Not Allowed";
    exit;
}

// Connect to database
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Get number of players
$result = $db->query("SELECT COUNT(*) FROM users;");
$row = mysqli_fetch_row($result);
$num_players = $row[0];

// Return as JSON
header('Content-Type: application/json');
echo json_encode(array('num_players' => $num_players));
exit;
