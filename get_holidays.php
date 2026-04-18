<?php
header('Content-Type: application/json');
// Note: Using 'payroll_api' as the database name to match your working setup
$pdo = new PDO("mysql:host=127.0.0.1;dbname=payroll_api;charset=utf8mb4", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $stmt = $pdo->query("SELECT holiday_name, holiday_date, type FROM holidays ORDER BY holiday_date ASC");
    $holidays = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "success", "data" => $holidays]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Failed to fetch holidays"]);
}
?>