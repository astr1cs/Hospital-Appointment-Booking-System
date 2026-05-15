<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/Appointment.php';

class AppointmentController extends BaseController {
    
    // List all appointments with filters
    public function index() {
        $appointmentModel = new Appointment();
        
        // Get filter values from request
        $filters = [
            'doctor_id' => $_GET['doctor_id'] ?? null,
            'status' => $_GET['status'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null,
            'booking_source' => $_GET['booking_source'] ?? null
        ];
        
        // Remove empty filters
        $filters = array_filter($filters);
        
        $appointments = $appointmentModel->getAll($filters);
        $stats = $appointmentModel->getStats($filters);
        $doctors = $appointmentModel->getAllDoctors();
        
        $data = [
            'title' => 'All Appointments',
            'appointments' => $appointments,
            'stats' => $stats,
            'doctors' => $doctors,
            'filters' => $filters,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('appointments/index', $data);
    }
    
    // Show appointment details (renamed from view to show)
    public function show($id) {
        $appointmentModel = new Appointment();
        $appointment = $appointmentModel->getById($id);
        
        if (!$appointment) {
            $_SESSION['error'] = 'Appointment not found';
            $this->redirect('admin.php?action=appointments');
            return;
        }
        
        $data = [
            'title' => 'Appointment Details',
            'appointment' => $appointment
        ];
        
        $this->view('appointments/view', $data);
    }
    
    // Clear filters and reset
    public function clear() {
        $this->redirect('admin.php?action=appointments');
    }
}
?>