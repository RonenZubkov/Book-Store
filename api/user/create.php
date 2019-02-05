<?php
/*For Debugging can be del later*/
ini_set('display_errors', 'On');
error_reporting(E_ALL);

// required headers
header("Access-Control-Allow-Origin: http://localhost:63342");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// files needed to connect to database
include_once '../../config/core.php';
include_once '../../config/database.php';
include_once '../../objects/user.php';
include_once './validate_admin_token.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$user = new User($db);

/*https://stackoverflow.com/questions/8893574/php-php-input-vs-post*/
// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$user->firstname = isset($data->firstname) ? $data->firstname : "";
$user->lastname = isset($data->lastname) ? $data->lastname : "";
$user->email = isset($data->email) ? $data->email : "";
$user->password = isset($data->password) ? $data->password : "";
$user->address = isset($data->address) ? $data->address : "";
$user->contact_number = isset($data->contact_number) ? $data->contact_number : "";
$user->access_level = "Customer";
print_r($user);
// create the user
if(
    $user->firstname &&
    $user->lastname &&
    $user->email &&
    $user->password &&
    $user->address &&
    $user->contact_number &&
    $user->create()
){

    // set response code
    http_response_code(200);

    // display message: user was created
    echo json_encode(array("message" => "User was created."));
}

// message if unable to create user
else{

    // set response code - 400 bad request
    http_response_code(400);

    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create user."));
}

