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

$employee_id = $_GET['id'] ?? '';

if (empty($employee_id)) {
    echo json_encode(["status" => "error", "message" => "No ID provided"]);
    exit;
}

$stmt = $pdo->prepare('SELECT employee_id, full_name, role, base_salary, total_leaves, leaves_taken FROM employees WHERE employee_id = ?');
$stmt->execute([$employee_id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if ($employee) {
    echo json_encode(["status" => "success", "data" => $employee]);
} else {
    echo json_encode(["status" => "error", "message" => "Employee not found"]);
}
?>