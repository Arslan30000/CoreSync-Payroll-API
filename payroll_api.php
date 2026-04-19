<?php
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=127.0.0.1;dbname=payroll_db;charset=utf8mb4", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET') {
  
    if ($action === 'get_all') {
        $stmt = $pdo->query("SELECT p.*, e.full_name FROM payroll p JOIN employees e ON p.employee_id = e.employee_id ORDER BY p.id DESC");
        echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } 
  
    elseif ($action === 'get_mine') {
        $empId = $_GET['id'] ?? '';
        $stmt = $pdo->prepare("SELECT * FROM payroll WHERE employee_id = ? ORDER BY id DESC");
        $stmt->execute([$empId]);
        echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }
} elseif ($method === 'POST') {
    // Admin: Generate Payroll for a Month
    $data = json_decode(file_get_contents("php://input"));
    if ($data->action === 'generate') {
        $month = $data->month; 
        $today = date('Y-m-d');
        
        // Prevent duplicate payrolls for the same month
        $check = $pdo->prepare("SELECT id FROM payroll WHERE salary_month = ? LIMIT 1");
        $check->execute([$month]);
        if ($check->fetch()) {
            echo json_encode(["status" => "error", "message" => "Payroll for $month already generated!"]);
            exit;
        }

        // Fetch all standard employees
        $emps = $pdo->query("SELECT employee_id, base_salary, total_leaves, leaves_taken FROM employees WHERE role='EMPLOYEE'")->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("INSERT INTO payroll (employee_id, salary_month, base_salary, deductions, net_salary, payment_date) VALUES (?, ?, ?, ?, ?, ?)");
        
        foreach ($emps as $emp) {
            $base = $emp['base_salary'];
            $deductions = 0;
            
            // Calculate deductions if they took unpaid leaves
            if ($emp['leaves_taken'] > $emp['total_leaves']) {
                $extraLeaves = $emp['leaves_taken'] - $emp['total_leaves'];
                $dailyRate = $base / 30; // Assuming 30 days a month
                $deductions = round($extraLeaves * $dailyRate, 2);
            }
            
            $net = $base - $deductions;
            $stmt->execute([$emp['employee_id'], $month, $base, $deductions, $net, $today]);
        }
        echo json_encode(["status" => "success"]);
    }
}
?>