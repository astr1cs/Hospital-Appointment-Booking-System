hospital-system/
│
├── controllers/
│   └── doctor/
│       ├── BaseController.php
│       ├── DashboardController.php
│       ├── ProfileController.php
│       ├── ScheduleController.php
│       ├── AppointmentController.php
│       ├── ConsultationController.php
│       ├── PatientController.php
│       ├── ReportController.php
│       └── ReviewController.php
│
├── models/
│   ├── Doctor.php (enhance)
│   ├── Appointment.php (enhance)
│   ├── ConsultationNote.php (new)
│   └── LeaveDate.php (new)
│
├── views/
│   └── doctor/
│       ├── layouts/
│       │   ├── header.php
│       │   └── footer.php
│       ├── dashboard.php
│       ├── profile/
│       │   └── index.php
│       ├── schedule/
│       │   ├── index.php
│       │   └── leaves.php
│       ├── appointments/
│       │   ├── index.php
│       │   ├── today.php
│       │   └── consult.php
│       ├── patients/
│       │   └── history.php
│       ├── reports/
│       │   └── earnings.php
│       └── reviews/
│           └── index.php
│
├── doctor.php                           # Front controller
└── assets/css/doctor.css                # Doctor-specific styles