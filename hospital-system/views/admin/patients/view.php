<div class="page-header">
    <h1 class="page-title">Patient Details</h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=patients" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="patient-details-grid">
    <!-- Personal Information -->
    <div class="detail-card">
        <div class="card-header">
            <h3><i class="fas fa-user-circle"></i> Personal Information</h3>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <span class="detail-label">Full Name:</span>
                <span class="detail-value"><?php echo htmlspecialchars($patient['name']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value"><?php echo htmlspecialchars($patient['email']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone:</span>
                <span class="detail-value"><?php echo htmlspecialchars($patient['phone']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date of Birth:</span>
                <span class="detail-value"><?php echo $patient['date_of_birth'] ? date('M d, Y', strtotime($patient['date_of_birth'])) : 'Not provided'; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Gender:</span>
                <span class="detail-value"><?php echo ucfirst($patient['gender'] ?? 'Not specified'); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Blood Group:</span>
                <span class="detail-value"><?php echo $patient['blood_group'] ?? 'Not specified'; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value">
                    <?php if ($patient['is_active']): ?>
                        <span class="status-active">Active</span>
                    <?php else: ?>
                        <span class="status-inactive">Inactive</span>
                    <?php endif; ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Registered:</span>
                <span class="detail-value"><?php echo date('M d, Y h:i A', strtotime($patient['created_at'])); ?></span>
            </div>
        </div>
    </div>

    <!-- Address & Emergency Contact -->
    <div class="detail-card">
        <div class="card-header">
            <h3><i class="fas fa-map-marker-alt"></i> Address & Emergency</h3>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <span class="detail-label">Address:</span>
                <span class="detail-value"><?php echo nl2br(htmlspecialchars($patient['address'] ?? 'Not provided')); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Emergency Contact:</span>
                <span class="detail-value"><?php echo htmlspecialchars($patient['emergency_contact_name'] ?? 'Not provided'); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Emergency Phone:</span>
                <span class="detail-value"><?php echo htmlspecialchars($patient['emergency_contact_phone'] ?? 'Not provided'); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Medical History:</span>
                <span class="detail-value"><?php echo nl2br(htmlspecialchars($patient['medical_history_notes'] ?? 'No medical history recorded')); ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Appointment History -->
<div class="detail-card full-width">
    <div class="card-header">
        <h3><i class="fas fa-calendar-check"></i> Appointment History</h3>
    </div>
    <div class="card-body">
        <?php if ($appointments && $appointments->num_rows > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $appointments->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></td>
                        <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                        <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['specialization_name']); ?></td>
                        <td><?php echo htmlspecialchars(substr($row['reason'], 0, 50)); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $row['status']; ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">No appointment history found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Billing History -->
<div class="detail-card full-width">
    <div class="card-header">
        <h3><i class="fas fa-dollar-sign"></i> Billing History</h3>
    </div>
    <div class="card-body">
        <?php if ($billings && $billings->num_rows > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Appointment</th>
                        <th>Doctor</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $billings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                        <td>Appointment #<?php echo $row['appointment_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                        <td>$<?php echo number_format($row['amount'], 2); ?></td>
                        <td><?php echo ucfirst($row['payment_method'] ?? 'N/A'); ?></td>
                        <td>
                            <span class="payment-badge payment-<?php echo $row['payment_status']; ?>">
                                <?php echo ucfirst($row['payment_status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">No billing history found.</p>
        <?php endif; ?>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-title {
    font-size: 24px;
    margin: 0;
    color: #333;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
}

.patient-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 25px;
}

.detail-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.detail-card.full-width {
    grid-column: span 2;
    margin-bottom: 25px;
}

.card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
}

.card-header h3 {
    margin: 0;
    font-size: 16px;
    color: #333;
}

.card-body {
    padding: 20px;
}

.detail-row {
    display: flex;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    width: 140px;
    font-weight: 600;
    color: #555;
    font-size: 13px;
}

.detail-value {
    flex: 1;
    color: #333;
    font-size: 13px;
}

.status-active {
    background: #d4edda;
    color: #155724;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    display: inline-block;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    display: inline-block;
}

.status-badge {
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    display: inline-block;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-confirmed {
    background: #d4edda;
    color: #155724;
}

.status-completed {
    background: #cce5ff;
    color: #004085;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.payment-badge {
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    display: inline-block;
}

.payment-paid {
    background: #d4edda;
    color: #155724;
}

.payment-pending {
    background: #fff3cd;
    color: #856404;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #eee;
    font-size: 13px;
}

.data-table th {
    background: #f8f9fa;
    font-weight: 600;
}

.text-muted {
    text-align: center;
    padding: 20px;
    color: #999;
}

@media (max-width: 768px) {
    .patient-details-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .detail-card.full-width {
        grid-column: span 1;
    }
    
    .detail-row {
        flex-direction: column;
    }
    
    .detail-label {
        width: 100%;
        margin-bottom: 5px;
    }
}
</style>