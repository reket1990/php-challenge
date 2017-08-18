<?php

// This endpoint gets the number of active players
// Optional query parameter: time - INT between 1 and 315360000 [10 years]
//                                  (default 86400 [1 day])
// TODO: Error handling

require_once '../config.php';

// Make sure GET request
$method = $_SERVER['REQUEST_METHOD'];
if ($method != 'GET') {
    header("HTTP/1.0 405 Method Not Allowed");
    echo "405: Method Not Allowed";
    exit;
}

// Process query string for time (default 86400 [1 day])
$time = 86400;
parse_str($_SERVER['QUERY_STRING'], $query_params);
if (isset($query_params['time']) && is_numeric($query_params['time'])) {
    if ($query_params['time'] >= 1 && $query_params['time'] <= 315360000) {
        $time = $query_params['time'];
    }
}
// Calculate unix time equivalent
$time_period = time() - $time;

// Connect to database
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Get number of recent players
$result = $db->query("SELECT * FROM scores WHERE last_played > $time_period;");
$num_players = mysqli_num_rows($result);

// Return as JSON
header('Content-Type: application/json');
echo json_encode(array('time' => $time, 'num_players' => $num_players));
exit;
