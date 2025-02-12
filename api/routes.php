<?php

// CORS Headers for web dev
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Essential Imports
require_once "./config/database.php";
require_once "./modules/get.php";
require_once "./modules/post.php";


// Database Connection
$con = new Connection();
$pdo = $con->connect();

// Modules
$get = new Get($pdo);
$post = new Post($pdo);


// Check if may 'request'(GET, POST, ETC.)
// Nagwowork sya in tandem with the .htaccess
if (isset($_REQUEST['request'])) {
    // Split the request into an array based on '/'
    $request = explode('/', $_REQUEST['request']);
} else {
    echo "Wala namang request eh?";
    http_response_code(404);
}



// THIS IS BASICALLY THE RESTAURANT MENU

// The menu has 2 categories, GET  at POST 
switch ($_SERVER['REQUEST_METHOD']) {


    // If GET yung request method natin, eto ang mga pwede nyang pagpilian na potahe(endpoint)
    case 'GET':
        switch ($request[0]) {

            case 'sinigang':
                echo "YOu ate sinigang!";
                break;

            case 'lahatnguser':
                echo json_encode($get->get_all_users());
                break;

            case 'user':
                if (count($request) > 1) {
                    echo json_encode($get->get_users($request[1]));
                } else {
                    echo json_encode($get->get_users());
                }
                break;

            default:
                echo "This is forbidden";
                http_response_code(403);
                break;
        }
        break;




    // If POST yung request method natin, eto ang mga pwede nyang pagpilian na potahe(endpoint)
    case 'POST':
        // Kinuha natin yung json content na nasa requests, at inistore sya sa $data variable
        // Eto kumbaga yung mga ingredients
        $data = json_decode(file_get_contents("php://input"));
        switch ($request[0]) {


            case 'registeruser':
                // Return JSON-encoded data for adding users
                echo json_encode($post->add_user($data));
                break;

            default:
                // Return a 403 response for unsupported requests
                echo "No Such Request";
                http_response_code(403);
                break;
        }
        break;

    default:
        // Return a 404 response for unsupported HTTP methods
        echo "Unsupported HTTP method";
        http_response_code(404);
        break;
}

