<?php

// This service is a internal service only (enabled for testing)
// This endpoint deletes and rebuilds the database required

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
    last_played TIME
);";
$db->query($sql);

// Create indexes
$db->query("CREATE INDEX idx_score ON scores(high_score);");
$db->query("CREATE INDEX idx_time ON scores(last_played);");
$db->query("CREATE INDEX idx_improvement ON scores(improvement, last_played);");

// Success message
// TODO: Error handling
echo "200: Success";
exit;