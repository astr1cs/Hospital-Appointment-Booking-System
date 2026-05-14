<div class="page-header">
    <h1 class="page-title">Patient Complaints</h1>
</div>

<!-- Statistics Cards -->
<div class="stats-row">
    <div class="stat-card-mini">
        <div class="stat-value"><?php echo $stats['total']; ?></div>
        <div class="stat-label">Total</div>
    </div>
    <div class="stat-card-mini stat-pending">
        <div class="stat-value"><?php echo $stats['pending']; ?></div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="stat-card-mini stat-resolved">
        <div class="stat-value"><?php echo $stats['resolved']; ?></div>
        <div class="stat-label">Resolved</div>
    </div>
    <div class="stat-card-mini stat-rejected">
        <div class="stat-value"><?php echo $stats['rejected']; ?></div>
        <div class="stat-label">Rejected</div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="filter-tabs">
    <a href="<?php echo SITE_URL; ?>admin.php?action=complaints&status=all"
        class="tab <?php echo $currentStatus == 'all' ? 'active' : ''; ?>">All</a>
    <a href="<?php echo SITE_URL; ?>admin.php?action=complaints&status=pending"
        class="tab <?php echo $currentStatus == 'pending' ? 'active' : ''; ?>">Pending</a>
    <a href="<?php echo SITE_URL; ?>admin.php?action=complaints&status=resolved"
        class="tab <?php echo $currentStatus == 'resolved' ? 'active' : ''; ?>">Resolved</a>
    <a href="<?php echo SITE_URL; ?>admin.php?action=complaints&status=rejected"
        class="tab <?php echo $currentStatus == 'rejected' ? 'active' : ''; ?>">Rejected</a>
</div>

<?php if (isset($success) && $success): ?>
<div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-header">
        <h3><i class="fas fa-comment-dots"></i> Complaints List</h3>
        <span class="badge"><?php echo $complaints->num_rows; ?> records</span>
    </div>
    <div class="card-body">
        <?php if ($complaints->num_rows > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $complaints->fetch_assoc()): ?>
                <tr id="complaint-<?php echo $row['id']; ?>">
                    <td><?php echo $row['id']; ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($row['patient_name']); ?></strong><br>
                        <small><?php echo htmlspecialchars($row['patient_email']); ?></small>
                    </td>
                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                    <td>
                        <span class="status-badge status-<?php echo $row['status']; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="<?php echo SITE_URL; ?>admin.php?action=complaints&sub=show&id=<?php echo $row['id']; ?>"
                            class="btn-icon btn-view" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="btn-icon btn-delete" title="Delete"
                            onclick="deleteComplaint(<?php echo $row['id']; ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p class="text-muted text-center">No complaints found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- AJAX Script for Delete -->
<script>
function deleteComplaint(id) {
    if (confirm('Are you sure you want to delete this complaint?')) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo SITE_URL; ?>admin.php?action=complaints&sub=ajaxDelete', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            var element = document.getElementById('complaint-' + id);
                            if (element) {
                                element.remove();
                            }
                            showMessage(response.message, 'success');
                            // Update stats
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            showMessage(response.message, 'error');
                        }
                    } catch (e) {
                        showMessage('Error deleting complaint', 'error');
                    }
                } else {
                    showMessage('Server error', 'error');
                }
            }
        };

        xhr.send('id=' + encodeURIComponent(id));
    }
}

function showMessage(message, type) {
    var alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-' + type;
    alertDiv.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') +
        '"></i> ' + message;

    var container = document.querySelector('.admin-content');
    var firstCard = document.querySelector('.admin-card');
    container.insertBefore(alertDiv, firstCard);

    setTimeout(function() {
        alertDiv.style.opacity = '0';
        setTimeout(function() {
            alertDiv.remove();
        }, 300);
    }, 3000);
}
</script>

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

.stats-row {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.stat-card-mini {
    background: white;
    padding: 15px 25px;
    border-radius: 10px;
    text-align: center;
    min-width: 100px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.stat-card-mini .stat-value {
    font-size: 28px;
    font-weight: bold;
    color: #333;
}

.stat-card-mini .stat-label {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}

.stat-pending .stat-value {
    color: #856404;
}

.stat-resolved .stat-value {
    color: #155724;
}

.stat-rejected .stat-value {
    color: #721c24;
}

.filter-tabs {
    display: flex;
    gap: 5px;
    margin-bottom: 20px;
    background: white;
    padding: 10px;
    border-radius: 10px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.tab {
    padding: 8px 20px;
    border-radius: 6px;
    text-decoration: none;
    color: #666;
    transition: all 0.3s;
}

.tab.active {
    background: #667eea;
    color: white;
}

.tab:hover:not(.active) {
    background: #f0f0f0;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
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

.actions {
    display: flex;
    gap: 8px;
}

.btn-icon {
    background: none;
    border: none;
    cursor: pointer;
    padding: 5px 8px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.btn-view {
    color: #17a2b8;
}

.btn-view:hover {
    background: #17a2b8;
    color: white;
}

.btn-delete {
    color: #dc3545;
}

.btn-delete:hover {
    background: #dc3545;
    color: white;
}

.badge {
    background: #e9ecef;
    color: #495057;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.data-table th {
    background: #f8f9fa;
    font-weight: 600;
}

.alert {
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.text-muted {
    text-align: center;
    padding: 40px;
    color: #999;
}
</style>