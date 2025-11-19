<?php
require_once __DIR__ . '/../../includes/config.php';

header('Content-Type: application/json');

try {
    // Get store closures from database
    $stmt = $pdo->query("SELECT closure_date FROM store_closures WHERE closure_date >= CURDATE()");
    $closedDates = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    // Add Sundays to closed dates for the next 6 months
    $sundays = getFutureSundays();
    $allClosedDates = array_merge($closedDates, $sundays);

    // Remove duplicates and sort
    $allClosedDates = array_unique($allClosedDates);
    sort($allClosedDates);

    echo json_encode($allClosedDates);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([]);
}

/**
 * Get all future Sundays for the next 6 months
 */
function getFutureSundays()
{
    $sundays = [];
    $today = new DateTime();
    $endDate = new DateTime('+6 months'); // Get Sundays for next 6 months

    // Start from next Sunday
    $current = clone $today;
    $current->modify('next sunday');

    while ($current <= $endDate) {
        $sundays[] = $current->format('Y-m-d');
        $current->modify('+1 week');
    }

    return $sundays;
}