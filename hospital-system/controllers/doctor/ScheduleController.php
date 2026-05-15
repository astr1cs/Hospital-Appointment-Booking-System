<?php
require_once __DIR__ . '/BaseController.php';

class ScheduleController extends DoctorBaseController {
    
    // View schedule
    public function index() {
        $userId = $this->getUserId();
        
        // Get doctor's availability
        $sql = "SELECT * FROM doctor_availability WHERE doctor_id = ? ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $availability = $stmt->get_result();
        
        // Get leave dates
        $sql = "SELECT * FROM leave_dates WHERE doctor_id = ? AND leave_date >= CURDATE() ORDER BY leave_date";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $leaves = $stmt->get_result();
        
        $data = [
            'title' => 'My Schedule',
            'availability' => $availability,
            'leaves' => $leaves
        ];
        
        $this->view('schedule/index', $data);
    }
    
    // Update availability
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('doctor.php?action=schedule');
            return;
        }
        
        $userId = $this->getUserId();
        
        // Delete existing availability
        $sql = "DELETE FROM doctor_availability WHERE doctor_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        // Insert new availability
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $success = true;
        
        foreach ($days as $day) {
            $isAvailable = isset($_POST['available_' . $day]) ? 1 : 0;
            $startTime = $_POST['start_time_' . $day] ?? '09:00:00';
            $endTime = $_POST['end_time_' . $day] ?? '17:00:00';
            $slotDuration = $_POST['slot_duration_' . $day] ?? 30;
            
            if ($isAvailable) {
                $sql = "INSERT INTO doctor_availability (doctor_id, day_of_week, start_time, end_time, slot_duration_minutes, is_available) 
                        VALUES (?, ?, ?, ?, ?, 1)";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("isssi", $userId, $day, $startTime, $endTime, $slotDuration);
                if (!$stmt->execute()) {
                    $success = false;
                }
            }
        }
        
        if ($success) {
            $_SESSION['success'] = 'Schedule updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update schedule';
        }
        
        $this->redirect('doctor.php?action=schedule');
    }
    
    // Add leave date
    public function addLeave() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('doctor.php?action=schedule');
            return;
        }
        
        $userId = $this->getUserId();
        $leaveDate = $_POST['leave_date'] ?? null;
        $reason = $_POST['reason'] ?? '';
        
        if (!$leaveDate) {
            $_SESSION['error'] = 'Leave date is required';
            $this->redirect('doctor.php?action=schedule');
            return;
        }
        
        $sql = "INSERT INTO leave_dates (doctor_id, leave_date, reason) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iss", $userId, $leaveDate, $reason);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Leave date added successfully';
        } else {
            $_SESSION['error'] = 'Failed to add leave date';
        }
        
        $this->redirect('doctor.php?action=schedule');
    }
    
    // Remove leave date
    public function removeLeave($id) {
        $userId = $this->getUserId();
        
        $sql = "DELETE FROM leave_dates WHERE id = ? AND doctor_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $userId);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Leave date removed';
        } else {
            $_SESSION['error'] = 'Failed to remove leave date';
        }
        
        $this->redirect('doctor.php?action=schedule');
    }
}
?>