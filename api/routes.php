<?php

// CORS Headers for web dev
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

// Essential Imports
require_once "./config/database.php";
require_once "./modules/get.php";
require_once "./modules/post.php";
require_once "./modules/product.php";
require_once "./modules/auth.php";
require_once "./modules/user.php";
require_once "./modules/seller.php";

// Database Connection
$con = new Connection();
$pdo = $con->connect();

// Modules
$get = new Get($pdo);
$post = new Post($pdo);
$product = new Product($pdo);
$user = new User($pdo);
$seller = new Seller($pdo);
$auth = new Auth($pdo);

// Check if may 'request'(GET, POST, ETC.)
// Nagwowork sya in tandem with the .htaccess
if (isset($_REQUEST['request'])) {
    // Split the request into an array based on '/'
    $request = explode('/', $_REQUEST['request']);
} else {
    echo "Wala namang request eh?";
    http_response_code(404);
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'OPTIONS':
        http_response_code(200);
        exit();

    case 'GET':
        switch ($request[0]) {
            case 'customer':
                if (count($request) > 1) {
                    switch ($request[1]) {
                        case 'profile':
                            if (count($request) > 2) {
                                echo json_encode($user->get_user_profile($request[2]));
                            } else {
                                echo "No ID Provided";
                            }
                            break;
                        default:
                            echo "Invalid customer endpoint";
                            http_response_code(403);
                            break;
                    }
                } else {
                    echo "Invalid customer request";
                    http_response_code(403);
                }
                break;

                case 'seller':
                    if (count($request) > 1) {
                        switch ($request[1]) {
                            case 'profile':
                                if (count($request) > 2) {
                                    echo json_encode($user->get_user_profile($request[2]));
                                } else {
                                    echo "No ID Provided";
                                }
                                break;

                            case 'business-profile':
                                    if (count($request) > 2) {
                                        echo json_encode($seller->get_business_profile($request[2]));
                                    } else {
                                        echo "No ID Provided";
                                    }
                                    break;

                            case 'products':
                                if (count($request) > 2) {
                                    echo json_encode($seller->get_seller_products($request[2]));
                                } else {
                                    echo "No seller ID provided";
                                }
                                break;

                            default:
                                echo "Invalid customer endpoint";
                                http_response_code(403);
                                break;
                        }
                    } else {
                        echo "Invalid customer request";
                        http_response_code(403);
                    }
                    break;

            default:
                echo "This is forbidden";
                http_response_code(403);
                break;
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        switch ($request[0]) {

            case 'login':
                echo json_encode($auth->login($data));
                break;

            case 'register':
                echo json_encode($user->add_user($data));
                break;

            // PRODUCTS ENDPOINTS
            case 'getProducts':
                echo json_encode($product->get_products($request[1] ?? null));
                break;

            case 'addProduct':
                echo json_encode($product->add_product($data));
                break;

            case 'updateProduct':
                echo json_encode($product->update_product($data));
                break;

            case 'deleteProduct':
                echo json_encode($product->delete_product($data));
                break;

                 case 'seller':
                    if (count($request) > 1) {
                        switch ($request[1]) {
                            case 'products':
                                if (count($request) > 2) {
                                    switch ($request[2]) {
                                        case 'add':
                                            $seller_id = $data->seller_id ?? null;
                                            echo json_encode($seller->add_product($seller_id, $data));
                                            break;
                            
                                        case 'update':
                                            if (count($request) > 2) {
                                                $seller_id = $data->seller_id ?? null;
                                                echo json_encode($seller->update_product($seller_id, $request[3], $data));
                                            } else {
                                                echo "No product ID provided";
                                            }
                                            break;
                            
                                        case 'delete':
                                            if (count($request) > 2) {
                                                $seller_id = $data->seller_id ?? null;
                                                echo json_encode($seller->delete_product($seller_id, $request[3]));
                                            } else {
                                                echo "No product ID provided";
                                            }
                                            break;
            
                                        default:
                                            echo "Invalid customer endpoint";
                                            http_response_code(403);
                                            break;
                                    }
                                } else {
                                    echo "Invalid customer request";
                                    http_response_code(403);
                                }
                                break;
                        }
                    } else {
                        echo "Invalid customer request";
                        http_response_code(403);
                    }
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
