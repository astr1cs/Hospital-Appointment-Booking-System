<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Report'; ?> - Hospital System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: white;
            padding: 40px;
        }
        .print-container {
            max-width: 1200px;
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
            color: #333;
        }
        .report-header p {
            color: #666;
            font-size: 12px;
        }
        .hospital-info {
            text-align: center;
            margin-bottom: 20px;
            color: #555;
        }
        .hospital-info h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .report-date {
            text-align: right;
            margin-bottom: 20px;
            font-size: 12px;
            color: #666;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
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
            border-bottom: 2px solid #ddd;
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
        .total-row td {
            padding: 12px 10px;
            border-top: 2px solid #ddd;
        }
        .report-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #999;
        }
        .no-print {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
        }
        .print-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        .print-btn:hover {
            background: #5a67d8;
        }
        @media print {
            body {
                padding: 20px;
            }
            .no-print {
                display: none;
            }
            .report-date {
                background: none;
                border: 1px solid #eee;
            }
        }
    </style>
</head>
<body>
<div class="print-container">