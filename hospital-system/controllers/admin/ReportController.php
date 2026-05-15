<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/Billing.php';
require_once __DIR__ . '/../../models/Appointment.php';
require_once __DIR__ . '/../../models/Doctor.php';

class ReportController extends BaseController {
    
    // Default index - redirect to revenue report
    public function index() {
        $this->redirect('admin.php?action=reports&sub=revenue');
    }
    
    // Revenue Report
    public function revenue() {
        $billingModel = new Billing();
        
        $period = $_GET['period'] ?? 'month';
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        
        $revenueData = $billingModel->getRevenueReport($period, $startDate, $endDate);
        $revenueByDoctor = $billingModel->getRevenueByDoctor($startDate, $endDate);
        $revenueBySpecialization = $billingModel->getRevenueBySpecialization($startDate, $endDate);
        
        $data = [
            'title' => 'Revenue Report',
            'report_type' => 'revenue',
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'revenueData' => $revenueData,
            'revenueByDoctor' => $revenueByDoctor,
            'revenueBySpecialization' => $revenueBySpecialization
        ];
        
        $this->view('reports/revenue', $data);
    }
    
    // Appointment Volume Report
    public function volume() {
        $appointmentModel = new Appointment();
        
        $volumeData = $appointmentModel->getVolumeReport();
        $peakHours = $appointmentModel->getPeakHoursReport();
        $peakDays = $appointmentModel->getPeakDaysReport();
        $topSpecializations = $appointmentModel->getTopSpecializations(5);
        
        $data = [
            'title' => 'Appointment Volume Report',
            'report_type' => 'volume',
            'volumeData' => $volumeData,
            'peakHours' => $peakHours,
            'peakDays' => $peakDays,
            'topSpecializations' => $topSpecializations
        ];
        
        $this->view('reports/volume', $data);
    }
    
    // Doctor Performance Report
    public function performance() {
        $doctorModel = new Doctor();
        
        $performanceData = $doctorModel->getPerformanceReport();
        
        $data = [
            'title' => 'Doctor Performance Report',
            'report_type' => 'performance',
            'performanceData' => $performanceData
        ];
        
        $this->view('reports/performance', $data);
    }
    
    // Export to Printable HTML
// Export to Printable HTML (no sidebar)
public function export() {
    $type = $_GET['type'] ?? 'revenue';
    $period = $_GET['period'] ?? 'month';
    $startDate = $_GET['start_date'] ?? null;
    $endDate = $_GET['end_date'] ?? null;
    
    $billingModel = new Billing();
    $appointmentModel = new Appointment();
    $doctorModel = new Doctor();
    
    // Use print layout instead of admin layout
    require_once 'views/admin/layouts/print_header.php';
    
    switch($type) {
        case 'revenue':
            $revenueData = $billingModel->getRevenueReport($period, $startDate, $endDate);
            $revenueByDoctor = $billingModel->getRevenueByDoctor($startDate, $endDate);
            $revenueBySpecialization = $billingModel->getRevenueBySpecialization($startDate, $endDate);
            ?>
<div class="report-header">
    <h1>Revenue Report</h1>
    <p>Hospital Appointment System</p>
</div>
<div class="report-date">
    Generated: <?php echo date('F d, Y h:i A'); ?>
    <?php if($startDate || $endDate): ?>
    <br>Date Range: <?php echo $startDate ?: 'Start'; ?> to <?php echo $endDate ?: 'Today'; ?>
    <?php endif; ?>
</div>

<h3>Revenue Over Time (<?php echo ucfirst($period); ?>)</h3>
<table>
    <thead>
        <tr>
            <th>Period</th>
            <th class="text-center">Invoices</th>
            <th class="text-right">Revenue</th>
        </tr>
    </thead>
    <tbody>
        <?php 
                    $total_revenue = 0;
                    while($row = $revenueData->fetch_assoc()): 
                        $total_revenue += $row['total_revenue'];
                    ?>
        <tr>
            <td><?php echo $row['period_label'] ?? $row['period']; ?></td>
            <td class="text-center"><?php echo $row['invoice_count']; ?></td>
            <td class="text-right revenue">$<?php echo number_format($row['total_revenue'], 2); ?></td>
        </tr>
        <?php endwhile; ?>
        <tr class="total-row">
            <td colspan="2"><strong>Total Revenue</strong></td>
            <td class="text-right"><strong>$<?php echo number_format($total_revenue, 2); ?></strong></td>
        </tr>
    </tbody>
</table>

<h3>Revenue by Doctor</h3>
<table>
    <thead>
        <tr>
            <th>Doctor</th>
            <th>Specialization</th>
            <th class="text-center">Invoices</th>
            <th class="text-right">Revenue</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $revenueByDoctor->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
            <td><?php echo htmlspecialchars($row['specialization_name']); ?></td>
            <td class="text-center"><?php echo $row['invoice_count']; ?></td>
            <td class="text-right">$<?php echo number_format($row['total_revenue'], 2); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<h3>Revenue by Specialization</h3>
<table>
    <thead>
        <tr>
            <th>Specialization</th>
            <th class="text-center">Invoices</th>
            <th class="text-right">Revenue</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $revenueBySpecialization->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['specialization_name']); ?></td>
            <td class="text-center"><?php echo $row['invoice_count']; ?></td>
            <td class="text-right">$<?php echo number_format($row['total_revenue'], 2); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php
            break;
            
        case 'volume':
            $volumeData = $appointmentModel->getVolumeReport();
            $peakHours = $appointmentModel->getPeakHoursReport();
            $peakDays = $appointmentModel->getPeakDaysReport();
            ?>
<div class="report-header">
    <h1>Appointment Volume Report</h1>
    <p>Hospital Appointment System</p>
</div>
<div class="report-date">
    Generated: <?php echo date('F d, Y h:i A'); ?>
</div>

<h3>Busiest Doctors</h3>
<table>
    <thead>
        <tr>
            <th>Doctor</th>
            <th>Specialization</th>
            <th class="text-center">Total</th>
            <th class="text-center">Completed</th>
            <th class="text-center">Completion Rate</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $volumeData->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
            <td><?php echo htmlspecialchars($row['specialization_name']); ?></td>
            <td class="text-center"><?php echo $row['total_appointments']; ?></td>
            <td class="text-center"><?php echo $row['completed']; ?></td>
            <td class="text-center"><?php echo $row['completion_rate']; ?>%</td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<h3>Peak Hours</h3>
<table>
    <thead>
        <tr>
            <th>Hour</th>
            <th class="text-center">Appointments</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $peakHours->fetch_assoc()): ?>
        <tr>
            <td><?php echo date('g:i A', mktime($row['hour'], 0)); ?> -
                <?php echo date('g:i A', mktime($row['hour'] + 1, 0)); ?></td>
            <td class="text-center"><?php echo $row['appointment_count']; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<h3>Busiest Days</h3>
<table>
    <thead>
        <tr>
            <th>Day</th>
            <th class="text-center">Appointments</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $peakDays->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['day_name']; ?></td>
            <td class="text-center"><?php echo $row['appointment_count']; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php
            break;
            
        case 'performance':
            $performanceData = $doctorModel->getPerformanceReport();
            ?>
<div class="report-header">
    <h1>Doctor Performance Report</h1>
    <p>Hospital Appointment System</p>
</div>
<div class="report-date">
    Generated: <?php echo date('F d, Y h:i A'); ?>
</div>

<h3>Doctor Performance Metrics</h3>
<table>
    <thead>
        <tr>
            <th>Doctor</th>
            <th>Specialization</th>
            <th class="text-center">Total</th>
            <th class="text-center">Completed</th>
            <th class="text-center">No Show Rate</th>
            <th class="text-center">Avg Rating</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $performanceData->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
            <td><?php echo htmlspecialchars($row['specialization_name']); ?></td>
            <td class="text-center"><?php echo $row['total_appointments']; ?></td>
            <td class="text-center"><?php echo $row['completed_appointments']; ?></td>
            <td class="text-center"><?php echo $row['no_show_rate']; ?>%</td>
            <td class="text-center">
                <?php echo $row['avg_rating'] ? number_format($row['avg_rating'], 1) . ' ★' : 'No ratings'; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php
            break;
    }
    
    require_once 'views/admin/layouts/print_footer.php';
    exit();
}
}
?>