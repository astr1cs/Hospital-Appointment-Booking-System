<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Doctor.php';
require_once __DIR__ . '/../../models/Appointment.php';
require_once __DIR__ . '/../../models/Billing.php';

class DashboardController extends BaseController {
    
    public function index() {
        $userModel = new User();
        $doctorModel = new Doctor();
        $appointmentModel = new Appointment();
        $billingModel = new Billing();
        
        // Get today's date
        $today = date('Y-m-d');
        
        // Get statistics
        $stats = [
            'today_appointments' => $appointmentModel->getCountByDate($today),
            'total_patients' => $userModel->getCountByRole('patient'),
            'active_doctors' => $doctorModel->getActiveCount(),
            'pending_billings' => $billingModel->getPendingTotal()
        ];
        
        // Get recent appointments (last 5)
        $recentAppointments = $appointmentModel->getRecent(5);
        
        // Get pending doctor approvals (if any - though we removed approval, keep for any with is_approved=0)
        $pendingDoctors = $doctorModel->getPendingCount();
        $pendingDoctorsList = $doctorModel->getPending();
        
        $data = [
            'title' => 'Dashboard',
            'stats' => $stats,
            'recentAppointments' => $recentAppointments,
            'pendingDoctors' => $pendingDoctors,
            'pendingDoctorsList' => $pendingDoctorsList,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('dashboard', $data);
    }
}
?>