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


1	Dashboard with statistics	✅
2	Today's Schedule (AJAX Check-in)	✅
3	Weekly Schedule	✅
4	All Appointments with Filters	✅
5	Appointment Details View	✅
6	Consultation Notes	✅
7	Schedule Management (Availability)	✅
8	Leave Dates Management	✅
9	Profile Management (AJAX update)	✅
10	Patient Medical History View	✅
11	Earnings Report	✅
12	Appointment Statistics	✅
13	Patient Reviews View	✅
14	Follow-up Appointments List	✅

