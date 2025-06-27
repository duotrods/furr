<?php
require_once '../../includes/config.php';
requireAdmin();

$report_type = $_GET['type'] ?? 'appointments';
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="furcare_report_' . $report_type . '_' . date('Ymd') . '.xls"');

// Create Excel content
echo "<table border='1'>";

if ($report_type == 'appointments') {
    $stmt = $pdo->prepare("SELECT a.*, s.name as service_name, s.price as service_price, 
                          u.first_name, u.last_name, u.email, u.phone 
                          FROM appointments a 
                          JOIN services s ON a.service_id = s.id 
                          JOIN users u ON a.user_id = u.id 
                          WHERE a.appointment_date BETWEEN ? AND ?
                          ORDER BY a.appointment_date, a.appointment_time");
    $stmt->execute([$start_date, $end_date]);
    $data = $stmt->fetchAll();
    
    echo "<tr>
            <th>Customer</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Service</th>
            <th>Date</th>
            <th>Time</th>
            <th>Pet Name</th>
            <th>Pet Type</th>
            <th>Status</th>
            <th>Amount</th>
          </tr>";
    
    foreach ($data as $row) {
        echo "<tr>
                <td>{$row['first_name']} {$row['last_name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['service_name']}</td>
                <td>" . formatDate($row['appointment_date']) . "</td>
                <td>" . formatTime($row['appointment_time']) . "</td>
                <td>{$row['pet_name']}</td>
                <td>{$row['pet_type']}</td>
                <td>{$row['status']}</td>
                <td>₱" . number_format($row['service_price'], 2) . "</td>
              </tr>";
    }
    
} elseif ($report_type == 'sales') {
    $stmt = $pdo->prepare("SELECT o.*, u.first_name, u.last_name, u.email, 
                          COUNT(oi.id) as item_count, SUM(oi.quantity) as total_quantity
                          FROM orders o
                          JOIN order_items oi ON o.id = oi.order_id
                          JOIN users u ON o.user_id = u.id
                          WHERE o.order_date BETWEEN ? AND ?
                          GROUP BY o.id
                          ORDER BY o.order_date DESC");
    $stmt->execute([$start_date, $end_date]);
    $data = $stmt->fetchAll();
    
    echo "<tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Date</th>
            <th>Items</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Amount</th>
          </tr>";
    
    foreach ($data as $row) {
        echo "<tr>
                <td>#" . str_pad($row['id'], 5, '0', STR_PAD_LEFT) . "</td>
                <td>{$row['first_name']} {$row['last_name']}</td>
                <td>{$row['email']}</td>
                <td>" . formatDate($row['order_date']) . "</td>
                <td>{$row['item_count']}</td>
                <td>{$row['total_quantity']}</td>
                <td>{$row['status']}</td>
                <td>₱" . number_format($row['total_amount'], 2) . "</td>
              </tr>";
    }
    
} elseif ($report_type == 'customers') {
    $stmt = $pdo->prepare("SELECT u.*, 
                          COUNT(a.id) as appointment_count,
                          COUNT(o.id) as order_count,
                          IFNULL(SUM(o.total_amount), 0) as total_spent
                          FROM users u
                          LEFT JOIN appointments a ON u.id = a.user_id
                          LEFT JOIN orders o ON u.id = o.user_id
                          WHERE u.created_at BETWEEN ? AND ?
                          GROUP BY u.id
                          ORDER BY total_spent DESC");
    $stmt->execute([$start_date, $end_date]);
    $data = $stmt->fetchAll();
    
    echo "<tr>
            <th>Customer</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Appointments</th>
            <th>Orders</th>
            <th>Total Spent</th>
          </tr>";
    
    foreach ($data as $row) {
        echo "<tr>
                <td>{$row['first_name']} {$row['last_name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['address']}</td>
                <td>{$row['appointment_count']}</td>
                <td>{$row['order_count']}</td>
                <td>₱" . number_format($row['total_spent'], 2) . "</td>
              </tr>";
    }
}

echo "</table>";
exit();
?>