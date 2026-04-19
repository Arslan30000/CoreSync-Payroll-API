<<<<<<< HEAD
<?php
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=127.0.0.1;dbname=payroll_api;charset=utf8mb4", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$method = $_SERVER['REQUEST_METHOD'];

// GET request: Fetch all leave requests for a specific employee
if ($method === 'GET') {
    $emp_id = $_GET['id'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM leave_requests WHERE employee_id = ? ORDER BY id DESC");
    $stmt->execute([$emp_id]);
    echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
} 
// POST request: Submit a new leave request
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $stmt = $pdo->prepare("INSERT INTO leave_requests (employee_id, start_date, end_date, reason) VALUES (?, ?, ?, ?)");
    $stmt->execute([$data->employee_id, $data->start_date, $data->end_date, $data->reason]);
    echo json_encode(["status" => "success"]);
}
=======
<?php
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=127.0.0.1;dbname=payroll_db;charset=utf8mb4", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$method = $_SERVER['REQUEST_METHOD'];

// GET request: Fetch all leave requests for a specific employee
if ($method === 'GET') {
    $emp_id = $_GET['id'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM leave_requests WHERE employee_id = ? ORDER BY id DESC");
    $stmt->execute([$emp_id]);
    echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
} 
// POST request: Submit a new leave request
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $stmt = $pdo->prepare("INSERT INTO leave_requests (employee_id, start_date, end_date, reason) VALUES (?, ?, ?, ?)");
    $stmt->execute([$data->employee_id, $data->start_date, $data->end_date, $data->reason]);
    echo json_encode(["status" => "success"]);
}
>>>>>>> 9b0de5d (Payroll generation complete)
?>