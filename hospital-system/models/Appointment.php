<?php
class Appointment {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Get count of appointments by date
    public function getCountByDate($date) {
        $sql = "SELECT COUNT(*) as count FROM appointments WHERE appointment_date = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }
    
    // Get recent appointments
    public function getRecent($limit = 5) {
        $sql = "SELECT a.*, 
                p.name as patient_name, 
                d.name as doctor_name
                FROM appointments a
                JOIN users p ON a.patient_id = p.id
                JOIN users d ON a.doctor_id = d.id
                ORDER BY a.created_at DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Get all appointments with filters
    public function getAll($filters = []) {
        $sql = "SELECT a.*, 
                p.name as patient_name, 
                p.email as patient_email,
                p.phone as patient_phone,
                d.name as doctor_name,
                d.email as doctor_email,
                doc.specialization_id,
                s.name as specialization_name
                FROM appointments a
                JOIN users p ON a.patient_id = p.id
                JOIN users d ON a.doctor_id = d.id
                JOIN doctors doc ON d.id = doc.user_id
                JOIN specializations s ON doc.specialization_id = s.id
                WHERE 1=1";
        
        $params = [];
        $types = "";
        
        if (!empty($filters['doctor_id'])) {
            $sql .= " AND a.doctor_id = ?";
            $params[] = $filters['doctor_id'];
            $types .= "i";
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND a.status = ?";
            $params[] = $filters['status'];
            $types .= "s";
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND a.appointment_date >= ?";
            $params[] = $filters['date_from'];
            $types .= "s";
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND a.appointment_date <= ?";
            $params[] = $filters['date_to'];
            $types .= "s";
        }
        
        if (!empty($filters['booking_source'])) {
            $sql .= " AND a.booked_by = ?";
            $params[] = $filters['booking_source'];
            $types .= "s";
        }
        
        $sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";
        
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Get appointment by ID
    public function getById($id) {
        $sql = "SELECT a.*, 
                p.name as patient_name, 
                p.email as patient_email,
                p.phone as patient_phone,
                d.name as doctor_name,
                d.email as doctor_email,
                doc.specialization_id,
                s.name as specialization_name
                FROM appointments a
                JOIN users p ON a.patient_id = p.id
                JOIN users d ON a.doctor_id = d.id
                JOIN doctors doc ON d.id = doc.user_id
                JOIN specializations s ON doc.specialization_id = s.id
                WHERE a.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Get all doctors for filter dropdown
    public function getAllDoctors() {
        $sql = "SELECT u.id, u.name 
                FROM users u 
                JOIN doctors d ON u.id = d.user_id 
                WHERE u.role = 'doctor' AND u.is_active = 1 AND d.is_approved = 1
                ORDER BY u.name";
        
        return $this->db->query($sql);
    }
    
    // Get appointment statistics
    public function getStats($filters = []) {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                    SUM(CASE WHEN status = 'no_show' THEN 1 ELSE 0 END) as no_show
                FROM appointments a
                WHERE 1=1";
        
        $params = [];
        $types = "";
        
        if (!empty($filters['doctor_id'])) {
            $sql .= " AND a.doctor_id = ?";
            $params[] = $filters['doctor_id'];
            $types .= "i";
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND a.appointment_date >= ?";
            $params[] = $filters['date_from'];
            $types .= "s";
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND a.appointment_date <= ?";
            $params[] = $filters['date_to'];
            $types .= "s";
        }
        
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }


    // Get appointment volume report
public function getVolumeReport() {
    $sql = "SELECT 
                d.name as doctor_name,
                s.name as specialization_name,
                COUNT(a.id) as total_appointments,
                SUM(CASE WHEN a.status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN a.status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN a.status = 'no_show' THEN 1 ELSE 0 END) as no_show,
                ROUND(SUM(CASE WHEN a.status = 'completed' THEN 1 ELSE 0 END) * 100.0 / COUNT(a.id), 2) as completion_rate
            FROM appointments a
            JOIN users d ON a.doctor_id = d.id
            JOIN doctors doc ON d.id = doc.user_id
            JOIN specializations s ON doc.specialization_id = s.id
            GROUP BY a.doctor_id
            ORDER BY total_appointments DESC";
    return $this->db->query($sql);
}

// Get peak hours report
public function getPeakHoursReport() {
    $sql = "SELECT 
                HOUR(appointment_time) as hour,
                COUNT(*) as appointment_count
            FROM appointments
            GROUP BY HOUR(appointment_time)
            ORDER BY appointment_count DESC";
    return $this->db->query($sql);
}

// Get peak days report
public function getPeakDaysReport() {
    $sql = "SELECT 
                DAYNAME(appointment_date) as day_name,
                COUNT(*) as appointment_count
            FROM appointments
            GROUP BY DAYNAME(appointment_date)
            ORDER BY FIELD(DAYNAME(appointment_date), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
    return $this->db->query($sql);
}

// Get most in-demand specializations
public function getTopSpecializations($limit = 5) {
    $sql = "SELECT 
                s.name as specialization_name,
                COUNT(a.id) as appointment_count
            FROM appointments a
            JOIN doctors doc ON a.doctor_id = doc.user_id
            JOIN specializations s ON doc.specialization_id = s.id
            GROUP BY doc.specialization_id
            ORDER BY appointment_count DESC
            LIMIT ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result();
}


//Patient
// Get available time slots for a doctor on a specific date
public function getAvailableSlots($doctorId, $date) {
    $dayOfWeek = date('l', strtotime($date));
    
    $sql = "SELECT start_time, end_time, slot_duration_minutes 
            FROM doctor_availability 
            WHERE doctor_id = ? AND day_of_week = ? AND is_available = 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("is", $doctorId, $dayOfWeek);
    $stmt->execute();
    $availability = $stmt->get_result()->fetch_assoc();
    
    if (!$availability) {
        return [];
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
    
    return $slots;
}

// Check if slot is available
public function isSlotAvailable($doctorId, $date, $time) {
    $sql = "SELECT COUNT(*) as count FROM appointments 
            WHERE doctor_id = ? AND appointment_date = ? AND appointment_time = ? 
            AND status NOT IN ('cancelled', 'no_show')";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("iss", $doctorId, $date, $time);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    return $result['count'] == 0;
}

// Book appointment
public function book($patientId, $doctorId, $date, $time, $reason, $bookedBy = 'patient', $dependentId = null) {
    $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason, status, booked_by) 
            VALUES (?, ?, ?, ?, ?, 'pending', ?)";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("iissss", $patientId, $doctorId, $date, $time, $reason, $bookedBy);
    
    if ($stmt->execute()) {
        $appointmentId = $this->db->insert_id;
        
        // Create billing record
        // Get doctor's consultation fee
        $sql = "SELECT consultation_fee FROM doctors WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $doctorId);
        $stmt->execute();
        $fee = $stmt->get_result()->fetch_assoc()['consultation_fee'];
        
        $sql = "INSERT INTO billing (appointment_id, patient_id, amount, payment_status) VALUES (?, ?, ?, 'pending')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iid", $appointmentId, $patientId, $fee);
        $stmt->execute();
        
        return true;
    }
    return false;
}
// Get today's appointments for a doctor
public function getTodayAppointments($doctorId) {
    $today = date('Y-m-d');
    $sql = "SELECT a.*, p.name as patient_name, p.email as patient_email, p.phone as patient_phone,
                   p.date_of_birth, p.blood_group, p.gender
            FROM appointments a
            JOIN users p ON a.patient_id = p.id
            WHERE a.doctor_id = ? AND a.appointment_date = ?
            ORDER BY a.appointment_time ASC";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("is", $doctorId, $today);
    $stmt->execute();
    return $stmt->get_result();
}

// Update appointment status (for check-in)
public function updateStatus($appointmentId, $status) {
    $sql = "UPDATE appointments SET status = ? WHERE id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("si", $status, $appointmentId);
    return $stmt->execute();
}

// Get weekly appointments
public function getWeeklyAppointments($doctorId) {
    $startOfWeek = date('Y-m-d', strtotime('monday this week'));
    $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
    
    $sql = "SELECT a.*, p.name as patient_name, 
                   DAYNAME(a.appointment_date) as day_name
            FROM appointments a
            JOIN users p ON a.patient_id = p.id
            WHERE a.doctor_id = ? AND a.appointment_date BETWEEN ? AND ?
            ORDER BY a.appointment_date ASC, a.appointment_time ASC";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("iss", $doctorId, $startOfWeek, $endOfWeek);
    $stmt->execute();
    return $stmt->get_result();
}


}
?>