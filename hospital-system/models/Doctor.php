<?php
class Doctor {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Get all doctors
    public function getAll() {
        $sql = "SELECT u.*, d.*, s.name as specialization_name 
                FROM users u 
                INNER JOIN doctors d ON u.id = d.user_id 
                LEFT JOIN specializations s ON d.specialization_id = s.id 
                WHERE u.role = 'doctor'
                ORDER BY u.created_at DESC";
        
        return $this->db->query($sql);
    }
    
    // Get doctor by ID
    public function getById($id) {
        $sql = "SELECT u.*, d.*, s.name as specialization_name 
                FROM users u 
                INNER JOIN doctors d ON u.id = d.user_id 
                LEFT JOIN specializations s ON d.specialization_id = s.id 
                WHERE d.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Create doctor (admin creates - auto approved)
    public function create($userData, $doctorData) {
        $this->db->begin_transaction();
        
        try {
            // Insert into users table
            $sql = "INSERT INTO users (name, email, password_hash, phone, role, is_active) 
                    VALUES (?, ?, ?, ?, 'doctor', 1)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssss", $userData['name'], $userData['email'], 
                            $userData['password_hash'], $userData['phone']);
            $stmt->execute();
            $userId = $this->db->insert_id;
            $stmt->close();
            
            // Insert into doctors table (auto-approved = 1)
            $sql = "INSERT INTO doctors (user_id, specialization_id, bio, consultation_fee, 
                    license_number, experience_years, is_approved) 
                    VALUES (?, ?, ?, ?, ?, ?, 1)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iisdsi", $userId, $doctorData['specialization_id'], 
                            $doctorData['bio'], $doctorData['consultation_fee'], 
                            $doctorData['license_number'], $doctorData['experience_years']);
            $stmt->execute();
            $stmt->close();
            
            $this->db->commit();
            return ['success' => true, 'user_id' => $userId];
            
        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // Update doctor
    public function update($userId, $userData, $doctorData) {
        $this->db->begin_transaction();
        
        try {
            // Update users table
            $sql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("sssi", $userData['name'], $userData['email'], 
                            $userData['phone'], $userId);
            $stmt->execute();
            $stmt->close();
            
            // Update doctors table
            $sql = "UPDATE doctors SET specialization_id = ?, bio = ?, consultation_fee = ?, 
                    license_number = ?, experience_years = ? WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("isdssi", $doctorData['specialization_id'], $doctorData['bio'], 
                            $doctorData['consultation_fee'], $doctorData['license_number'], 
                            $doctorData['experience_years'], $userId);
            $stmt->execute();
            $stmt->close();
            
            $this->db->commit();
            return ['success' => true];
            
        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // Deactivate doctor
    public function deactivate($userId) {
        $sql = "UPDATE users SET is_active = 0 WHERE id = ? AND role = 'doctor'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
    
    // Activate doctor
    public function activate($userId) {
        $sql = "UPDATE users SET is_active = 1 WHERE id = ? AND role = 'doctor'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
    
    // Get statistics
    public function getStats() {
        $sql = "SELECT COUNT(*) as total 
                FROM doctors d
                JOIN users u ON d.user_id = u.id
                WHERE u.role = 'doctor' AND u.is_active = 1";
        
        $result = $this->db->query($sql);
        return $result->fetch_assoc();
    }

    // Get active doctors count
public function getActiveCount() {
    $sql = "SELECT COUNT(*) as count 
            FROM doctors d
            JOIN users u ON d.user_id = u.id
            WHERE u.role = 'doctor' AND u.is_active = 1 AND d.is_approved = 1";
    
    $result = $this->db->query($sql);
    return $result->fetch_assoc()['count'];
}

// Get pending doctors count
public function getPendingCount() {
    $sql = "SELECT COUNT(*) as count 
            FROM doctors d
            JOIN users u ON d.user_id = u.id
            WHERE u.role = 'doctor' AND d.is_approved = 0";
    
    $result = $this->db->query($sql);
    return $result->fetch_assoc()['count'];
}

// Get pending doctors list
public function getPending() {
    $sql = "SELECT u.*, d.*, s.name as specialization_name 
            FROM users u 
            INNER JOIN doctors d ON u.id = d.user_id 
            LEFT JOIN specializations s ON d.specialization_id = s.id 
            WHERE u.role = 'doctor' AND d.is_approved = 0 
            ORDER BY u.created_at ASC";
    
    return $this->db->query($sql);
}


// Get doctor performance report
public function getPerformanceReport() {
    $sql = "SELECT 
                u.id as doctor_id,
                u.name as doctor_name,
                s.name as specialization_name,
                COUNT(DISTINCT a.id) as total_appointments,
                SUM(CASE WHEN a.status = 'completed' THEN 1 ELSE 0 END) as completed_appointments,
                SUM(CASE WHEN a.status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_appointments,
                SUM(CASE WHEN a.status = 'no_show' THEN 1 ELSE 0 END) as no_show_appointments,
                ROUND(AVG(r.rating), 2) as avg_rating,
                COUNT(DISTINCT r.id) as total_reviews,
                ROUND(SUM(CASE WHEN a.status = 'no_show' THEN 1 ELSE 0 END) * 100.0 / COUNT(a.id), 2) as no_show_rate
            FROM users u
            JOIN doctors d ON u.id = d.user_id
            JOIN specializations s ON d.specialization_id = s.id
            LEFT JOIN appointments a ON u.id = a.doctor_id
            LEFT JOIN doctor_reviews r ON u.id = r.doctor_id AND a.id = r.appointment_id
            WHERE u.role = 'doctor' AND d.is_approved = 1
            GROUP BY u.id
            ORDER BY completed_appointments DESC";
    return $this->db->query($sql);
}
//patients

// Get all approved doctors for patient browsing
public function getAllForPatients($search = null, $specializationId = null, $minFee = null, $maxFee = null) {
    $sql = "SELECT u.id as user_id, u.name, u.profile_pic, 
                   d.*, s.name as specialization_name,
                   COALESCE(AVG(r.rating), 0) as avg_rating,
                   COUNT(DISTINCT r.id) as total_reviews
            FROM users u 
            JOIN doctors d ON u.id = d.user_id 
            JOIN specializations s ON d.specialization_id = s.id 
            LEFT JOIN doctor_reviews r ON u.id = r.doctor_id
            WHERE u.role = 'doctor' AND u.is_active = 1 AND d.is_approved = 1";
    
    $params = [];
    $types = "";
    
    if ($search) {
        $searchTerm = "%{$search}%";
        $sql .= " AND (u.name LIKE ? OR s.name LIKE ?)";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= "ss";
    }
    
    if ($specializationId) {
        $sql .= " AND d.specialization_id = ?";
        $params[] = $specializationId;
        $types .= "i";
    }
    
    if ($minFee) {
        $sql .= " AND d.consultation_fee >= ?";
        $params[] = $minFee;
        $types .= "d";
    }
    
    if ($maxFee) {
        $sql .= " AND d.consultation_fee <= ?";
        $params[] = $maxFee;
        $types .= "d";
    }
    
    $sql .= " GROUP BY u.id ORDER BY avg_rating DESC, u.name ASC";
    
    $stmt = $this->db->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result();
}

// Get doctor details for patient view
public function getDoctorDetails($doctorUserId) {
    $sql = "SELECT u.id as user_id, u.name, u.email, u.phone, u.profile_pic,
                   d.*, s.name as specialization_name,
                   COALESCE(AVG(r.rating), 0) as avg_rating,
                   COUNT(DISTINCT r.id) as total_reviews
            FROM users u 
            JOIN doctors d ON u.id = d.user_id 
            JOIN specializations s ON d.specialization_id = s.id 
            LEFT JOIN doctor_reviews r ON u.id = r.doctor_id
            WHERE u.id = ? AND u.role = 'doctor' AND u.is_active = 1 AND d.is_approved = 1
            GROUP BY u.id";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $doctorUserId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Get doctor reviews
public function getDoctorReviews($doctorUserId, $limit = 10) {
    $sql = "SELECT r.*, u.name as patient_name
            FROM doctor_reviews r
            JOIN users u ON r.patient_id = u.id
            WHERE r.doctor_id = ?
            ORDER BY r.created_at DESC
            LIMIT ?";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ii", $doctorUserId, $limit);
    $stmt->execute();
    return $stmt->get_result();
}

// Get doctor availability schedule
public function getAvailability($doctorUserId) {
    $sql = "SELECT * FROM doctor_availability WHERE doctor_id = ? AND is_available = 1 ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $doctorUserId);
    $stmt->execute();
    return $stmt->get_result();
}

}
?>