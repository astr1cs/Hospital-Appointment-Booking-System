<div class="page-header">
    <h1 class="page-title">Complaint Details</h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=complaints" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="complaint-details">
    <!-- Complaint Information -->
    <div class="detail-card">
        <div class="card-header">
            <h3><i class="fas fa-info-circle"></i> Complaint Information</h3>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Complaint ID:</span>
                    <span class="detail-value">#<?php echo $complaint['id']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="status-badge status-<?php echo $complaint['status']; ?>">
                        <?php echo ucfirst($complaint['status']); ?>
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Subject:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($complaint['subject']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Submitted:</span>
                    <span class="detail-value"><?php echo date('F d, Y h:i A', strtotime($complaint['created_at'])); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Information -->
    <div class="detail-card">
        <div class="card-header">
            <h3><i class="fas fa-user"></i> Patient Information</h3>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($complaint['patient_name']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($complaint['patient_email']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($complaint['patient_phone']); ?></span>
                </div>
                <?php if ($complaint['appointment_date']): ?>
                <div class="detail-item">
                    <span class="detail-label">Related Appointment:</span>
                    <span class="detail-value">
                        <?php echo date('M d, Y', strtotime($complaint['appointment_date'])); ?>
                        with Dr. <?php echo htmlspecialchars($complaint['doctor_name']); ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Complaint Message -->
    <div class="detail-card">
        <div class="card-header">
            <h3><i class="fas fa-envelope"></i> Complaint Message</h3>
        </div>
        <div class="card-body">
            <div class="message-box">
                <?php echo nl2br(htmlspecialchars($complaint['message'])); ?>
            </div>
        </div>
    </div>

    <!-- Admin Response (if resolved) -->
    <?php if ($complaint['status'] != 'pending' && $complaint['admin_response']): ?>
    <div class="detail-card">
        <div class="card-header">
            <h3><i class="fas fa-reply"></i> Admin Response</h3>
        </div>
        <div class="card-body">
            <div class="response-box">
                <div class="response-meta">
                    Resolved on: <?php echo date('F d, Y h:i A', strtotime($complaint['resolved_at'])); ?>
                </div>
                <div class="response-message">
                    <?php echo nl2br(htmlspecialchars($complaint['admin_response'])); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Response Form (for pending complaints only) -->
    <?php if ($complaint['status'] == 'pending'): ?>
    <div class="detail-card">
        <div class="card-header">
            <h3><i class="fas fa-edit"></i> Admin Response</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo SITE_URL; ?>admin.php?action=complaints&sub=resolve&id=<?php echo $complaint['id']; ?>">
                <div class="form-group">
                    <label>Response Message <span class="required">*</span></label>
                    <textarea name="admin_response" class="form-control" rows="5" 
                              placeholder="Enter your response to this complaint..." required></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-resolve">
                        <i class="fas fa-check"></i> Mark as Resolved
                    </button>
                    <button type="submit" formaction="<?php echo SITE_URL; ?>admin.php?action=complaints&sub=reject&id=<?php echo $complaint['id']; ?>" 
                            class="btn-reject" onclick="return confirm('Reject this complaint?')">
                        <i class="fas fa-times"></i> Reject
                    </button>
                    <a href="<?php echo SITE_URL; ?>admin.php?action=complaints" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
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

.complaint-details {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.detail-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
}

.card-header h3 {
    margin: 0;
    font-size: 16px;
}

.card-body {
    padding: 20px;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.detail-item {
    display: flex;
    padding: 5px 0;
}

.detail-label {
    width: 140px;
    font-weight: 600;
    color: #555;
}

.detail-value {
    flex: 1;
    color: #333;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-resolved {
    background: #d4edda;
    color: #155724;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
}

.message-box {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    line-height: 1.6;
    color: #333;
}

.response-box {
    background: #e8eaf6;
    padding: 20px;
    border-radius: 8px;
}

.response-meta {
    font-size: 12px;
    color: #666;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #c5cae9;
}

.response-message {
    line-height: 1.6;
    color: #333;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.required {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
}

textarea.form-control {
    resize: vertical;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 25px;
}

.btn-resolve {
    background: #28a745;
    color: white;
    padding: 10px 24px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-resolve:hover {
    background: #218838;
}

.btn-reject {
    background: #dc3545;
    color: white;
    padding: 10px 24px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-reject:hover {
    background: #c82333;
}

.btn-cancel {
    background: #6c757d;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

@media (max-width: 768px) {
    .detail-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .detail-item {
        flex-direction: column;
    }
    
    .detail-label {
        width: 100%;
        margin-bottom: 5px;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>