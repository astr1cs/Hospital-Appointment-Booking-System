<div class="page-header">
    <h1 class="page-title">Medical History</h1>
    <p>Keep track of your personal health notes and medical history</p>
</div>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="medical-history-container">
    <div class="info-card">
        <div class="card-header">
            <h3><i class="fas fa-notes-medical"></i> Personal Health Notes</h3>
            <p class="card-desc">These notes are visible only to you and the doctors you consult</p>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo SITE_URL; ?>patient.php?action=medical-history&sub=update">
                <div class="form-group">
                    <label>Medical History Notes</label>
                    <textarea name="medical_history_notes" class="form-control" rows="12" 
                              placeholder="Enter your medical history, allergies, past surgeries, chronic conditions, medications, etc."><?php 
                        echo htmlspecialchars($medicalHistory['medical_history_notes'] ?? ''); 
                    ?></textarea>
                    <small><i class="fas fa-info-circle"></i> This information helps doctors understand your health background better.</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Save Notes
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="tips-card">
        <h4><i class="fas fa-lightbulb"></i> What to include in your medical history?</h4>
        <ul>
            <li><i class="fas fa-allergies"></i> Allergies (medications, food, etc.)</li>
            <li><i class="fas fa-syringe"></i> Current medications and dosages</li>
            <li><i class="fas fa-heartbeat"></i> Chronic conditions (diabetes, hypertension, asthma, etc.)</li>
            <li><i class="fas fa-scalpel"></i> Past surgeries or hospitalizations</li>
            <li><i class="fas fa-dna"></i> Family medical history</li>
            <li><i class="fas fa-vaccine"></i> Vaccination records</li>
            <li><i class="fas fa-stethoscope"></i> Any ongoing treatments</li>
        </ul>
    </div>
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
    margin: 0;
}

.medical-history-container {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 25px;
}

.info-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    padding: 20px 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card-header h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    color:white
}

.card-desc {
    margin: 0;
    font-size: 12px;
    opacity: 0.9;
}

.card-body {
    padding: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    resize: vertical;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

small {
    display: block;
    margin-top: 8px;
    font-size: 12px;
    color: #666;
}

small i {
    margin-right: 5px;
}

.form-actions {
    text-align: right;
}

.btn-save {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-save:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.tips-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    height: fit-content;
}

.tips-card h4 {
    margin: 0 0 15px 0;
    color: #667eea;
    display: flex;
    align-items: center;
    gap: 10px;
}

.tips-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tips-card li {
    padding: 8px 0;
    color: #555;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    border-bottom: 1px solid #f0f0f0;
}

.tips-card li:last-child {
    border-bottom: none;
}

.tips-card li i {
    width: 20px;
    color: #667eea;
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

@media (max-width: 1024px) {
    .medical-history-container {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .card-header {
        padding: 15px 20px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .form-actions {
        text-align: center;
    }
    
    .btn-save {
        width: 100%;
        justify-content: center;
    }
}
</style>