<?php

// This endpoint gets the players with the highest score
// Optional query parameter: count
// TODO: Error handling

require_once '../config.php';

// Make sure GET request
$method = $_SERVER['REQUEST_METHOD'];
if ($method != 'GET') {
    header("HTTP/1.0 405 Method Not Allowed");
    echo "405: Method Not Allowed";
    exit;
}

// Process query string for count (default 10)
$count = 10;
parse_str($_SERVER['QUERY_STRING'], $query_params);
if (isset($query_params['count']) && is_numeric($query_params['count'])) {
    if ($query_params['count'] >= 1 && $query_params['count'] <= 1000) {
        $count = $query_params['count'];
    }
}

// Connect to database
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Get top players
$result = $db->query("SELECT `user_id`, `high_score` FROM scores ORDER BY high_score DESC LIMIT $count;");
$leaderboard = array();
while ($row = mysqli_fetch_assoc($result)) {
    $leaderboard[] = $row;
}

// Return as JSON
header('Content-Type: application/json');
echo json_encode($leaderboard);
exit;
