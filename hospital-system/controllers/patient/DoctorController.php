<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/Doctor.php';
require_once __DIR__ . '/../../models/Specialization.php';

class DoctorController extends PatientBaseController {
    
    // Browse doctors
    public function index() {
        $doctorModel = new Doctor();
        $specializationModel = new Specialization();
        
        // Get filter parameters
        $search = $_GET['search'] ?? null;
        $specializationId = $_GET['specialization'] ?? null;
        $minFee = $_GET['min_fee'] ?? null;
        $maxFee = $_GET['max_fee'] ?? null;
        
        $doctors = $doctorModel->getAllForPatients($search, $specializationId, $minFee, $maxFee);
        $specializations = $specializationModel->getAll();
        
        $data = [
            'title' => 'Find Doctors',
            'doctors' => $doctors,
            'specializations' => $specializations,
            'search' => $search,
            'selectedSpecialization' => $specializationId,
            'minFee' => $minFee,
            'maxFee' => $maxFee
        ];
        
        $this->view('doctors/index', $data);
    }
    
    // View doctor details
    public function show($id) {
        $doctorModel = new Doctor();
        
        $doctor = $doctorModel->getDoctorDetails($id);
        
        if (!$doctor) {
            $_SESSION['error'] = 'Doctor not found';
            $this->redirect('patient.php?action=doctors');
            return;
        }
        
        $reviews = $doctorModel->getDoctorReviews($id);
        $availability = $doctorModel->getAvailability($id);
        
        $data = [
            'title' => $doctor['name'],
            'doctor' => $doctor,
            'reviews' => $reviews,
            'availability' => $availability
        ];
        
        $this->view('doctors/details', $data);
    }
}
?>