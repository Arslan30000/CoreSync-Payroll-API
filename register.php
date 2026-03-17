<?php
header('Content-Type: application/json');

$host = '127.0.0.1';
$db   = 'payroll_db';
$user = 'root'; 
$pass = '';    

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->employee_id) || empty($data->full_name) || empty($data->role) || empty($data->base_salary) || empty($data->password)) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit;
}

$checkStmt = $pdo->prepare('SELECT employee_id FROM employees WHERE employee_id = ?');
$checkStmt->execute([$data->employee_id]);
if ($checkStmt->fetch()) {
    echo json_encode(["status" => "error", "message" => "Employee ID already exists."]);
    exit;
}


try {
    $stmt = $pdo->prepare('INSERT INTO employees (employee_id, full_name, role, base_salary, password) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$data->employee_id, $data->full_name, $data->role, $data->base_salary, $data->password]);
    
    echo json_encode(["status" => "success", "message" => "Employee registered successfully."]);
} catch (\PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Failed to register employee."]);
}
?>