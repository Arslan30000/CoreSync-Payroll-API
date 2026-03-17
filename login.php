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

if (!isset($data->employee_id) || !isset($data->password)) {
    echo json_encode(["status" => "error", "message" => "Missing credentials"]);
    exit;
}


$stmt = $pdo->prepare('SELECT full_name, role, password FROM employees WHERE employee_id = ?');
$stmt->execute([$data->employee_id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);


if ($employee && $data->password === $employee['password']) {
    echo json_encode([
        "status" => "success",
        "name" => $employee['full_name'],
        "role" => $employee['role']
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Credentials"]);
}
?>