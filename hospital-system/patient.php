<?php
require_once 'includes/init.php';
SessionManager::requireRole('patient');

$action = $_GET['action'] ?? 'dashboard';
$subAction = $_GET['sub'] ?? 'index';
$id = $_GET['id'] ?? null;

$action = preg_replace('/[^a-zA-Z]/', '', $action);
$subAction = preg_replace('/[^a-zA-Z]/', '', $subAction);

$controllerMap = [
    'dashboard' => 'DashboardController',
    'profile' => 'ProfileController',
    'doctors' => 'DoctorController',
    'appointments' => 'AppointmentController',
    'medical-history' => 'MedicalHistoryController',
    'dependents' => 'DependentController',
    'billing' => 'BillingController',
    'reviews' => 'ReviewController',
    'announcements' => 'AnnouncementController'
];

if (!isset($controllerMap[$action])) {
    die('Invalid action');
}

$controllerClass = $controllerMap[$action];
$controllerPath = 'controllers/patient/' . $controllerClass . '.php';

if (!file_exists($controllerPath)) {
    die('Controller not found: ' . $controllerClass);
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