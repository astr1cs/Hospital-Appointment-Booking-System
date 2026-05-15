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
            background: #f5f7fa;
        }
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .receipt-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .receipt-header h1 {
            margin-bottom: 10px;
            font-size: 28px;
        }
        .receipt-body {
            padding: 30px;
        }
        .receipt-title {
            text-align: center;
            margin-bottom: 30px;
        }
        .receipt-title h2 {
            color: #333;
            margin-bottom: 5px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: 600;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        .amount-row {
            background: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .amount-label {
            font-weight: bold;
            font-size: 16px;
        }
        .amount-value {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
            margin-top: 20px;
            color: #999;
            font-size: 12px;
        }
        .print-btn {
            display: block;
            width: 200px;
            margin: 20px auto 0;
            padding: 10px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        @media print {
            body {
                padding: 0;
                background: white;
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
        <h1>🏥 City Hospital</h1>
        <p>123 Healthcare Ave, Medical District</p>
        <p>Tel: +1 234 567 890 | Email: info@cityhospital.com</p>
    </div>
    <div class="receipt-body">
        <div class="receipt-title">
            <h2>PAYMENT RECEIPT</h2>
            <p>Receipt #: <?php echo str_pad($bill['id'], 8, '0', STR_PAD_LEFT); ?></p>
        </div>
        
        <div class="info-row">
            <span class="info-label">Date:</span>
            <span class="info-value"><?php echo date('F d, Y h:i A', strtotime($bill['paid_at'])); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Patient Name:</span>
            <span class="info-value"><?php echo htmlspecialchars($bill['patient_name']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Doctor:</span>
            <span class="info-value">Dr. <?php echo htmlspecialchars($bill['doctor_name']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Appointment Date:</span>
            <span class="info-value"><?php echo date('F d, Y', strtotime($bill['appointment_date'])); ?> at <?php echo date('h:i A', strtotime($bill['appointment_time'])); ?></span>
        </div>
        
        <div class="amount-row">
            <div style="display: flex; justify-content: space-between;">
                <span class="amount-label">Consultation Fee:</span>
                <span class="amount-value">$<?php echo number_format($bill['amount'], 2); ?></span>
            </div>
        </div>
        
        <div class="info-row">
            <span class="info-label">Payment Method:</span>
            <span class="info-value"><?php echo ucfirst($bill['payment_method']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Transaction Status:</span>
            <span class="info-value" style="color: #28a745;">Completed</span>
        </div>
        
        <div class="footer">
            <p>Thank you for choosing City Hospital</p>
            <p>This is a computer-generated receipt. No signature required.</p>
        </div>
    </div>
</div>
<button onclick="window.print();" class="print-btn">Print Receipt</button>
</body>
</html>