<div class="page-header">
    <h1 class="page-title">Find Your Perfect Doctor</h1>
    <p class="page-subtitle">Connect with experienced healthcare professionals</p>
</div>

<!-- Advanced Search & Filter Bar -->
<div class="filter-section">
    <form method="GET" action="<?php echo SITE_URL; ?>patient.php" id="filterForm">
        <input type="hidden" name="action" value="doctors">
        
        <div class="filter-row">
            <div class="filter-group search-group">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="filter-input" 
                       placeholder="Search by name or specialization..."
                       value="<?php echo htmlspecialchars($search ?? ''); ?>"
                       autocomplete="off">
            </div>
            
            <div class="filter-group">
                <select name="specialization" class="filter-select" id="specializationSelect">
                    <option value="">All Specializations</option>
                    <?php while($spec = $specializations->fetch_assoc()): ?>
                    <option value="<?php echo $spec['id']; ?>" 
                        <?php echo ($selectedSpecialization == $spec['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($spec['name']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="filter-group fee-group">
                <div class="fee-input-wrapper">
                    <span class="fee-label">$</span>
                    <input type="number" name="min_fee" class="filter-input fee-input" 
                           placeholder="Min" value="<?php echo htmlspecialchars($minFee ?? ''); ?>">
                </div>
                <span class="fee-separator">—</span>
                <div class="fee-input-wrapper">
                    <span class="fee-label">$</span>
                    <input type="number" name="max_fee" class="filter-input fee-input" 
                           placeholder="Max" value="<?php echo htmlspecialchars($maxFee ?? ''); ?>">
                </div>
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="<?php echo SITE_URL; ?>patient.php?action=doctors" class="btn-clear">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Results Count -->
<?php if ($doctors->num_rows > 0): ?>
<div class="results-count">
    <i class="fas fa-user-md"></i>
    <span><?php echo $doctors->num_rows; ?> doctors found</span>
</div>
<?php endif; ?>

<!-- Doctors Grid -->
<div class="doctors-grid">
    <?php if ($doctors->num_rows > 0): ?>
        <?php while($row = $doctors->fetch_assoc()): ?>
        <div class="doctor-card" data-doctor-id="<?php echo $row['user_id']; ?>">
            <div class="doctor-card-inner">
                <div class="doctor-badge">
                    <?php if($row['experience_years'] >= 15): ?>
                        <span class="badge expert">Expert</span>
                    <?php elseif($row['experience_years'] >= 8): ?>
                        <span class="badge senior">Senior</span>
                    <?php endif; ?>
                </div>
                
                <div class="doctor-image-wrapper">
                    <div class="doctor-image">
                        <?php if ($row['profile_pic'] && $row['profile_pic'] != 'default.jpg'): ?>
                            <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo $row['profile_pic']; ?>" alt="Doctor">
                        <?php else: ?>
                            <div class="doctor-avatar">
                                <i class="fas fa-user-md"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="doctor-info">
                    <h3 class="doctor-name"><?php echo htmlspecialchars($row['name']); ?></h3>
                    <div class="doctor-specialty">
                        <i class="fas fa-stethoscope"></i> <?php echo htmlspecialchars($row['specialization_name']); ?>
                    </div>
                    
                    <div class="doctor-rating-section">
                        <div class="doctor-rating">
                            <div class="stars">
                                <?php 
                                $rating = round($row['avg_rating'], 1);
                                $fullStars = floor($rating);
                                $halfStar = ($rating - $fullStars) >= 0.5;
                                for($i = 1; $i <= 5; $i++):
                                    if($i <= $fullStars):
                                ?>
                                    <i class="fas fa-star"></i>
                                <?php elseif($halfStar && $i == $fullStars + 1): ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <span class="rating-value"><?php echo number_format($rating, 1); ?></span>
                            <span class="reviews-count">(<?php echo $row['total_reviews']; ?> reviews)</span>
                        </div>
                    </div>
                    
                    <div class="doctor-details">
                        <div class="detail-item">
                            <i class="fas fa-briefcase"></i>
                            <span><?php echo $row['experience_years']; ?>+ years experience</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Available today</span>
                        </div>
                    </div>
                    
                    <div class="doctor-fee">
                        <div class="fee-amount">$<?php echo number_format($row['consultation_fee'], 2); ?></div>
                        <div class="fee-label">per consultation</div>
                    </div>
                </div>
                
                <div class="doctor-actions">
                    <button class="btn-view-profile" onclick="location.href='<?php echo SITE_URL; ?>patient.php?action=doctors&sub=show&id=<?php echo $row['user_id']; ?>'">
                        <i class="fas fa-user-circle"></i> View Profile
                    </button>
                    <button class="btn-book" onclick="location.href='<?php echo SITE_URL; ?>patient.php?action=appointments&sub=book&doctor_id=<?php echo $row['user_id']; ?>'">
                        <i class="fas fa-calendar-check"></i> Book Now
                    </button>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-results">
            <div class="no-results-icon">
                <i class="fas fa-search"></i>
            </div>
            <h3>No Doctors Found</h3>
            <p>We couldn't find any doctors matching your criteria</p>
            <div class="suggestions">
                <p>Try:</p>
                <ul>
                    <li>Removing some filters</li>
                    <li>Checking your spelling</li>
                    <li>Selecting a different specialization</li>
                </ul>
            </div>
            <a href="<?php echo SITE_URL; ?>patient.php?action=doctors" class="btn-reset-all">
                <i class="fas fa-undo-alt"></i> Reset All Filters
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
/* Modern Reset & Variables */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --primary-color: #667eea;
    --secondary-color: #764ba2;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --gray-100: #f8f9fa;
    --gray-200: #e9ecef;
    --gray-300: #dee2e6;
    --gray-600: #6c757d;
    --gray-800: #343a40;
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
    --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
    --shadow-xl: 0 20px 40px rgba(0,0,0,0.15);
}

.page-header {
    margin-bottom: 40px;
    text-align: center;
}

.page-title {
    font-size: 36px;
    font-weight: 700;
    margin: 0 0 12px 0;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.page-subtitle {
    color: var(--gray-600);
    font-size: 18px;
    margin: 0;
}

/* Enhanced Filter Section */
.filter-section {
    background: white;
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: var(--shadow-lg);
    transition: all 0.3s ease;
}

.filter-section:hover {
    box-shadow: var(--shadow-xl);
}

.filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: flex-end;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.search-group {
    position: relative;
    flex: 2;
}

.search-group i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-600);
    font-size: 16px;
    pointer-events: none;
}

.search-group .filter-input {
    padding-left: 45px;
}

.filter-input {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    font-size: 15px;
    transition: all 0.3s ease;
    outline: none;
}

.filter-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.filter-select {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    font-size: 15px;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    outline: none;
}

.filter-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.fee-group {
    display: flex;
    gap: 12px;
    align-items: center;
}

.fee-input-wrapper {
    position: relative;
    flex: 1;
}

.fee-label {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-600);
    font-weight: 600;
    font-size: 14px;
}

.fee-input {
    padding-left: 28px !important;
}

.fee-separator {
    color: var(--gray-600);
    font-weight: 600;
}

.filter-actions {
    display: flex;
    gap: 12px;
}

.btn-filter {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 14px 28px;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-clear {
    background: var(--gray-200);
    color: var(--gray-800);
    padding: 14px 24px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-clear:hover {
    background: var(--gray-300);
    transform: translateY(-2px);
}

/* Results Count */
.results-count {
    background: var(--gray-100);
    padding: 12px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    display: inline-block;
}

.results-count i {
    color: var(--primary-color);
    margin-right: 8px;
}

/* Doctors Grid */
.doctors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 30px;
}

.doctor-card {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
    position: relative;
}

.doctor-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.doctor-card-inner {
    padding: 28px;
    position: relative;
}

.doctor-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 1;
}

.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.badge.expert {
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    color: white;
}

.badge.senior {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.doctor-image-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.doctor-image {
    width: 120px;
    height: 120px;
    background: var(--primary-gradient);
    border-radius: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
}

.doctor-avatar i {
    font-size: 60px;
    color: white;
}

.doctor-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.doctor-name {
    text-align: center;
    font-size: 22px;
    font-weight: 700;
    margin: 0 0 8px 0;
    color: var(--gray-800);
}

.doctor-specialty {
    text-align: center;
    color: var(--primary-color);
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.doctor-rating-section {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.doctor-rating {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--gray-100);
    padding: 6px 14px;
    border-radius: 50px;
}

.stars {
    color: #ffc107;
    font-size: 13px;
}

.rating-value {
    font-weight: 700;
    color: var(--gray-800);
}

.reviews-count {
    font-size: 12px;
    color: var(--gray-600);
}

.doctor-details {
    margin-bottom: 20px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    color: var(--gray-600);
    font-size: 14px;
}

.detail-item i {
    width: 20px;
    color: var(--primary-color);
}

.doctor-fee {
    text-align: center;
    padding: 16px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    border-radius: 16px;
    margin: 20px 0;
}

.fee-amount {
    font-size: 28px;
    font-weight: 700;
    color: var(--primary-color);
}

.fee-label {
    font-size: 12px;
    color: var(--gray-600);
    margin-top: 4px;
}

.doctor-actions {
    display: flex;
    gap: 12px;
}

.btn-view-profile, .btn-book {
    flex: 1;
    padding: 12px;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-view-profile {
    background: white;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.btn-view-profile:hover {
    background: var(--primary-color);
    color: white;
}

.btn-book {
    background: var(--primary-gradient);
    color: white;
}

.btn-book:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

/* No Results */
.no-results {
    text-align: center;
    padding: 80px 40px;
    background: white;
    border-radius: 24px;
    grid-column: 1 / -1;
}

.no-results-icon i {
    font-size: 80px;
    color: var(--gray-300);
    margin-bottom: 24px;
}

.no-results h3 {
    font-size: 24px;
    margin: 0 0 12px 0;
    color: var(--gray-800);
}

.no-results p {
    color: var(--gray-600);
    margin-bottom: 24px;
}

.suggestions {
    background: var(--gray-100);
    padding: 20px;
    border-radius: 16px;
    margin: 24px 0;
    display: inline-block;
    text-align: left;
}

.suggestions p {
    font-weight: 600;
    margin-bottom: 8px;
}

.suggestions ul {
    margin: 0;
    padding-left: 20px;
}

.suggestions li {
    color: var(--gray-600);
    margin: 4px 0;
}

.btn-reset-all {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--primary-gradient);
    color: white;
    padding: 14px 28px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-reset-all:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 28px;
    }
    
    .page-subtitle {
        font-size: 16px;
    }
    
    .filter-row {
        flex-direction: column;
    }
    
    .filter-group {
        width: 100%;
    }
    
    .filter-actions {
        width: 100%;
    }
    
    .btn-filter, .btn-clear {
        flex: 1;
        justify-content: center;
    }
    
    .doctors-grid {
        grid-template-columns: 1fr;
    }
    
    .doctor-actions {
        flex-direction: column;
    }
    
    .fee-group {
        flex-direction: row;
    }
}

/* Loading Animation */
@keyframes shimmer {
    0% {
        background-position: -1000px 0;
    }
    100% {
        background-position: 1000px 0;
    }
}

.loading {
    animation: shimmer 2s infinite linear;
    background: linear-gradient(to right, #f6f7f8 8%, #edeef1 18%, #f6f7f8 33%);
    background-size: 1000px 100%;
}
</style>

<script>
// Optional: Add loading state and AJAX functionality
document.getElementById('filterForm').addEventListener('submit', function() {
    const button = document.querySelector('.btn-filter');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
    button.disabled = true;
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    }, 30000); // Reset after 30 seconds or on page load
});
</script>