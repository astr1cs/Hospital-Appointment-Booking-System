<?php
require_once __DIR__ . '/BaseController.php';

class AppointmentController extends ReceptionistBaseController {
    
    // Today's schedule for all doctors
    // Today's schedule for all doctors
public function today() {
    $today = date('Y-m-d');
    
    // Get all appointments for today grouped by doctor
    $sql = "SELECT a.*, 
                   d.name as doctor_name,
                   d.id as doctor_user_id,
                   p.name as patient_name,
                   p.phone as patient_phone
            FROM appointments a
            JOIN users d ON a.doctor_id = d.id
            JOIN users p ON a.patient_id = p.id
            WHERE a.appointment_date = ?
            ORDER BY d.name, a.appointment_time ASC";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $appointments = $stmt->get_result();
    
    // Get statistics
    $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                SUM(CASE WHEN status = 'checked_in' THEN 1 ELSE 0 END) as checked_in,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
            FROM appointments
            WHERE appointment_date = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $stats = $stmt->get_result()->fetch_assoc();
    
    // Group appointments by doctor
    $grouped = [];
    while ($row = $appointments->fetch_assoc()) {
        $doctorId = $row['doctor_user_id'];
        if (!isset($grouped[$doctorId])) {
            $grouped[$doctorId] = [
                'doctor_name' => $row['doctor_name'],
                'appointments' => []
            ];
        }
        $grouped[$doctorId]['appointments'][] = $row;
    }
    
    $data = [
        'title' => 'Today\'s Schedule',
        'grouped' => $grouped,
        'stats' => $stats,
        'today' => $today,
        'success' => $_SESSION['success'] ?? null,
        'error' => $_SESSION['error'] ?? null
    ];
    
    // Clear session messages after retrieving
    unset($_SESSION['success']);
    unset($_SESSION['error']);
    
    $this->view('appointments/today', $data);
}
    
    // Check in patient
    public function checkin() {
        $appointmentId = $_GET['id'] ?? null;
        
        if (!$appointmentId) {
            $_SESSION['error'] = 'Appointment ID required';
            $this->redirect('receptionist.php?action=appointments&sub=today');
            return;
        }
        
        $sql = "UPDATE appointments SET status = 'checked_in' WHERE id = ? AND status IN ('pending', 'confirmed')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $appointmentId);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Patient checked in successfully';
        } else {
            $_SESSION['error'] = 'Failed to check in patient';
        }
        
        $this->redirect('receptionist.php?action=appointments&sub=today');
    }
    
    // Cancel appointment
    // Cancel appointment
// Cancel appointment
public function cancel($id) {
    $sql = "UPDATE appointments SET status = 'cancelled' WHERE id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Appointment cancelled successfully';
    } else {
        $_SESSION['error'] = 'Failed to cancel appointment';
    }
    
    // Redirect back to today's schedule page
    $this->redirect('receptionist.php?action=appointments&sub=today');
}
    
    // Waiting queue (checked-in patients)
    public function queue() {
        $today = date('Y-m-d');
        
        $sql = "SELECT a.*, 
                       d.name as doctor_name,
                       p.name as patient_name,
                       p.phone as patient_phone
                FROM appointments a
                JOIN users d ON a.doctor_id = d.id
                JOIN users p ON a.patient_id = p.id
                WHERE a.appointment_date = ? AND a.status = 'checked_in'
                ORDER BY a.appointment_time ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $today);
        $stmt->execute();
        $queue = $stmt->get_result();
        
        $data = [
            'title' => 'Waiting Queue',
            'queue' => $queue
        ];
        
        $this->view('appointments/queue', $data);
    }

// Show walk-in booking form

        // Show walk-in booking form
// Show walk-in booking form
public function book() {
    // Get all doctors
    $sql = "SELECT u.id, u.name, d.consultation_fee, s.name as specialization_name
            FROM users u
            JOIN doctors d ON u.id = d.user_id
            JOIN specializations s ON d.specialization_id = s.id
            WHERE u.role = 'doctor' AND u.is_active = 1 AND d.is_approved = 1
            ORDER BY u.name";
    $doctors = $this->db->query($sql);
    
    // Get patients for selection
    $sql = "SELECT id, name, phone FROM users WHERE role = 'patient' AND is_active = 1 ORDER BY name LIMIT 50";
    $patients = $this->db->query($sql);
    
    // Check for newly registered patient
    $newPatientId = $_SESSION['new_patient_id'] ?? null;
    $newPatientName = $_SESSION['new_patient_name'] ?? null;
    
    $data = [
        'title' => 'Walk-in Booking',
        'doctors' => $doctors,
        'patients' => $patients,
        'newPatientId' => $newPatientId,
        'newPatientName' => $newPatientName,
        'success' => $_SESSION['success'] ?? null,
        'error' => $_SESSION['error'] ?? null
    ];
    
    // Clear session variables
    unset($_SESSION['new_patient_id']);
    unset($_SESSION['new_patient_name']);
    unset($_SESSION['success']);
    unset($_SESSION['error']);
    
    $this->view('appointments/book', $data);
}

// AJAX: Get available slots for a doctor on a date
public function getSlots() {
    header('Content-Type: application/json');
    
    $doctorId = $_POST['doctor_id'] ?? null;
    $date = $_POST['date'] ?? null;
    
    if (!$doctorId || !$date) {
        echo json_encode(['success' => false, 'message' => 'Missing required data']);
        return;
    }
    
    $dayOfWeek = date('l', strtotime($date));
    
    // Check if doctor is on leave
    $sql = "SELECT id FROM leave_dates WHERE doctor_id = ? AND leave_date = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("is", $doctorId, $date);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => true, 'slots' => []]);
        return;
    }
    
    // Get doctor's availability
    $sql = "SELECT start_time, end_time, slot_duration_minutes 
            FROM doctor_availability 
            WHERE doctor_id = ? AND day_of_week = ? AND is_available = 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("is", $doctorId, $dayOfWeek);
    $stmt->execute();
    $availability = $stmt->get_result()->fetch_assoc();
    
    if (!$availability) {
        echo json_encode(['success' => true, 'slots' => []]);
        return;
    }
    
    // Get booked slots
    $sql = "SELECT appointment_time FROM appointments 
            WHERE doctor_id = ? AND appointment_date = ? 
            AND status NOT IN ('cancelled', 'no_show')";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("is", $doctorId, $date);
    $stmt->execute();
    $bookedResult = $stmt->get_result();
    
    $bookedSlots = [];
    while ($row = $bookedResult->fetch_assoc()) {
        $bookedSlots[] = $row['appointment_time'];
    }
    
    // Generate slots
    $start = new DateTime($availability['start_time']);
    $end = new DateTime($availability['end_time']);
    $interval = new DateInterval('PT' . $availability['slot_duration_minutes'] . 'M');
    
    $slots = [];
    $current = clone $start;
    
    while ($current < $end) {
        $slotTime = $current->format('H:i:s');
        $isBooked = in_array($slotTime, $bookedSlots);
        
        $slots[] = [
            'time' => $current->format('h:i A'),
            'time_value' => $slotTime,
            'available' => !$isBooked
        ];
        
        $current->add($interval);
    }
    
    echo json_encode(['success' => true, 'slots' => $slots]);
}

// Store walk-in appointment
public function store() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('receptionist.php?action=appointments&sub=book');
        return;
    }
    
    $patientId = $_POST['patient_id'] ?? null;
    $doctorId = $_POST['doctor_id'] ?? null;
    $date = $_POST['date'] ?? null;
    $time = $_POST['time'] ?? null;
    $reason = $_POST['reason'] ?? 'Walk-in appointment';
    
    if (!$patientId || !$doctorId || !$date || !$time) {
        $_SESSION['error'] = 'All fields are required';
        $this->redirect('receptionist.php?action=appointments&sub=book');
        return;
    }
    
    // Check if slot is still available
    $sql = "SELECT COUNT(*) as count FROM appointments 
            WHERE doctor_id = ? AND appointment_date = ? AND appointment_time = ? 
            AND status NOT IN ('cancelled', 'no_show')";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("iss", $doctorId, $date, $time);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    if ($result['count'] > 0) {
        $_SESSION['error'] = 'This time slot is no longer available';
        $this->redirect('receptionist.php?action=appointments&sub=book');
        return;
    }
    
    // Create appointment
    $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason, status, booked_by) 
            VALUES (?, ?, ?, ?, ?, 'confirmed', 'receptionist')";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("iisss", $patientId, $doctorId, $date, $time, $reason);
    
    if ($stmt->execute()) {
        $appointmentId = $this->db->insert_id;
        
        // Create billing record
        $sql = "SELECT consultation_fee FROM doctors WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $doctorId);
        $stmt->execute();
        $fee = $stmt->get_result()->fetch_assoc()['consultation_fee'];
        
        $sql = "INSERT INTO billing (appointment_id, patient_id, amount, payment_status) VALUES (?, ?, ?, 'pending')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iid", $appointmentId, $patientId, $fee);
        $stmt->execute();
        
        $_SESSION['success'] = 'Appointment booked successfully!';
    } else {
        $_SESSION['error'] = 'Failed to book appointment';
    }
    
    $this->redirect('receptionist.php?action=appointments&sub=today');
}

// Register new patient from walk-in

        
    public function registerPatient() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('receptionist.php?action=appointments&sub=book');
        return;
    }
    
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? 'password123';
    
    if (empty($name) || empty($email) || empty($phone)) {
        $_SESSION['error'] = 'Name, email and phone are required';
        $this->redirect('receptionist.php?action=appointments&sub=book');
        return;
    }
    
    // Check if email exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['error'] = 'Email already exists';
        $this->redirect('receptionist.php?action=appointments&sub=book');
        return;
    }
    
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    
    $sql = "INSERT INTO users (name, email, password_hash, phone, role, is_active) VALUES (?, ?, ?, ?, 'patient', 1)";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $passwordHash, $phone);
    
    if ($stmt->execute()) {
        $patientId = $this->db->insert_id;
        
        // Insert into patients table
        $sql = "INSERT INTO patients (user_id) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $patientId);
        $stmt->execute();
        
        // Store success message and new patient ID in session
        $_SESSION['success'] = 'Patient registered successfully! You can now book an appointment.';
        $_SESSION['new_patient_id'] = $patientId;
        $_SESSION['new_patient_name'] = $name;
    } else {
        $_SESSION['error'] = 'Failed to register patient';
    }
    
    // Redirect back to booking page
    $this->redirect('receptionist.php?action=appointments&sub=book');
}



}
?>