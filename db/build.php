<?php

// This service is a internal service only (enabled for testing)
// This endpoint deletes and rebuilds the database required
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

// Delete existing tables
$db->query("DROP TABLE IF EXISTS `users`;");
$db->query("DROP TABLE IF EXISTS `scores`;");

// Rebuild tables
// NOTE: Technically they could be one table but this is for
//       future proofing of new features
$sql = "CREATE TABLE users (
    user_id VARCHAR(30),
    country VARCHAR(10),
    locale VARCHAR(10)
);";
$db->query($sql);

$sql = "CREATE TABLE scores (
    user_id VARCHAR(30),
    high_score INT,
    improvement INT,
    last_played INT
);";
$db->query($sql);

// Create indexes
$db->query("CREATE INDEX idx_score ON scores(high_score);");
$db->query("CREATE INDEX idx_time ON scores(last_played);");
$db->query("CREATE INDEX idx_improvement ON scores(improvement, last_played);");

// Generate response
header('Content-Type: application/json');
echo json_encode(array("status" => "ok", "code" => 200));
exit;
