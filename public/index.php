<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/db.php';

$app = AppFactory::create();


// Student Routes

// GET
$app->get('/students', function (Request $request, Response $response, $args) {
    $datas = null;
    
    $sql = "SELECT * FROM tblstudents";

    $db = new MySQLDatabase();

    $connect = $db->startConnection();
    
    $result = mysqli_query($connect, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_all($result, MYSQLI_ASSOC)) {
            $datas = $row;
        }
    } 
    else {
        $datas = "0 results";
    }
    mysqli_free_result($result);
    $db->stopConnection($connect);

    $response->getBody()->write(json_encode($datas));
    return $response;
});

// GET Specific Record Version
$app->get('/students/{studno}', function (Request $request, Response $response, $args) {
    $studno = $args['studno'];
    $datas = null;
    
    $sql = "SELECT * FROM tblstudents WHERE id = $studno";

    $db = new MySQLDatabase();

    $connect = $db->startConnection();
    
    $result = mysqli_query($connect, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_all($result, MYSQLI_ASSOC)) {
            $datas = $row;
        }
    } 
    else {
        $datas = "0 results";
    }
    mysqli_free_result($result);
    $db->stopConnection($connect);

    $response->getBody()->write(json_encode($datas));
    return $response;
});

// POST
$app->post('/students/create', function (Request $request, Response $response, $args) {
    $datas = $request->getBody();
    $message = null;
    $values = json_decode($datas, TRUE);

    $name = $values['name'];  
    $address = $values['address']; 
    $course = $values['course']; 

    $sql = "INSERT INTO tblstudents (name, course, address) VALUES ('$name','$course', '$address')";
    
    $db = new MySQLDatabase();

    $connect = $db->startConnection();
    
    if (mysqli_query($connect, $sql)) {
        $message =
        [
            "message" => "1 Student Record was Added",
        ];
    } 
    else {
        $response =
        [
            "message" => "Error: " . $sql . "<br>" . mysqli_error($conn),
        ];
    }
    $db->stopConnection($connect);

    $response->getBody()->write(json_encode($message));
    return $response;
});

$app->put('/students/update/{studno}', function (Request $request, Response $response, $args) {
    $studno = $args['studno'];
    $datas = $request->getBody();
    $message = null;
    $values = json_decode($datas, TRUE);

    $name = $values['name'];  
    $address = $values['address']; 
    $course = $values['course']; 

    $sql = "UPDATE tblstudents SET name = '$name', course = '$course', address = '$address' WHERE id = $studno";
    
    $db = new MySQLDatabase();

    $connect = $db->startConnection();
    
    if (mysqli_query($connect, $sql)) {
        $message =
        [
            "message" => "Update: $name from $course",
        ];
    } 
    else {
        $response =
        [
            "message" => "Error: " . $sql . "<br>" . mysqli_error($conn),
        ];
    }
    $db->stopConnection($connect);

    $response->getBody()->write(json_encode($message));
    return $response;
});

// DELETE
$app->delete('/students/delete/{studno}', function (Request $request, Response $response, $args) {
    $studno = $args['studno'];
    $message = null;

    $sql = "DELETE FROM tblstudents WHERE id = $studno";
    
    $db = new MySQLDatabase();

    $connect = $db->startConnection();
    
    if (mysqli_query($connect, $sql)) {
        $message =
        [
            "message" => "Student No: $studno deleted",
        ];
    } 
    else {
        $response =
        [
            "message" => "Error: " . $sql . "<br>" . mysqli_error($conn),
        ];
    }
    $db->stopConnection($connect);

    $response->getBody()->write(json_encode($message));
    return $response;
});

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->run();
