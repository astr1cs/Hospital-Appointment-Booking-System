<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?> - Hospital System</title>
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
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f5f5f5;
            font-weight: 600;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .revenue {
            color: #28a745;
            font-weight: bold;
        }
        .total-row {
            background: #f8f9fa;
            font-weight: bold;
        }
        .report-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #999;
        }
        @media print {
            body {
                padding: 20px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1><?php echo htmlspecialchars($title); ?></h1>
        <p>Hospital Appointment System - Generated Report</p>
    </div>
    
    <div class="report-date">
        Generated: <?php echo date('F d, Y h:i A'); ?>
    </div>
    
    <?php if (isset($revenueData)): ?>
        <h3>Revenue Over Time</h3>
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
                <tr><th>Doctor</th><th>Specialization</th><th class="text-center">Invoices</th><th class="text-right">Revenue</th></tr>
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
    <?php endif; ?>
    
    <?php if (isset($volumeData)): ?>
        <h3>Busiest Doctors</h3>
        <table>
            <thead>
                <tr><th>Doctor</th><th>Specialization</th><th class="text-center">Total</th><th class="text-center">Completed</th><th class="text-center">Completion Rate</th></tr>
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
            <thead><tr><th>Hour</th><th class="text-center">Appointments</th></tr></thead>
            <tbody>
                <?php while($row = $peakHours->fetch_assoc()): ?>
                <tr><td><?php echo date('g:i A', mktime($row['hour'], 0)); ?> - <?php echo date('g:i A', mktime($row['hour'] + 1, 0)); ?></td><td class="text-center"><?php echo $row['appointment_count']; ?></td></tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <h3>Busiest Days</h3>
        <table>
            <thead><tr><th>Day</th><th class="text-center">Appointments</th></tr></thead>
            <tbody>
                <?php while($row = $peakDays->fetch_assoc()): ?>
                <tr><td><?php echo $row['day_name']; ?></td><td class="text-center"><?php echo $row['appointment_count']; ?></td></tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <?php if (isset($performanceData)): ?>
        <h3>Doctor Performance Metrics</h3>
        <table>
            <thead>
                <tr><th>Doctor</th><th>Specialization</th><th class="text-center">Total</th><th class="text-center">Completed</th><th class="text-center">No Show Rate</th><th class="text-center">Avg Rating</th></tr>
            </thead>
            <tbody>
                <?php while($row = $performanceData->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['specialization_name']); ?></td>
                    <td class="text-center"><?php echo $row['total_appointments']; ?></td>
                    <td class="text-center"><?php echo $row['completed_appointments']; ?></td>
                    <td class="text-center"><?php echo $row['no_show_rate']; ?>%</td>
                    <td class="text-center"><?php echo $row['avg_rating'] ? number_format($row['avg_rating'], 1) . ' ★' : 'No ratings'; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <div class="report-footer">
        <p>This is a computer-generated report. No signature is required.</p>
        <p>© <?php echo date('Y'); ?> Hospital Appointment System</p>
    </div>
    
    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print();" style="padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer;">Print Report</button>
    </div>
</body>
</html>