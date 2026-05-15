<div class="page-header">
    <h1 class="page-title">Find Doctors</h1>
    <p>Search and book appointments with our expert doctors</p>
</div>

<!-- Search & Filter Bar -->
<div class="filter-section">
    <form method="GET" action="<?php echo SITE_URL; ?>patient.php">
        <input type="hidden" name="action" value="doctors">
        
        <div class="filter-row">
            <div class="filter-group search-group">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="filter-input" 
                       placeholder="Search by doctor name or specialization..."
                       value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
            
            <div class="filter-group">
                <select name="specialization" class="filter-select">
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
                <input type="number" name="min_fee" class="filter-input" 
                       placeholder="Min Fee" value="<?php echo htmlspecialchars($minFee ?? ''); ?>">
                <span>-</span>
                <input type="number" name="max_fee" class="filter-input" 
                       placeholder="Max Fee" value="<?php echo htmlspecialchars($maxFee ?? ''); ?>">
            </div>
            
            <button type="submit" class="btn-filter">
                <i class="fas fa-search"></i> Search
            </button>
            
            <a href="<?php echo SITE_URL; ?>patient.php?action=doctors" class="btn-clear">
                <i class="fas fa-times"></i> Clear
            </a>
        </div>
    </form>
</div>

<!-- Doctors Grid -->
<div class="doctors-grid">
    <?php if ($doctors->num_rows > 0): ?>
        <?php while($row = $doctors->fetch_assoc()): ?>
        <div class="doctor-card">
            <div class="doctor-image">
                <?php if ($row['profile_pic'] && $row['profile_pic'] != 'default.jpg'): ?>
                    <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo $row['profile_pic']; ?>" alt="Doctor">
                <?php else: ?>
                    <i class="fas fa-user-md"></i>
                <?php endif; ?>
            </div>
            <div class="doctor-info">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <div class="doctor-specialty">
                    <i class="fas fa-stethoscope"></i> <?php echo htmlspecialchars($row['specialization_name']); ?>
                </div>
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
                    <span>(<?php echo $row['total_reviews']; ?> reviews)</span>
                </div>
                <div class="doctor-experience">
                    <i class="fas fa-briefcase"></i> <?php echo $row['experience_years']; ?>+ years experience
                </div>
                <div class="doctor-fee">
                    <i class="fas fa-dollar-sign"></i> Consultation Fee: $<?php echo number_format($row['consultation_fee'], 2); ?>
                </div>
            </div>
            <div class="doctor-actions">
                <a href="<?php echo SITE_URL; ?>patient.php?action=doctors&sub=show&id=<?php echo $row['user_id']; ?>" 
                   class="btn-view-profile">
                    <i class="fas fa-eye"></i> View Profile
                </a>
                <a href="<?php echo SITE_URL; ?>patient.php?action=appointments&sub=book&doctor_id=<?php echo $row['user_id']; ?>" 
                   class="btn-book">
                    <i class="fas fa-calendar-plus"></i> Book Appointment
                </a>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-results">
            <i class="fas fa-search"></i>
            <h3>No doctors found</h3>
            <p>Try adjusting your search criteria</p>
            <a href="<?php echo SITE_URL; ?>patient.php?action=doctors" class="btn-clear">Clear Filters</a>
        </div>
    <?php endif; ?>
</div>

<style>
.page-header {
    margin-bottom: 30px;
}

.page-title {
    font-size: 28px;
    margin: 0 0 10px 0;
    color: #333;
}

.page-header p {
    color: #666;
}

.filter-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.search-group {
    position: relative;
}

.search-group i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}

.search-group .filter-input {
    padding-left: 35px;
}

.filter-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}

.filter-select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    background: white;
}

.fee-group {
    display: flex;
    gap: 10px;
    align-items: center;
}

.fee-group .filter-input {
    width: 100px;
}

.btn-filter {
    background: #667eea;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
}

.btn-clear {
    background: #6c757d;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
}

.doctors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
}

.doctor-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    gap: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.doctor-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}

.doctor-image {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.doctor-image i {
    font-size: 50px;
    color: white;
}

.doctor-image img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.doctor-info {
    flex: 1;
}

.doctor-info h3 {
    margin: 0 0 8px 0;
    color: #333;
}

.doctor-specialty {
    color: #667eea;
    font-size: 13px;
    margin-bottom: 8px;
}

.doctor-rating {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.stars {
    color: #ffc107;
    font-size: 13px;
}

.doctor-rating span {
    font-size: 12px;
    color: #666;
}

.doctor-experience, .doctor-fee {
    font-size: 13px;
    color: #555;
    margin-bottom: 5px;
}

.doctor-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
    min-width: 120px;
}

.btn-view-profile, .btn-book {
    padding: 8px 12px;
    border-radius: 6px;
    text-decoration: none;
    text-align: center;
    font-size: 13px;
}

.btn-view-profile {
    background: #f8f9fa;
    color: #667eea;
    border: 1px solid #667eea;
}

.btn-view-profile:hover {
    background: #667eea;
    color: white;
}

.btn-book {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-book:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
}

.no-results {
    text-align: center;
    padding: 60px;
    background: white;
    border-radius: 12px;
}

.no-results i {
    font-size: 60px;
    color: #ccc;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .doctors-grid {
        grid-template-columns: 1fr;
    }
    
    .doctor-card {
        flex-direction: column;
        text-align: center;
    }
    
    .doctor-image {
        margin: 0 auto;
    }
    
    .doctor-rating {
        justify-content: center;
    }
    
    .filter-row {
        flex-direction: column;
    }
    
    .filter-group {
        width: 100%;
    }
}
</style>