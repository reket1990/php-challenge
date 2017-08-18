<?php

// This service is a internal service only (enabled for testing)
// This endpoint populates the database with fake data
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

// TODO: Check tables exist

// Populate users table
for ($i = 0; $i < 100; $i++) { // 100 chunks of 10,000 is 1,000,000
    $sql = "INSERT INTO `users` (`user_id`, `country`, `locale`) VALUES ";
    // Construct query 10,000 at a time
    for ($j = 1; $j <= 10000; $j++) {
        $user_id = $i * 10000 + $j;
        $sql .= "(\"$user_id\", \"us\", \"en_US\"), ";
    }
    // Remove trailing comma and add semicolon
    $sql = substr($sql, 0, -2);
    $sql .= ";";
    $db->query($sql);
}

// Populate scores table (randomly)
for ($i = 0; $i < 100; $i++) { // 100 chunks of 10,000 is 1,000,000
    $sql = "INSERT INTO `scores` (`user_id`, `high_score`, `improvement`, `last_played`) VALUES ";
    // Construct query 10,000 at a time
    for ($j = 1; $j <= 10000; $j++) {
        $user_id = $i * 10000 + $j;
        $score = rand(10000, 100000);
        $improvement = rand(0, 10000);
        $time = rand(time() - 2592000, time()); // any time within last 30 days
        $sql .= "(\"$user_id\", $score, $improvement, $time), ";
    }
    // Remove trailing comma and add semicolon
    $sql = substr($sql, 0, -2);
    $sql .= ";";
    $db->query($sql);
}

// Generate response
header('Content-Type: application/json');
echo json_encode(array("status" => "ok", "code" => 200));
exit;
