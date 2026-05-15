<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
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
        .receipt-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .receipt-header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .receipt-header p {
            color: #666;
            font-size: 12px;
        }
        .receipt-title {
            text-align: center;
            margin-bottom: 25px;
        }
        .receipt-title h2 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .info-table td:first-child {
            width: 150px;
            font-weight: 600;
        }
        .amount-box {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            text-align: right;
            border: 1px solid #ddd;
        }
        .amount-box .label {
            font-size: 14px;
        }
        .amount-box .value {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            margin-left: 20px;
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
<div class="receipt-container">
    <div class="receipt-header">
        <h1>City Hospital</h1>
        <p>123 Healthcare Ave, Medical District</p>
        <p>Phone: +1 234 567 890 | Email: info@cityhospital.com</p>
    </div>
    
    <div class="receipt-title">
        <h2>PAYMENT RECEIPT</h2>
        <p>Receipt No: <?php echo str_pad($bill['id'], 6, '0', STR_PAD_LEFT); ?></p>
    </div>
    
    <table class="info-table">
        <tr>
            <td>Date & Time:</td>
            <td><?php echo date('F d, Y h:i A', strtotime($bill['paid_at'] ?? $bill['created_at'])); ?></td>
        </tr>
        <tr>
            <td>Patient Name:</td>
            <td><?php echo htmlspecialchars($bill['patient_name']); ?></td>
        </tr>
        <tr>
            <td>Doctor:</td>
            <td>Dr. <?php echo htmlspecialchars($bill['doctor_name']); ?></td>
        </tr>
        <tr>
            <td>Appointment Date:</td>
            <td><?php echo date('F d, Y', strtotime($bill['appointment_date'])); ?> at <?php echo date('h:i A', strtotime($bill['appointment_time'])); ?></td>
        </tr>
        <tr>
            <td>Payment Method:</td>
            <td><?php echo ucfirst($bill['payment_method']); ?></td>
        </tr>
        <tr>
            <td>Status:</td>
            <td style="color: #28a745;">Paid</td>
        </tr>
    </table>
    
    <div class="amount-box">
        <span class="label">Total Amount Paid:</span>
        <span class="value">$<?php echo number_format($bill['amount'], 2); ?></span>
    </div>
    
    <div class="footer">
        <p>Thank you for choosing City Hospital</p>
        <p>This is a computer-generated receipt. No signature required.</p>
    </div>
</div>

<div class="print-btn">
    <button onclick="window.print();">Print Receipt</button>
</div>
</body>
</html>