<?php
require_once 'includes/init.php';
SessionManager::requireRole('doctor');

$action = $_GET['action'] ?? 'dashboard';
$subAction = $_GET['sub'] ?? 'index';
$id = $_GET['id'] ?? null;

$action = preg_replace('/[^a-zA-Z-]/', '', $action);
$subAction = preg_replace('/[^a-zA-Z]/', '', $subAction);

$controllerMap = [
    'dashboard' => 'DashboardController',
    'profile' => 'ProfileController',
    'schedule' => 'ScheduleController',
    'appointments' => 'AppointmentController',
    'consultation' => 'ConsultationController',
    'patients' => 'PatientController',
    'reports' => 'ReportController',
    'reviews' => 'ReviewController'
];

if (!isset($controllerMap[$action])) {
    die('Invalid action: ' . $action);
}

$controllerClass = $controllerMap[$action];
$controllerPath = 'controllers/doctor/' . $controllerClass . '.php';

if (!file_exists($controllerPath)) {
    die('Controller not found: ' . $controllerPath);
}

require_once $controllerPath;
$controller = new $controllerClass();

if (method_exists($controller, $subAction)) {
    if ($id) {
        $controller->$subAction($id);
    } else {
        $controller->$subAction();
    }
} else {
    die('Method ' . $subAction . ' not found in ' . $controllerClass);
}
?>