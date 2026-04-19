<<<<<<< HEAD
<?php
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=127.0.0.1;dbname=payroll_api;charset=utf8mb4", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$method = $_SERVER['REQUEST_METHOD'];

// GET request: Fetch today's attendance record for the employee
if ($method === 'GET') {
    $emp_id = $_GET['id'] ?? '';
    $today = date('Y-m-d');
    
    $stmt = $pdo->prepare("SELECT time_in, time_out FROM attendance WHERE employee_id = ? AND date = ?");
    $stmt->execute([$emp_id, $today]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode(["status" => "success", "data" => $record ? $record : null]);
} 
// POST request: Clock in or Clock out
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $emp_id = $data->employee_id;
    $action = $data->action; // 'in' or 'out'
    $today = date('Y-m-d');
    $time = date('H:i:s');

    if ($action === 'in') {
        $stmt = $pdo->prepare("INSERT INTO attendance (employee_id, date, time_in) VALUES (?, ?, ?)");
        $stmt->execute([$emp_id, $today, $time]);
    } elseif ($action === 'out') {
        $stmt = $pdo->prepare("UPDATE attendance SET time_out = ? WHERE employee_id = ? AND date = ?");
        $stmt->execute([$time, $emp_id, $today]);
    }
    echo json_encode(["status" => "success"]);
}
=======
<?php
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=127.0.0.1;dbname=payroll_db;charset=utf8mb4", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$method = $_SERVER['REQUEST_METHOD'];

// GET request: Fetch today's attendance record for the employee
if ($method === 'GET') {
    $emp_id = $_GET['id'] ?? '';
    $today = date('Y-m-d');
    
    $stmt = $pdo->prepare("SELECT time_in, time_out FROM attendance WHERE employee_id = ? AND date = ?");
    $stmt->execute([$emp_id, $today]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode(["status" => "success", "data" => $record ? $record : null]);
} 
// POST request: Clock in or Clock out
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $emp_id = $data->employee_id;
    $action = $data->action; // 'in' or 'out'
    $today = date('Y-m-d');
    $time = date('H:i:s');

    if ($action === 'in') {
        $stmt = $pdo->prepare("INSERT INTO attendance (employee_id, date, time_in) VALUES (?, ?, ?)");
        $stmt->execute([$emp_id, $today, $time]);
    } elseif ($action === 'out') {
        $stmt = $pdo->prepare("UPDATE attendance SET time_out = ? WHERE employee_id = ? AND date = ?");
        $stmt->execute([$time, $emp_id, $today]);
    }
    echo json_encode(["status" => "success"]);
}
>>>>>>> 9b0de5d (Payroll generation complete)
?>