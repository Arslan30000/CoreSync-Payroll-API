<<<<<<< HEAD
<?php
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=127.0.0.1;dbname=payroll_api;charset=utf8mb4", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$data = json_decode(file_get_contents("php://input"));
if(empty($data->employee_id) || empty($data->old_password) || empty($data->new_password)) {
    echo json_encode(["status" => "error", "message" => "Missing fields"]); exit;
}

$stmt = $pdo->prepare("SELECT password FROM employees WHERE employee_id = ?");
$stmt->execute([$data->employee_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $user['password'] === $data->old_password) {
    $update = $pdo->prepare("UPDATE employees SET password = ? WHERE employee_id = ?");
    $update->execute([$data->new_password, $data->employee_id]);
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Incorrect old password"]);
}
=======
<?php
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=127.0.0.1;dbname=payroll_db;charset=utf8mb4", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$data = json_decode(file_get_contents("php://input"));
if(empty($data->employee_id) || empty($data->old_password) || empty($data->new_password)) {
    echo json_encode(["status" => "error", "message" => "Missing fields"]); exit;
}

$stmt = $pdo->prepare("SELECT password FROM employees WHERE employee_id = ?");
$stmt->execute([$data->employee_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $user['password'] === $data->old_password) {
    $update = $pdo->prepare("UPDATE employees SET password = ? WHERE employee_id = ?");
    $update->execute([$data->new_password, $data->employee_id]);
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Incorrect old password"]);
}
>>>>>>> 9b0de5d (Payroll generation complete)
?>