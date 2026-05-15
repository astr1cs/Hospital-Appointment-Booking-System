<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Report - <?php echo date('F d, Y', strtotime($date)); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px;
            background: white;
        }
        .report-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .report-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .report-header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .report-header p {
            color: #666;
            font-size: 12px;
        }
        .report-date {
            text-align: right;
            margin-bottom: 20px;
            font-size: 12px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .summary-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .summary-table td:first-child {
            background: #f5f5f5;
            font-weight: bold;
        }
        .doctor-section {
            margin-bottom: 30px;
            border: 1px solid #ddd;
        }
        .doctor-title {
            background: #f5f5f5;
            padding: 10px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
        }
        .appointments-table th,
        .appointments-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .appointments-table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #999;
        }
        .print-btn {
            text-align: center;
            margin-top: 30px;
        }
        .print-btn button {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            cursor: pointer;
        }
        @media print {
            body {
                padding: 20px;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="report-container">
    <div class="report-header">
        <h1>City Hospital - Daily Report</h1>
        <p>123 Healthcare Ave, Medical District | Phone: +1 234 567 890</p>
    </div>
    
    <div class="report-date">
        Report Date: <?php echo date('F d, Y', strtotime($date)); ?>
    </div>
    
    <table class="summary-table">
        <tr>
            <td>Total Appointments</td>
            <td><?php echo $stats['total']; ?></td>
            <td>Confirmed</td>
            <td><?php echo $stats['confirmed']; ?></td>
        </tr>
        <tr>
            <td>Pending</td>
            <td><?php echo $stats['pending']; ?></td>
            <td>Checked In</td>
            <td><?php echo $stats['checked_in']; ?></td>
        </tr>
        <tr>
            <td>Completed</td>
            <td><?php echo $stats['completed']; ?></td>
            <td>Cancelled</td>
            <td><?php echo $stats['cancelled']; ?></td>
        </tr>
        <tr>
            <td colspan="2">Total Revenue</td>
            <td colspan="2">$<?php echo number_format($revenue['total_revenue'], 2); ?></td>
        </tr>
    </table>
    
    <?php
    // Group appointments by doctor
    $grouped = [];
    $appointments->data_seek(0);
    while ($row = $appointments->fetch_assoc()) {
        $doctorId = $row['doctor_id'];
        if (!isset($grouped[$doctorId])) {
            $grouped[$doctorId] = [
                'doctor_name' => $row['doctor_name'],
                'appointments' => []
            ];
        }
        $grouped[$doctorId]['appointments'][] = $row;
    }
    ?>
    
    <?php foreach($grouped as $doctor): ?>
    <div class="doctor-section">
        <div class="doctor-title">Dr. <?php echo htmlspecialchars($doctor['doctor_name']); ?></div>
        <table class="appointments-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Patient Name</th>
                    <th>Phone</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($doctor['appointments'] as $apt): ?>
                <tr>
                    <td><?php echo date('h:i A', strtotime($apt['appointment_time'])); ?></td>
                    <td><?php echo htmlspecialchars($apt['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($apt['patient_phone']); ?></td>
                    <td><?php echo ucfirst($apt['status']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endforeach; ?>
    
    <div class="footer">
        <p>This is a computer-generated report. No signature required.</p>
        <p>Generated on <?php echo date('F d, Y h:i A'); ?></p>
    </div>
</div>

<div class="print-btn">
    <button onclick="window.print();">Print Report</button>
</div>
</body>
</html>