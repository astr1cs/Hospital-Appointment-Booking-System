<div class="page-header">
    <h1 class="page-title">Hospital Announcements</h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=announcements&sub=create" class="btn-primary">
        <i class="fas fa-plus"></i> New Announcement
    </a>
</div>

<?php if (isset($success) && $success): ?>
<div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-header">
        <h3><i class="fas fa-bullhorn"></i> All Announcements</h3>
        <span class="badge"><?php echo $announcements->num_rows; ?> total</span>
    </div>
    <div class="card-body">
        <?php if ($announcements->num_rows > 0): ?>
        <?php while($row = $announcements->fetch_assoc()): ?>
        <div class="announcement-item" id="announcement-<?php echo $row['id']; ?>">
            <div class="announcement-header">
                <div class="announcement-title">
                    <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                    <span class="target-badge target-<?php echo $row['target_role']; ?>">
                        <i class="fas fa-users"></i>
                        <?php echo ucfirst($row['target_role']); ?>
                    </span>
                </div>
                <div class="announcement-meta">
                    <span class="date">
                        <i class="fas fa-calendar"></i>
                        <?php echo date('M d, Y h:i A', strtotime($row['published_at'])); ?>
                    </span>
                    <span class="author">
                        <i class="fas fa-user"></i>
                        <?php echo htmlspecialchars($row['author_name']); ?>
                    </span>
                    <button class="delete-btn" onclick="deleteAnnouncement(<?php echo $row['id']; ?>)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="announcement-body">
                <p><?php echo nl2br(htmlspecialchars($row['body'])); ?></p>
            </div>
        </div>
        <?php endwhile; ?>
        <?php else: ?>
        <p class="text-muted text-center">No announcements yet. Click "New Announcement" to create one.</p>
        <?php endif; ?>
    </div>
</div>

<!-- AJAX Script for Delete -->
<!-- AJAX Script with XMLHttpRequest -->
<script>
function deleteAnnouncement(id) {
    if (confirm('Are you sure you want to delete this announcement?')) {
        // Create XMLHttpRequest object
        var xhr = new XMLHttpRequest();

        // Configure the request
        xhr.open('POST', '<?php echo SITE_URL; ?>api/delete-announcement.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Set up callback function
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) { // Request complete
                if (xhr.status === 200) { // Success
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Remove the announcement from DOM
                            var element = document.getElementById('announcement-' + id);
                            if (element) {
                                element.remove();
                            }
                            // Show success message
                            showMessage(response.message, 'success');
                        } else {
                            showMessage(response.message, 'error');
                        }
                    } catch (e) {
                        showMessage('Error parsing response', 'error');
                    }
                } else {
                    showMessage('Server error: ' + xhr.status, 'error');
                }
            }
        };

        // Send the request
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

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.announcement-item {
    background: #f9f9f9;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s;
}

.announcement-item:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.announcement-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

.announcement-title {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.announcement-title h4 {
    margin: 0;
    color: #333;
}

.target-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
}

.target-all {
    background: #667eea;
    color: white;
}

.target-patient {
    background: #28a745;
    color: white;
}

.target-doctor {
    background: #17a2b8;
    color: white;
}

.target-receptionist {
    background: #ffc107;
    color: #333;
}

.announcement-meta {
    display: flex;
    gap: 15px;
    align-items: center;
    font-size: 12px;
    color: #666;
}

.announcement-meta i {
    margin-right: 4px;
}

.delete-btn {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
    padding: 5px 8px;
    border-radius: 5px;
    transition: all 0.3s;
}

.delete-btn:hover {
    background: #dc3545;
    color: white;
}

.announcement-body {
    color: #555;
    line-height: 1.6;
}

.announcement-body p {
    margin: 0;
}

.badge {
    background: #e9ecef;
    color: #495057;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
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