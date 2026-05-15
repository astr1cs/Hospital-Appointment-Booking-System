<?php
require_once __DIR__ . '/BaseController.php';

class PatientController extends ReceptionistBaseController {
    
    // Search patients
    public function search() {
        $search = $_GET['search'] ?? '';
        $patients = [];
        
        if (!empty($search)) {
            $searchTerm = "%{$search}%";
            $sql = "SELECT u.*, 
                           COUNT(a.id) as total_visits,
                           SUM(CASE WHEN b.payment_status = 'pending' THEN b.amount ELSE 0 END) as pending_amount
                    FROM users u
                    LEFT JOIN appointments a ON u.id = a.patient_id
                    LEFT JOIN billing b ON a.id = b.appointment_id
                    WHERE u.role = 'patient' AND (u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)
                    GROUP BY u.id
                    ORDER BY u.name";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
            $stmt->execute();
            $patients = $stmt->get_result();
        }
        
        $data = [
            'title' => 'Search Patients',
            'patients' => $patients,
            'search' => $search,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('patients/search', $data);
    }
    
    // View patient details
   public function show($id) {
        $sql = "SELECT u.*, 
                       p.date_of_birth, p.blood_group, p.gender, p.address,
                       p.emergency_contact_name, p.emergency_contact_phone, p.medical_history_notes
                FROM users u
                LEFT JOIN patients p ON u.id = p.user_id
                WHERE u.id = ? AND u.role = 'patient'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $patient = $stmt->get_result()->fetch_assoc();
        
        if (!$patient) {
            $_SESSION['error'] = 'Patient not found';
            $this->redirect('receptionist.php?action=patients&sub=search');
            return;
        }
        
        // Get upcoming appointments
        $sql = "SELECT a.*, d.name as doctor_name
                FROM appointments a
                JOIN users d ON a.doctor_id = d.id
                WHERE a.patient_id = ? AND a.appointment_date >= CURDATE() AND a.status NOT IN ('cancelled', 'completed')
                ORDER BY a.appointment_date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $upcoming = $stmt->get_result();
        
        // Get past appointments
        $sql = "SELECT a.*, d.name as doctor_name
                FROM appointments a
                JOIN users d ON a.doctor_id = d.id
                WHERE a.patient_id = ? AND (a.appointment_date < CURDATE() OR a.status = 'completed')
                ORDER BY a.appointment_date DESC
                LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $past = $stmt->get_result();
        
        // Get billing summary
        $sql = "SELECT 
                    SUM(CASE WHEN payment_status = 'paid' THEN amount ELSE 0 END) as total_paid,
                    SUM(CASE WHEN payment_status = 'pending' THEN amount ELSE 0 END) as total_pending
                FROM billing
                WHERE patient_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $billing = $stmt->get_result()->fetch_assoc();
        
        $data = [
            'title' => 'Patient Details',
            'patient' => $patient,
            'upcoming' => $upcoming,
            'past' => $past,
            'billing' => $billing
        ];
        
        $this->view('patients/view', $data);
    }
    
    // Show register form
    public function register() {
        $data = [
            'title' => 'Register New Patient',
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['error']);
        
        $this->view('patients/register', $data);
    }
    
    // Store new patient
   // Store new patient
// Store new patient
public function store() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('receptionist.php?action=patients&sub=register');
        return;
    }
    
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? 'password123';
    $dateOfBirth = $_POST['date_of_birth'] ?? null;
    $bloodGroup = $_POST['blood_group'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $address = $_POST['address'] ?? null;
    $emergencyName = $_POST['emergency_contact_name'] ?? null;
    $emergencyPhone = $_POST['emergency_contact_phone'] ?? null;
    
    // Validate
    if (empty($name) || empty($email) || empty($phone)) {
        $_SESSION['error'] = 'Name, email and phone are required';
        $this->redirect('receptionist.php?action=patients&sub=register');
        return;
    }
    
    // Check if email exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['error'] = 'Email already exists';
        $this->redirect('receptionist.php?action=patients&sub=register');
        return;
    }
    
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    
    $this->db->begin_transaction();
    
    try {
        // Insert into users
        $sql = "INSERT INTO users (name, email, password_hash, phone, role, is_active) 
                VALUES (?, ?, ?, ?, 'patient', 1)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $passwordHash, $phone);
        $stmt->execute();
        $userId = $this->db->insert_id;
        
        // Insert into patients
        $sql = "INSERT INTO patients (user_id, date_of_birth, blood_group, gender, address, 
                emergency_contact_name, emergency_contact_phone) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("issssss", $userId, $dateOfBirth, $bloodGroup, $gender, 
                        $address, $emergencyName, $emergencyPhone);
        $stmt->execute();
        
        $this->db->commit();
        
        // Set success message and stay on register page
        $_SESSION['success'] = 'Patient registered successfully! You can now book an appointment.';
        $this->redirect('receptionist.php?action=patients&sub=register');
        
    } catch (Exception $e) {
        $this->db->rollback();
        $_SESSION['error'] = 'Failed to register patient: ' . $e->getMessage();
        $this->redirect('receptionist.php?action=patients&sub=register');
    }
}


}
?>