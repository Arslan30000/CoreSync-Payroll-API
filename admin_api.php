<<<<<<< HEAD
<?php
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=127.0.0.1;dbname=payroll_api;charset=utf8mb4", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET') {
    if ($action === 'get_pending_leaves') {
        $stmt = $pdo->query("SELECT id, employee_id, start_date, end_date, reason, status FROM leave_requests WHERE status='Pending'");
        echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } elseif ($action === 'get_employees') {
        $stmt = $pdo->query("SELECT employee_id, full_name, role, base_salary FROM employees");
        echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    if ($data->action === 'update_leave') {
        $stmt = $pdo->prepare("UPDATE leave_requests SET status = ? WHERE id = ?");
        $stmt->execute([$data->status, $data->leave_id]);
        
        // If approved, deduct from leave balance (calculate days difference)
        if ($data->status === 'Approved') {
            $stmt2 = $pdo->prepare("UPDATE employees e JOIN leave_requests l ON e.employee_id = l.employee_id SET e.leaves_taken = e.leaves_taken + DATEDIFF(l.end_date, l.start_date) + 1 WHERE l.id = ?");
            $stmt2->execute([$data->leave_id]);
        }
        echo json_encode(["status" => "success"]);
    }
}
=======
<?php
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=127.0.0.1;dbname=payroll_db;charset=utf8mb4", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET') {
    if ($action === 'get_pending_leaves') {
        $stmt = $pdo->query("SELECT id, employee_id, start_date, end_date, reason, status FROM leave_requests WHERE status='Pending'");
        echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } elseif ($action === 'get_employees') {
        $stmt = $pdo->query("SELECT employee_id, full_name, role, base_salary FROM employees");
        echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    if ($data->action === 'update_leave') {
        $stmt = $pdo->prepare("UPDATE leave_requests SET status = ? WHERE id = ?");
        $stmt->execute([$data->status, $data->leave_id]);
        
        // If approved, deduct from leave balance (calculate days difference)
        if ($data->status === 'Approved') {
            $stmt2 = $pdo->prepare("UPDATE employees e JOIN leave_requests l ON e.employee_id = l.employee_id SET e.leaves_taken = e.leaves_taken + DATEDIFF(l.end_date, l.start_date) + 1 WHERE l.id = ?");
            $stmt2->execute([$data->leave_id]);
        }
        echo json_encode(["status" => "success"]);
    }
}
>>>>>>> 9b0de5d (Payroll generation complete)
?>