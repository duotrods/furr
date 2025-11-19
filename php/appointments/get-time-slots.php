<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_GET['date'])) {
    echo json_encode(['error' => 'Date parameter is required']);
    exit();
}

$date = $_GET['date'];
$service_id = $_GET['service_id'] ?? null;

try {
    // 1. Validate date format
    if (!DateTime::createFromFormat('Y-m-d', $date)) {
        throw new Exception('Invalid date format');
    }

    // 2. Check if it's Sunday (day 0)
    $dateTime = new DateTime($date);
    if ($dateTime->format('w') == 0) {
        echo json_encode([]); // Return empty array for Sundays
        exit();
    }

    // 3. Check for store closures
    $stmt = $pdo->prepare("
        SELECT closure_type, start_time, end_time 
        FROM store_closures 
        WHERE closure_date = ?
    ");
    $stmt->execute([$date]);
    $closure = $stmt->fetch();

    // 4. If full day closure, return empty
    if ($closure && $closure['closure_type'] === 'full_day') {
        echo json_encode([]);
        exit();
    }

    // 5. Get business hours and time slot interval
    $business_hours = [
        'start' => '09:00:00', // 9 AM
        'end' => '17:00:00'    // 5 PM
    ];
    $slot_interval = 60; // minutes

    // 6. Get existing appointments for the date
    $stmt = $pdo->prepare("
        SELECT appointment_time 
        FROM appointments 
        WHERE appointment_date = ? 
        AND status != 'cancelled'
    ");
    $stmt->execute([$date]);
    $booked_slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 7. Calculate all possible time slots
    $all_slots = generateTimeSlots(
        $business_hours['start'],
        $business_hours['end'],
        $slot_interval
    );

    // 8. Filter out closed time slots for partial closures
    if ($closure && $closure['closure_type'] === 'partial') {
        $closed_start = strtotime($closure['start_time']);
        $closed_end = strtotime($closure['end_time']);

        $all_slots = array_filter($all_slots, function ($slot) use ($closed_start, $closed_end) {
            $slot_time = strtotime($slot);
            // Keep slots that are NOT within the closed time range
            return $slot_time < $closed_start || $slot_time >= $closed_end;
        });

        // Re-index the array
        $all_slots = array_values($all_slots);
    }

    // 9. Filter out booked slots
    $available_slots = array_diff($all_slots, $booked_slots);

    // 10. If service duration is provided, filter slots that can accommodate it
    if ($service_id) {
        $service = getServiceById($service_id);
        $available_slots = filterSlotsByDuration($available_slots, $service['duration'], $slot_interval);
    }

    echo json_encode(array_values($available_slots));
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

/**
 * Generates time slots between start and end times
 */
function generateTimeSlots($start, $end, $interval)
{
    $slots = [];
    $current = strtotime($start);
    $end = strtotime($end);

    while ($current <= $end) {
        $slots[] = date('H:i:s', $current);
        $current = strtotime("+$interval minutes", $current);
    }

    return $slots;
}

/**
 * Filters time slots to ensure service duration can be accommodated
 */
function filterSlotsByDuration($slots, $duration, $interval)
{
    $valid_slots = [];
    $consecutive_slots_needed = ceil($duration / $interval);

    foreach ($slots as $index => $slot) {
        $has_consecutive = true;

        for ($i = 1; $i < $consecutive_slots_needed; $i++) {
            if (!isset($slots[$index + $i])) {
                $has_consecutive = false;
                break;
            }
        }

        if ($has_consecutive) {
            $valid_slots[] = $slot;
        }
    }

    return $valid_slots;
}